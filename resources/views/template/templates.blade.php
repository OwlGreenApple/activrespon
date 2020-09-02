@extends('layouts.app')

@section('content')

<div class="container">
    <!-- add list-->
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card">
                <div class="card-header">
                    <b>Create Templates</b>
                </div>

                <div class="card-body">
                    <!-- Create template -->
                     <div class="mb-2 text-right">
                        <button data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm">Create Template</button>
                    </div>

                    <div class="table-responsive">
                    <table class="table table-striped" id="datatab">
                        <thead>
                            <th>Name</th>
                            <th>Message</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @if($data->count() > 0)
                            @foreach($data as $row)
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td class="wraptext">
                                        <span>{{$row->message}}</span>
                                        <div><small><a class="message-text" id="{{$row->id}}">Read | Edit</a></small></div>
                                    </td>
                                    <td>{{$row->created_at}}</td>
                                    <td>{{$row->updated_at}}</td>
                                    <td>
                                        <button id="{{$row->id}}" class="btn btn-danger btn-sm del">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr><td class="text-center" colspan="5">No Data</td></tr>
                            @endif
                        </tbody>
                    </table>
                     </div>
                     <!-- end table -->

                </div>
            </div>
        </div>
    </div>
<!-- end container -->   
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Template</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
         <form id="create_template">
            <div class="form-group">
                <label class="col-form-label text-md-right"><b>Name</b></label>
                <input type="text" class="form-control" name="template_name"/>
                <span class="error template_name"></span>
            </div> 
            <div class="form-group">
                <label class="col-form-label text-md-right"><b>Message</b></label>
                 <div class="mb-2"><input class="btn btn-default btn-sm" type="button" id="tag_name" value="Add Name" /></div>  
                <textarea id="divInput-template" class="form-control" name="message"></textarea>
                <span class="error message"></span>
            </div> 
            <button type="submit" class="btn btn-default">Create Template</button>
         </form>
      </div>
    </div>

  </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><h4>Edit Template</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
         <form id="edit_template">
            <div class="form-group">
                <label class="col-form-label text-md-right"><b>Name</b></label>
                <input type="text" class="form-control" name="edit_template_name"/>
                <span class="error edit_template_name"></span>
            </div> 
            <div class="form-group">
                <label class="col-form-label text-md-right"><b>Message</b></label>
                 <div class="mb-2"><input class="btn btn-default btn-sm" type="button" id="tagname" value="Add Name" /></div>  
                <textarea id="divInput-edit-template" class="form-control" name="edit_message"></textarea>
                <span class="error edit_message"></span>
            </div> 
            <input type="hidden" name="id" />
            <button type="submit" class="btn btn-default">Edit Template</button>
         </form>
      </div>
    </div>

  </div>
</div>


<!-- give emoji -->
 <script type="text/javascript">
    $("#divInput-description-post, #divInput-template, #divInput-edit-template").emojioneArea({
        pickerPosition: "right",
        mainPathFolder : "{{url('')}}",
    });

    $(function(){
        $('#tag_name').on('click', function(){
            var tag = '{name}';
            var cursorPos = $('#divInput-template').prop('selectionStart');
            var v = $('#divInput-template').val();
            var textBefore = v.substring(0,  cursorPos );
            var textAfter  = v.substring( cursorPos, v.length );
            $('#divInput-template').emojioneArea()[0].emojioneArea.setText(textBefore+ tag +textAfter );
        });
     });
</script>

<script src="{{ asset('/assets/js/caret.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        createTemplate();
        editTemplate();
        updateTemplate();
        delTemplate();
        table();
    });

    function table(){
        $("#datatab").dataTable({
            'pageLength':10,
        });
    }

    function createTemplate(){
        $("#create_template").submit(function(e){
            e.preventDefault();
            var data = $(this).serialize();
            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                type : "POST",
                url : "{{ route('addtemplate') }}",
                data : data,
                success : function(result){
                    if(result.success == true){
                        alert(result.message);
                        location.href="{{route('templates')}}";
                        //$('input').val('');
                        //$("#divInput-broadcast").emojioneArea()[0].emojioneArea.setText('');
                    } else {
                        $(".template_name").html(result.template_name);
                        $(".message").html(result.message);
                    }
                }
            })/*end ajax*/;
        });
    }

    /* Display all data to edit */
    function editTemplate(){
        $(".message-text").click(function(){
            displayPopup();
            var id = $(this).attr('id');
            $.ajax({
                type : "GET",
                url : "{{route('displaytemplate')}}",
                data : {'id':id, 'editor':true},
                dataType : "json",
                success : function(result){
                    $("input[name='id']").val(id);
                    $("input[name='edit_template_name']").val(result.name);
                    $("#divInput-edit-template").emojioneArea()[0].emojioneArea.setText(result.message);
                }
            });
        });
    }

    function displayPopup(){
        $("#editModal").modal();
    }

    function updateTemplate(){
        $("#edit_template").submit(function(e){
            e.preventDefault();
            var data = $(this).serialize();
            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                type : "POST",
                url : "{{route('updatetemplate')}}",
                data : data,
                dataType : "json",
                success : function(result){
                    if(result.success == true){
                        alert(result.status);
                        location.href="{{route('templates')}}";
                    } else {
                        $(".edit_template_name").html(result.edit_template_name);
                        $(".edit_message").html(result.edit_message);
                    }
                   
                }
            });
        });
    }

    function delTemplate(){
        $(".del").click(function(){
            var conf = confirm('Are you sure to delete this template?');
            var id = $(this).attr('id');

            if(conf == true){
                 $.ajax({
                    type : "GET",
                    url : "{{route('deletetemplate')}}",
                    data : {'id':id},
                    dataType : "json",
                    success : function(result){
                        alert(result.status);
                        location.href="{{route('templates')}}";
                    }
                });
            } else {
                return false;
            }
        });
    }

</script>

@endsection
