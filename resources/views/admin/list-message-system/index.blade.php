@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;
  var tableLog;

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-user/load-user') ?>",
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

      <br>  

      <div class="form-group">
        <div id="user-charts" style="height: 300px; width: 100%;"></div>
      </div>

      <br>

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
  });

  
</script>
@endsection