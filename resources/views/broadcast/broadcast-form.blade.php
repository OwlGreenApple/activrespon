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
                    <a href="{{route('broadcast')}}" class="nav-link">See Broadcast List</a>
                </li>
              </ul>
            </nav>
        </div>
    </div>
</div>
<!-- end navbar -->

<div class="container">
    <!-- add list-->
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header"><b>Create BroadCast Reminder</b></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                         </div>   
                    @endif 

                    @if (session('status_warning'))
                        <div class="alert alert-warning" role="alert">
                            {{ session('status_warning') }}
                         </div>   
                    @endif 

                    @if (session('status_error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('status_error') }}
                         </div>   
                    @endif

                    <form id="get_template">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Choose Template</label>
                            <div class="col-md-6">
                                <select class="form-control" name="template_list" id="display-template">
                                    <option>Choose</option>
                                    @if($templates->count() > 0)
                                        @foreach($templates as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm">Add</button>
                            </div>
                        </div> 
                    </form>

                    <!-- Registration Form -->
                     <form method="POST" action="{{ route('createbroadcast') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Lists Option</label>
                            <div class="col-md-6">
                                @foreach($data as $row)
                                <div class="form-check">
                                  <input class="form-check-input" name="id[]" type="checkbox" value="{{$row->id}}">
                                  <label class="form-check-label" for="{{$row->id}}">
                                    {{$row->label}}
                                  </label>
                                </div>
                                 @endforeach
                                <!-- end check box -->
                                @if (session('error'))
                                    <div class="error" role="alert">
                                        {{ session('error')->first('id') }}
                                     </div>   
                                @endif 
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Message</label>
                            <div class="col-md-6">
                                <textarea id="divInput-description-post" class="form-control" name="message"></textarea>
                                 @if (session('error'))
                                    <div class="error" role="alert">
                                        {{ session('error')->first('message') }}
                                     </div>   
                                 @endif 
                            </div>
                            <div class="col-md-2"><input class="btn btn-default btn-sm" type="button" id="tagname" value="Add Name" /></div>  
                        </div> 

                         <input type="hidden" name="is_event" value="{{encrypt(0)}}"/>
                        <!-- submit button -->
                         <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Create Broadcast Reminder
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

<!-- give emoji -->
 <script type="text/javascript">
    $("#divInput-description-post").emojioneArea({
        pickerPosition: "right",
        mainPathFolder : "{{url('')}}",
    });

    $(function(){
        $('#tagname').on('click', function(){
            var tag = '{name}';
            var cursorPos = $('#divInput-description-post').prop('selectionStart');
            var v = $('#divInput-description-post').val();
            var textBefore = v.substring(0,  cursorPos );
            var textAfter  = v.substring( cursorPos, v.length );
            $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(textBefore+ tag +textAfter );
        });
     });

</script>

<script type="text/javascript">
    $(document).ready(function(){
        displayTemplate();
    });

    /* Attach broadcast template into textarea message */
    function displayTemplate(){
        $("body").on('submit','#get_template',function(e){
            e.preventDefault();
            var id = document.getElementsByName("template_list")[0].value;
            $.ajax({
                type : 'GET',
                url : '{{route("displaytemplate")}}',
                data : {'id':id},
                dataType : "text",
                success : function(txt){
                    $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(txt);
                }
            });
        });
    }
</script>

@endsection
