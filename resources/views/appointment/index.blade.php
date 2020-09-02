@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2 class="campaign">Appointments List</h2>
  </div>

  <div class="act-tel-dashboard-right">
    <h5><a class="act-tel-apt-create" href="{{url('create-apt')}}">SETUP APPOINTMENT TEMPLATE</a></h5>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container mt-2">
  <div class="act-tel-tab">
    <div class="row">
      <div class="input-group col-lg-4">
          <input type="text" class="form-control-lg col-lg-10 search-box" placeholder="Find appointment" >
          <span class="input-group-append">
            <div class="btn search-icon">
                <span class="icon-search"></span>
            </div>
          </span>
      </div> 

      <div class="col-lg-6"></div>

      <div class="clearfix"></div>

    </div>
  </div>
</div>

<!-- NUMBER -->
<div class="container">
  <div class="act-tel-tab">
      <div id="display_appointment">
        @if($appointments->count() > 0)
          @include('appointment.table_apt')
        @else
          <div class="alert bg-dashboard cardlist">
            Currently you don't have any appointments, please click : <b>SETUP APPOINTMENT TEMPLATE</b>.
          </div>
        @endif
      </div>
  </div>
</div>

<!-- Modal Copy Link -->
<div class="modal fade" id="copy-link" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Copy Link
        </h5>
      </div>
      <div class="modal-body">
        You have copied the link!
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" data-dismiss="modal">
          OK
        </button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Edit Campaign Name -->
  <div class="modal fade child-modal" id="edit-campaign" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2">
                  <form id="edit_campaign_name">
                    <label>Edit Campaign Name</label>
                    <div class="form-group">
                      <input type="text" class="form-control" name="campaign_name" />
                      <span class="error campaign_name"></span>
                    </div>
                    <input type="hidden" name="campaign_id" />
                    <span class="error campaign_id"></span>
                 
                    <div class="text-right">
                      <button  type="submit" class="btn btn-custom mr-1">Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
                
            </div>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

<script type="text/javascript">

  var global_url = "{{ url('appointment') }}";

  $(document).ready(function(){
      pagination();
      editCampaignName();
      saveCampaignEditName();
      delAppointment();
      searchAppointment();
      MDTimepicker(); 
      neutralizeClock();
      copyLink();

  });

  function clearToolTip()
  {
     $('[data-toggle="tooltip"]').tooltip('hide');
  }

  //ajax pagination
  function pagination()
  {
      $(".page-item").removeClass('active').removeAttr('aria-current');
      var mulr = window.location.href;
      getActiveButtonByUrl(mulr)
    
      $('body').on('click', '.pagination .page-link', function (e) {
          e.preventDefault();
          var url = $(this).attr('href');
          window.history.pushState("", "", url);
          var search = $(".search-box").val();
          loadPagination(url,search);
      });
  }

  function loadPagination(url,search) {
      $.ajax({
        beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
        url: url,
        data : {'search':search},
      }).done(function (data) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          clearToolTip();
          getActiveButtonByUrl(url);
          $('#display_appointment').html(data);
      }).fail(function (xhr,attr,throwable) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $('#display_appointment').html("<div class='alert alert-warning'>Sorry, Failed to load data! please contact administrator</div>");
          console.log(xhr.responseText);
      });
  }

  function getActiveButtonByUrl(url)
  {
    var page = url.split('?');
    if(page[1] !== undefined)
    {
      var pagevalue = page[1].split('=');
      $(".page-link").each(function(){
         var text = $(this).text();
         if(text == pagevalue[1])
          {
            $(this).attr('href',url);
            $(this).addClass('on');
          } else {
            $(this).removeClass('on');
          }
      });
    }
    else {
        var mod_url = url+'?page=1';
        getActiveButtonByUrl(mod_url);
    }
  }
  //end ajax pagination

   function copyLink(){
      $( "body" ).on("click",".btn-copy",function(e) 
      {
        e.preventDefault();
        e.stopPropagation();

        var link = $(this).attr("data-link");

        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = link;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        $('#copy-link').modal('show');
      });
    }

  function delAppointment()
  {
    $("body").on("click",".appt-del",function(){
      var id = $(this).attr('id');
      var conf = confirm("Are you sure to delete this appointment?"+"\n"+"WARNING : This cannot be undone");

      if(conf == true)
      {
        $.ajax({
          type : 'GET',
          url : '{{ url("appt-del") }}',
          data : {id : id},
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            // alert(result.message);
            loadPagination(global_url,null);
          },
          error : function(xhr, attr, throwable)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
        });
      }
      else 
      {
        return false;
      }
    });
  }

  function searchAppointment()
  {
      $(".search-icon").click(function(){
        var search = $(".search-box").val();
        loadPagination(global_url,search);
      });
  }

  function editCampaignName(){
      $("body").on("click",".edit",function(){
        var id = $(this).attr('id');
        var name = $(this).attr('data-name');

        $("#edit-campaign").modal();
        $("input[name='campaign_name']").val(name);
        $("input[name='campaign_id']").val(id);
      });

  } 

  function saveCampaignEditName()
  {
      $("#edit_campaign_name").submit(function(e){
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{ url("edit-campaign-name") }}',
          data : data,
          dataType : 'json',
          beforeSend : function(){
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            $(".search-result").hide();

            if(result.success == 1)
            {
              $(".campaignid-"+result.id).html(result.campaign_name);
              $("#edit-campaign").modal('hide');
              $(".error").hide();
            }
            else
            {
              $(".error").show(); 
              $(".campaign_name").html(result.campaign_name);
              $(".campaign_id").html(result.campaign_id);
              if(result.error_server !== undefined)
              {
                alert(result.error_server);
              }
            }
            
          },
          error : function(xhr)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
        });
      });
  }

  function MDTimepicker(){
      $("body").on('focus','.timepicker',function(){
          $(this).mdtimepicker({
            format: 'hh:mm',
          });
      });
    }

    /* prevent empty col if user click cancel on clock */
    function neutralizeClock(){
       $("body").on("click",".mdtp__button.cancel",function(){
          $(".timepicker").val('00:00');
      });
    }

</script>
@endsection
