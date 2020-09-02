@extends('layouts.app')

@section('content')

<!-- navbar -->
<div class="container mb-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a href="{{route('home')}}" class="nav-link">Back Home</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('userlist')}}" class="nav-link">Back To List</a>
                </li>
              </ul>
            </nav>
        </div>
    </div>
</div>
<!-- end navbar -->

<div class="container mb-2">
    <!-- add list-->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Create List</b></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif 

                    @if (session('error_number'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error_number') }}
                        </div>
                    @endif 

                     <form name="event_form" method="POST" action="{{ route('addlist') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Bot API</label>
                            <div class="col-md-8">
                                <input name="bot_api" class="form-control" />
                                @if(session('bot_check_number'))
                                    <div class="error" role="alert">
                                        {{ session('bot_check_number') }}
                                    </div>
                                @endif 
                            </div>
                        </div>   

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Bot Name</label>
                            <div class="col-md-8">
                                 <input type="text" class="form-control" name="bot_name" />
                            </div>
                             @if(session('bot_name'))
                                <div class="error">
                                    {{ session('bot_name') }}
                                </div> 
                            @endif
                        </div> 

                         <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Name List</label>
                            <div class="col-md-8">
                                 <input type="text" class="form-control" name="label_name" />
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Category</label>

                            <div class="col-md-8">
                                <select name="category" class="form-control">
                                    <option value="0">Request Message</option>
                                    <option value="1">Event</option>
                                </select>

                                @if (session('category'))
                                    <div class="error">
                                        {{ session('category') }}
                                    </div> 
                                @endif
                                @if (session('isevent'))
                                    <div class="error">
                                        {{ session('isevent') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row dev">
                            <label class="col-md-3 col-form-label text-md-right">Event Date</label>

                            <div class="col-md-8">
                               <span id="event_date"></span>
                            </div>
                        </div>

                         @if (session('date_event'))
                         <div class="form-group row">
                             <label class="col-md-3 col-form-label text-md-right"></label>
                            <div class="col-md-8">
                               <div class="error">
                                    {{ session('date_event') }}
                                </div> 
                            </div>
                        </div>
                        @endif     

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Create input</label>

                            <div class="col-md-8 row">
                               <select id="type_fields" class="form-control col-md-8">
                                    <option value="1">Fields</option>
                                    <option value="2">Dropdown</option>
                                </select>
                                <input class="btn btn-default btn-sm add-field col-md-4" type="button" value="Add Field" />
                            </div>
                        </div>

                        <div id="append" class="form-group row">
                           <!-- display input here -->
                        </div> 
                        
                         <div class="form-group">
                            <label>Page Header</label>
                            <div class="col-md-12">
                                 <textarea name="editor1" id="editor1" rows="10" cols="80"></textarea>
                            </div>
                        </div> 
    
                                
                        <div class="form-group">
                            <label>Pixel</label>
                            <div class="col-md-12">
                                 <textarea name="pixel_txt" class="form-control"></textarea>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label>Message</label>
                            <div class="col-md-12">
                                 <textarea name="message_txt" class="form-control"></textarea>
                            </div>
                        </div> 

                        <!-- submit button -->
                         <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Add List
                                </button>
                            </div>
                        </div>
                     </form>
                     <!-- end form -->

                </div>
            </div>
        </div>
    </div>
<!-- end container -->   
</div>

<!-- Modal To Add Column -->
  <div class="modal fade" id="openMenu" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4>Create Field</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                 

                <form id="save_fields">
                    
                </form>

            </div>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Modal Dropdown -->
  <div class="modal fade" id="openDropdown" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2">
                    <input class="btn btn-default btn-sm add-option" type="button" value="Add Option" />
                </div>
                 <div class="form-group">
                    <label>Dropdown name</label>
                   <input id="dropdown_name" type="text" class="form-control" />
                </div> 
                <label>Option Value</label>
                <div id="appendoption" class="form-group row">
                   <!-- display input here -->
                </div> 
                <div class="form-group">
                   <button id="cdp" class="btn btn-success btn-sm">Create Dropdown</button>
                </div>
            </div>
        </div>
      </div>
      
    </div>
  </div>

<script type="text/javascript">
    /* CKEditor */
     CKEDITOR.replace( 'editor1',{
        allowedContent: true,
        allowedContent: true,
        filebrowserBrowseUrl: "{{ route('ckbrowse') }}",
        filebrowserUploadUrl: "{{ route('ckupload') }}",
        extraPlugins: ['uploadimage','colorbutton','justify','image2','font','videoembed'],
        removePlugins : 'image',
    });

    CKEDITOR.editorConfig = function( config ) {
        config.extraAllowedContent = true;
        config.extraPlugins = 'uploadimage','colorbutton','justify','image2','font','videoembed';
        config.removePlugins = 'image';
    };


    $(document).ready(function(){
        displayEventField();
        openMenu();
        addCols();
        delCols();
        //saveInputs();
        addDropdown();
        delDrop();
        addDropdownToField();
        displayDropdownMenu();
        delOption();
    });

    function saveInputs()
    {
        $("body").on("submit","#save_fields",function(e){
            e.preventDefault();
            var data = $(this).serialize();
            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
            });
            $.ajax({
                type : 'POST',
                url : "",
                data : data,
                dataType:'json',
                success : function(result){

                }
            });
        });
    }

    function openMenu()
    {
        $('#open_modal').click(function(){
            $("#openMenu").modal();
        });
    }

    /* Datetimepicker */
     $("body").on('focus','.evd',function () {
          $('#datetimepicker').datetimepicker({
            format : 'YYYY-MM-DD HH:mm',
          });
      });

     /* Display event date field */
    function displayEventField(){
        $(".dev").hide();
        $("select[name='category']").change(function(){
            var val = $(this).val();

            if(val == 1){
                $(".dev").show();
                $("#event_date").append("<input id='datetimepicker' type='text' name='date_event' class='form-control evd' />");
            } else {
                $(".dev").hide();
                $(".evd").remove();
            }
            
        });
    }

     function addCols(){
      $("body").on('click','.add-field',function(){
        var len = $(".fields").length;
        var type = $("#type_fields").val();
        var box_html;

         box_html = '<div class="col-md-3 col-form-label text-md-right pos-'+len+'"></div><div class="col-md-9 row pos-'+len+'"><input name="fields[]" class="form-control mb-2 col-md-6 fields pos-'+len+'" /><a id="'+len+'" class="del mb-2 col-md-2 btn btn-warning">Delete</a><select name="isoption[]" class="pos-'+len+' form-control col-md-3"><option value="0">Optional</option><option value="1">Require</option></select></div>';
       
        if(len < 5 && type == 1){
            $("#append").append(box_html);
        } else if(len < 5 && type == 2) {
            $("#openDropdown").modal();
        }
        else {
            alert('You only can create 5 inputs');
        }
      });
    } 

    function addDropdown()
    {
        $("body").on("click",".add-option",function(){
            var flen = $(".fields").length;
            var len = $(".doption").length;
            var dropdown = '<input name="doption[]" class="form-control mb-2 col-sm-8 float-left doption dpos-'+len+'" /><a id="dpos-'+len+'" class="deloption mb-2 col-sm-3 btn btn-warning">Delete</a>';

            if(flen < 5){
                $("#appendoption").append(dropdown);
            } else {
                alert('You only can create 5 inputs');
            }
        });
    }


    function delOption()
    {
      $("body").on("click",".deloption",function(){
          var opt = $(this).attr('id');
          $('#'+opt).remove();
          $('.'+opt).remove();
      });
    }

    function addDropdownToField()
    {
         $("body").on("click","#cdp",function(){
            var len = $(".fields").length;
            var options = '';
            var optionName = $("#dropdown_name").val();
            $(".doption").each(function(){
                value = $(this).val();
                options += '<input name="dropfields['+len+'][]" class="form-control" value="'+value+'"/>';
            });
            var box_html = '<label class="col-md-3"></label> <div class="col-md-9 row"><input name="dropdown[]" pos="'+len+'" class="fields pos-'+len+' form-control col-sm-6 toggledropdown" value="'+optionName+'" /><a id="'+len+'" class="del mb-2 col-sm-3 btn btn-warning">Delete</a><div style="padding : 0" id="togglepos-'+len+'" class="pos-'+len+' col-sm-9 hiddendropdown mb-2">'+options+'</div></div>';
            
            if(len < 5)
            {
                $("#append").append(box_html);
                $(".doption, .deloption").remove();
            }
            else 
            {
                alert('You only can create 5 inputs');
            }

         });
    }

    function displayDropdownMenu()
    {
        $("body").on("click",".toggledropdown",function(){
            var id = $(this).attr('pos');
            $("#togglepos-"+id).slideToggle();
        });
    }

    /*function addDropdownToField()
    {
         $("body").on("click","#cdp",function(){
            var len = $(".fields").length;
            var options = '';
            var optionName = $("#dropdown_name").val();
            $(".doption").each(function(){
                value = $(this).val();
                options += '<option value="'+value+'">'+value+'</option>';
            });
            var box_html = '<div class="pos-'+len+' form-control col-sm-3">'+optionName+'</div><select id="dropfields" name="fields[]" class="form-control mb-2 col-sm-5 float-left fields pos-'+len+'">'+options+'</select><a id="'+len+'" class="del mb-2 col-sm-3 btn btn-warning">Delete</a>';
            $("#append").append(box_html);
         });
    }*/

    /*function addCols(){
      $("#cip").hide();
      $("body").on('click','.add-field',function(){
        $("#cip").show();
        var len = $(".fields").length;
        var box_html = '<input name="fields[]" class="form-control mb-2 col-sm-8 float-left fields pos-'+len+'" /><a id="'+len+'" class="del mb-2 col-sm-4 btn btn-warning">Delete</a>';

        if(len < 5){
            $("#append").append(box_html);
        } else {
            alert('You only can create 5 inputs')
        }
      });
    }
    */

    function delCols(){
      $("body").on("click",".del",function(){
        var len = $(".fields").length;
        var pos = $(this).attr('id');
        $(".pos-"+pos).remove();
        $("#"+pos).remove();
      });
    }  

    function delDrop(){
      $("body").on("click",".deloption",function(){
        var len = $(".doption").length;
        var dpos = $(this).attr('id');
        $("."+dpos).remove();
        $("#"+dpos).remove();

        if(len == 1){
            $("#cip").hide();
        } 

      });
    }

</script>
@endsection
