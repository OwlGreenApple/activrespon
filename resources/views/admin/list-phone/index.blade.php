@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;
  var tableLog;

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-phone/load') ?>",
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
                  responsive : false,
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

        <h2><b>Phone Numbers</b></h2>  
        
        <h5>
          Show you all phone numbers 
        </h5>
        

        <div id="pesan" class="alert"></div>

        <form>
          <table class="table" id="myTable">
            <thead align="center">
              <th>
                Email
              </th>
              <th>
                Phone Number
              </th>
              <th>
                Counter
              </th>
              <th>
                Created
              </th>
              <th>
                Key
              </th>
              <th>
                Link Screenshoot
              </th>
              <th>
                Restart Phone
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
    restartSpiderman();

    // $('.formatted-date').datepicker({
      // dateFormat: 'yy/mm/dd',
    // });
  });

  function restartSpiderman()
  {
    $("body").on("click",".server-restart",function(){
      var url = $(this).attr('data-url');
      var folder = $(this).attr('data-folder');
      var btn_id = $(this).attr('id');
      var phone_id = $(this).attr('data-phone-id');

      $("#"+btn_id).html('Loading....').addClass('disabled');
      dorestartSpiderman(url,folder,btn_id,phone_id);
    });
  }

  function dorestartSpiderman(url,folder,btn_id,phone_id)
  {
      var data = {"url":url, "folder":folder, "id":phone_id};
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type : "POST",
        url: "{{ url('restart-simi') }}",
        data: data,
        timeout: 600000,
        success: function(result){
            $("#"+btn_id).html('Restart').removeClass('disabled');
            alert(result.response);
        },
        error : function(xhr){
          $("#"+btn_id).html('Restart').removeClass('disabled');
          console.log(xhr.responseText);
          alert('server error');
        }
      });
  }

  
</script>
@endsection