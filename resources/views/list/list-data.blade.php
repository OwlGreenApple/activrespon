@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>LISTS</h2>
  </div>

  <div class="act-tel-dashboard-right">
     <a href="{{url('list-form')}}" class="btn btn-custom">Create List</a>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container mt-2">
  <div class="act-tel-tab">
      <div class="input-group">
          <input type="text" name="listname" class="form-control-lg col-lg-4 search-box" placeholder="Find a List By a name" >
          <span class="input-group-append">
            <div class="btn search-icon">
                <span class="icon-search"></span>
            </div>
          </span>
      </div>
  </div>
</div>

<!-- NUMBER -->
<div class="container">
  <div class="act-tel-tab">
      <div class="message mt-2"><!-- message --></div>
      <div id="display_list">
        @if($lists->count() > 0)
          @include('list.list-table')
        @else
          <div class="alert bg-dashboard cardlist">
            Currently you don't have any list, please click : <b>Create List</b>.
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

<!-- Modal  -->
<div class="modal fade" id="table-data" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Data Subscriber
        </h5>
      </div>
      <div class="modal-body">
       
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" data-dismiss="modal">
          Close
        </button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Import Contact -->
<div class="modal fade child-modal" id="export-contact" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content -->
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Export Subscriber
        </h5>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="mb-2">
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-controller" name="status_export" value="0" checked/> For Data
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-controller" name="status_export" value="1"/> For Import
              </label>
            </div>
             
            <div class="text-right">
              <a id="btn-export" class="btn btn-activ mr-1">Export</a>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    </div>
    
  </div>
</div>
<!-- End Modal -->

<script type="text/javascript">

  $(document).ready(function(){
    searchList();
    deleteList();
    duplicateList();
    copyLink();
    pagination();
    openExport();
    changeExport();
    $('[data-toggle="tooltip"]').tooltip()
  });

  function openExport() {
    $("body").on("click",".open_export",function(){
      var list_id = $(this).attr('id');
      $("#export-contact").modal();
      $("input[name='status_export'][value="+0+"]").prop('checked',true);
      $("#btn-export").attr('href',"{{url('export_excel_list_subscriber')}}/"+list_id+'/'+0);
      $("#btn-export").attr('list_id',list_id);
    });
  }

  function changeExport()
  {
    $("input[name='status_export']").change(function(){
      var val = $(this).val();
      var list_id = $("#btn-export").attr('list_id');
      $("#btn-export").attr('href',"{{url('export_excel_list_subscriber')}}/"+list_id+'/'+val);
    });
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
          loadPagination(url);
      });
  }

  function loadPagination(url) {
      $.ajax({
        beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
        url: url
      }).done(function (data) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          getActiveButtonByUrl(url);
          $('#display_list').html(data);
      }).fail(function (xhr,attr,throwable) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          alert("Sorry, Failed to load data! please contact administrator");
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

  function searchList(){
    $(".search-icon").click(function(){
        var listname = $("input[name=listname]").val();

        $.ajax({
          type : 'GET',
          url : '{{route("searchlist")}}',
          data : {'listname' : listname},
          dataType : 'html',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            $("#display_list").html(result);
          }
        });
    });
  }

   function deleteList(){
    $('body').on('click',".del",function(){
      var id = $(this).attr('id');
      var mulr = window.location.href;
      var conf = confirm('Are you sure to delete this list?');

      if(conf == true)
      {
        $.ajax({
          type : 'GET',
          url : "{{route('deletelist')}}",
          data : {'id' : id},
          dataType : 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            $(".message").html('<div class="alert alert-success">'+result.message+'</div>');
            loadPagination(mulr);
          },
          error : function(xhr)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xh.responseText);
          }
        });
      } else {
        return false;
      }
     
    });
  }

  function duplicateList(){
    $("body").on("click",".duplicate",function(){
        var id = $(this).attr('id');
        var mulr = window.location.href;
        var conf = confirm('Do you want to duplicate this list?');
        
        if(conf == true)
        {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
           $.ajax({
              type : 'POST',
              url : '{{route("duplicatelist")}}',
              data : {'id' : id},
              dataType : "json",
              beforeSend: function()
              {
                $('#loader').show();
                $('.div-loading').addClass('background-load');
              },
              success : function(result)
              {
                 $('#loader').hide();
                 $('.div-loading').removeClass('background-load');

                 if(result.error == true)
                 {
                    $('.message').html('<div class="alert alert-danger">'+result.message+'</div>');
                 }
                 else
                 {
                    $('.message').html('<div class="alert alert-success">'+result.message+'</div>');
                    loadPagination(mulr);
                 }
              },
              error:function(xhr)
              {
                 $('#loader').hide();
                 $('.div-loading').removeClass('background-load');
                 console.log(xhr.responseText);
              }
          });
        }
        else {
          return false;
        }
        
    });
    }

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

    /*$(document).ready(function(){
        table();
        displayDataAdditional();
        openImport();
        csvImport();
    });

    //TO IMPORT
    function openImport()
    {
        $(".import-col").click(function(){
            $("#import_popup").modal();
            var id = $(this).attr('id');
            $("input[name='list_id_import']").val(id);
        });
    } 

    function csvImport()
    {
        $("body").on("submit","#import_list_subscriber",function(e){
            e.preventDefault();
            var form = $(this)[0];
            var data = new FormData(form);

            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                type : 'POST',
                enctype: 'multipart/form-data',
                url : '{{url("import_csv_list_subscriber")}}',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                data : data,
                dataType : 'json',
                success : function(response)
                {
                    alert(response.message);
                }
            })
        });
    }

    function displayDataAdditional(){
        $("body").on("click",".addt",function(){
            var listid = $(this).attr('id');
            var boxdata = '';
            $("#additional_popup").modal();

            $.ajax({
                type:'GET',
                url:'{{route("customeradditional")}}',
                data : {id:listid},
                dataType : 'json',
                success : function(response) 
                {
                    //console.log(response.additonal);
                    $.each(response.additonal,function(key,value){

                        boxdata += '<div class="form-group row"><label class="col-md-3 col-form-label text-md-right"><b class="text-capitalize">'+key+'</b></label><div class="col-md-8 form-control">'+value+'</div></div></div>';
                    });
                    $("#displayadditional").html(boxdata);
                }
            });
        });
    }
    */

</script>
@endsection

