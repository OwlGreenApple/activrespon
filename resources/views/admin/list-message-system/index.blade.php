@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;
  var tableLog;

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-message-system/load') ?>",
      dataType: 'text',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');

        var data = jQuery.parseJSON(result);
        $('#content').html(data.view);
        
        table = $('#myTable').DataTable({
                  responsive : true,
                  destroy: true,
                  "order": [],
                });
      }
    });
  }

</script>

<section id="tabs" class="col-md-10 offset-md-1 col-12 pl-0 pr-0 project-tab" style="margin-top:30px;margin-bottom: 120px;">
  <div class="container body-content-mobile main-cont">
    <div class="row">

    <div class="col-md-11">

      <h2><b>Message System</b></h2>  
      
      <h5>
        All message activomni
      </h5>
      

      <div id="pesan" class="alert"></div>



      <div class="form-group">
      
        <!--<button class="btn btn-primary mb-4" data-toggle="modal" data-target="#modal-add-user">
          Add User (Excel)
        </button>-->
      </div>

      <form>
        <table class="table" id="myTable">
          <thead align="center">
            <th>
              Phone number 
            </th>
            <th>
              Message
            </th>
            <th>
              Status
            </th>
            <th>
              Created
            </th>
            <th>
              Action
            </th>
          </thead>
          <tbody id="content">
          </tbody>
        </table>

        <div id="pager"></div>    
      </form>
    </div>
  </div>
</div>

<!-- Modal View confirm delete -->
<div class="modal fade" id="modal-confirm-delete" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Are you sure want to delete
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

          <input type="hidden" name="idmessage" id="idmessage">
          <button type="button" class="btn" data-dismiss="modal">Cancel</button>
          <button id="btn-process-resend" type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
        </table>
      </div>
    </div>
      
  </div>
</div>


</section>

<script type="text/javascript">


  $(document).ready(function() {
    table = $('#myTable').DataTable({
                responsive : true,
                destroy: true,
                "order": [],
            });

    tableLog = $('#tableLog').DataTable({
                responsive : true,
                destroy: true,
                "order": [],
            });
            
    // $.fn.dataTable.moment( 'ddd, DD MMM YYYY' );
    moment( 'ddd, DD MMM YYYY' );

    refresh_page();

    // $('.formatted-date').datepicker({
      // dateFormat: 'yy/mm/dd',
    // });
    
    $( "body" ).on( "click", ".btn-resend", function() {
      $('#idmessage').val($(this).attr('data-id'));
    });
    $( "body" ).on( "click", "#btn-process-resend", function() {
      $.ajax({
        type : 'GET',
        url : "<?php echo url('/list-message-system/resend') ?>",
        dataType: 'text',
        data : {'id':$('#idmessage').val()},
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(result) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          alert("message process");
          
        }
      });
    });
  });

  
</script>
@endsection