@extends('layouts.app')

@section('content')

<div class="container mb-5 main-cont">
  <div class="row">
    <div class="col-md-12">
      <h2><b>Orders</b></h2>  
      <h5>List of your WA chat members.</h5>
      <hr>
    </div>

    <div class="col-md-12">
      <button id="add_member" class="btn btn-primary">Add Member</button>
      <div class="error"><!-- error when unable to insert database --></div>
      <div class="card-body table-responsive">
        <div id="chat_members"><!-- data --></div>
      </div>
      <!-- <div class="alert bg-dashboard cardlist">
        You don't have any order yet, please make order <a href="{{ url('pricing') }}">Here</a>
      </div> -->
    </div>

  </div>
</div>

<!-- Modal Add Member -->
<div class="modal fade" id="add_member_form" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Add Member Chat
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="add_member_chat">
        <div class="modal-body">
            <div class="form-group">
              <label><b>Member Name</b></label>
              <input type="text" class="form-control" name="member_name" />
            </div>
            <div class="form-group">
              <label><b>Phone Number</b></label>
              <input type="text" class="form-control" name="phone_number" />
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Save</button>
          <button class="btn" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
      
  </div>
</div>

<!-- Modal Confirm Delete -->
<div class="modal fade" id="confirm-delete" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Delete Confirmation
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete?

        <input type="hidden" name="id_delete" id="id_delete">
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" id="btn-delete-ok" data-dismiss="modal">
          Yes
        </button>
        <button class="btn" data-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    add_member_form();
    add_member();
    loadMember();
  });

  function add_member_form()
  {
    $("#add_member").click(function(){
      $("#add_member_form").modal();
    });
  }

  function add_member()
  {
      $("#add_member_chat").submit(function(e){
        var data = $(this).serialize();
        add_member_save(data);
        e.preventDefault();
      });
  }

  function add_member_save(data){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      type : 'POST',
      url : "{{ url('member_save') }}",
      data : data,
      dataType: 'json',
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        if(result.response == true){
          loadMember();
          $("input").val('');
        }
        else{
          $(".error").html("<div class='alert alert-danger'>Sorry, our server is too busy, please try again later.</div>");
        }
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  function loadMember() {
    $.ajax({
      type : 'GET',
      url : "{{ url('get_chat_member') }}",
      dataType: 'html',
      success: function(result){
        $("#chat_members").html(result);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }


  $( "body" ).on( "click", ".view-details", function() {
    var id = $(this).attr('data-id');

    $('.details-'+id).toggleClass('d-none');
  });
  
  $( "body" ).on( "click", ".btn-search", function() {
    currentPage = '';
    refresh_page();
  });

  $( "body" ).on( "click", ".btn-confirm", function() {
    $('#id_confirm').val($(this).attr('data-id'));
    $('#mod-no_order').html($(this).attr('data-no-order'));
    $('#mod-package').html($(this).attr('data-package'));

    var total = parseInt($(this).attr('data-total'));
    $('#mod-total').html('Rp. ' + total.toLocaleString());
    var diskon = parseInt($(this).attr('data-discount'));
		if (diskon == 0 ) {
			$("#div-discount").hide();
		}
    $('#mod-discount').html('Rp. ' + diskon.toLocaleString());
    $('#mod-date').html($(this).attr('data-date'));

    var keterangan = '-';
   // console.log($(this).attr('data-keterangan'));
    if($(this).attr('data-keterangan')!='' || $(this).attr('data-keterangan')!=null){
      keterangan = $(this).attr('data-keterangan');
    }

    $('#mod-keterangan').html(keterangan);
  });

  $( "body" ).on( "click", ".popup-newWindow", function()
  {
    event.preventDefault();
    window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
  });

  $( "body" ).on( "click", ".btn-delete", function() {
    $('#id_delete').val($(this).attr('data-id'));
  });

  $( "body" ).on( "click", "#btn-delete-ok", function() {
    delete_order();
  });

  $(document).on('click', '.checkAll', function (e) {
    $('input:checkbox').not(this).prop('checked', this.checked);
  });

</script>
@endsection