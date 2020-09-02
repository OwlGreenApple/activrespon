@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-woowa/load-woowa') ?>",
      dataType: 'text',
    //   beforeSend: function()
    //   {
    //     $('#loader').show();
    //     $('.div-loading').addClass('background-load');
    //   },
      success: function(result) {
        // $('#loader').hide();
        // $('.div-loading').removeClass('background-load');

        var data = jQuery.parseJSON(result);
        $('#content').html(data.view);
        var spantagihan = (parseInt($('#total_tagihan').val())).toLocaleString('en');
        $('#span-tagihan').html('Rp. '+ spantagihan );

        table = $('#myTable').DataTable({
                  // responsive: true,
                  destroy: true,
                  "order": [],
                });
      }
    });
  }

	function create_invoice(){
		$( "body" ).on( "click", "#button-create-ok", function() {

			$.ajax({
				url: '<?php echo url('/list-woowa/create-invoice'); ?>',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'post',
				data: {
					id:1 //no need data
				},
				
				dataType: 'text',
				success: function(result)
				{
					var data = jQuery.parseJSON(result);
					console.log("success");
					
					if(data.status=='error'){
						$('#pesan').html('<div class="alert alert-warning"><strong>Warning!</strong> '+data.message+'</div>');
					} else {
						$('#pesan').html('<div class="alert alert-success"><strong>Success!</strong> '+data.message+'</div>');
						
						refresh_page();
					}
					$('#pesan').show();
				}
			});
		});

	}

</script>

<section id="tabs" class="col-md-10 offset-md-1 col-12 pl-0 pr-0 project-tab" style="margin-top:30px;margin-bottom: 120px;">
  <div class="container body-content-mobile main-cont">
    <div class="row">
    <div class="col-md-11">

      <h2><b>WooWA</b></h2>  
      
      <h5>
        Show you previous history orders
      </h5>
      
      <hr>

      <div id="pesan" class="alert" style="display: none;"></div>

                <div class="row" style="margin-bottom: 15px; margin-left: 1px;">
                  <div class="col-md-6 col-12 row">

                    
                  </div>
                  <div class="col-md-6 col-12 div-tagihan" align="right">
                    Total Tagihan = <span id="span-tagihan"></span>
                  </div>
                </div>

      <form class="table-responsive">
        <table class="table" id="myTable">
          <thead align="center">
          <!--   <th>
              No
            </th> -->
            <th>
              No WA
            </th>
            <th action="no_order">
              No Order
            </th>
            <!--
            <th action="grand_total all">
              Total
            </th>
            -->
            <th action="month">
              Month
            </th>
            <th action="tagihan">
              Tagihan
            </th>
            <th action="created_at">
              Created
            </th>
          </thead>
          <tbody id="content"></tbody>
        </table>

        <div id="pager"></div>    
				<button type="button" class="btn btn-primary btn-create" data-toggle="modal" id="btn-create-ex" data-target="#confirm-create" data-action="extend" style="margin-bottom: 10px"> Create Invoice </button>
      </form>
    </div>
  </div>
</div>

<!-- Modal confirm create-->
<div class="modal fade" id="confirm-create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Create Invoice</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="totalorder" id="totalorder">
        Total : <span id="totaltagihan"></span> <br>
        Create Invoice?
      </div>

      <input type="hidden" name="type" id="type">

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Cancel
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-primary" id="button-create-ok">
          Yes
        </button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $( "body" ).on( "click", "#btn-create-ex", function() {
		$("#totaltagihan").html($('#span-tagihan').html());
  });

  $( "body" ).on( "click", ".btn-search", function() {
    currentPage = '';
    refresh_page();
  });


  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    currentPage = $(this).attr('href');
    refresh_page();
  });

  $(document).ready(function() {
    table = $('#myTable').DataTable({
                responsive : true,
                destroy: true,
                "order": [],
            });
    // $.fn.dataTable.moment( 'ddd, DD MMM YYYY' );

    refresh_page();
		create_invoice();
    // $('.formatted-date').datepicker({
    //   dateFormat: 'yy/mm/dd',
    // });
  });

</script>
@endsection