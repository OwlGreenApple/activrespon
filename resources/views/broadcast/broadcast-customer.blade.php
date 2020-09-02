@extends('layouts.app')

@section('content')
<!-- navbar -->
<div class="container mb-2">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a href="{{route('home')}}" class="nav-link">Back Home</a>
                </li>
                <li class="nav-item">
                     <a class="nav-link" href="{{route('broadcast')}}">Back Broadcast List</a>
                </li>
              </ul>
            </nav>
        </div>
    </div>
</div>
<!-- end navbar -->

<!-- Broadcast customer reminder -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"><h5><b>Broadcast Customer Reminder</b></h5></div>

                <div class="card-body table-responsive">
                    <table class="table table-striped" id="broadcast-customer">
                        <thead>
                            <th>List Name</th>
                            <th>List URL</th>
                            <th>Customer WA Number</th>
                            <th>Message</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Send Status</th>
                        </thead>
                        <tbody>
                            @if($data->count() > 0)
                            @foreach($data as $row)
                                <tr>
                                    <td>{{$row->label}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->wa_number}}</td>
                                    <td class="wraptext">
                                        <span class="get-text-{{$row->id}}">
                                            {{$row->message}}
                                        </span>
                                        <div><small>
                                            <a id="{{$row->id}}" class="display_popup">Read More</a>
                                        </small></div>
                                    </td>
                                    <td>{{$row->created_at}}</td>
                                    <td>{{$row->updated_at}}</td>
                                    <td>
                                        @if($row->status == 0)
                                            Pending
                                        @elseif($row->status == 1)
                                            <span class="text-warning">Queue</span>
                                        @elseif($row->status == 2)
                                            <span class="text-success">Sent</span> 
                                        @elseif($row->status == 5)
                                            <span class="text-danger">Failed</span>    
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- end card-body -->  
            </div>
        </div>
    </div>
<!-- end container -->   
</div>

<!-- Broadcast customer event -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"><h5><b>Broadcast Customer Event</b></h5></div>

                <div class="card-body table-responsive">
                    <table class="table table-striped" id="broadcast-event">
                        <thead>
                            <th>List Name</th>
                            <th>List URL</th>
                            <th>Customer WA Number</th>
                            <th>Message</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Send Status</th>
                        </thead>
                        <tbody>
                            @if($event->count() > 0)
                            @foreach($event as $rows)
                                <tr>
                                    <td>{{$rows->label}}</td>
                                    <td>{{$rows->name}}</td>
                                    <td>{{$rows->wa_number}}</td>
                                    <td class="wraptext">
                                        <span class="get-text-{{$rows->id}}">
                                            {{$rows->message}}
                                        </span>
                                        <div><small>
                                            <a id="{{$rows->id}}" class="display_popup">Read More</a>
                                        </small></div>
                                    </td>
                                    <td>{{$rows->created_at}}</td>
                                    <td>{{$rows->updated_at}}</td>
                                    <td>
                                        @if($rows->status == 0)
                                            Pending
                                        @elseif($rows->status == 1)
                                            <span class="text-warning">Queue</span>
                                        @elseif($rows->status == 2)
                                            <span class="text-success">Sent</span> 
                                        @elseif($rows->status == 5)
                                            <span class="text-danger">Failed</span>    
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- end card-body -->  
            </div>
        </div>
    </div>
<!-- end container -->   
</div>

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4>Broadcast Message</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p><!-- display message --></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<script type="text/javascript">
    $(document).ready(function(){
        getText();
        table();
    });

     function table(){
        $("#broadcast-customer, #broadcast-event").dataTable({
            'pageLength':10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
    }

    function getText(){
        $("body").on("click",".display_popup",function(){
            $("#myModal").modal();
            var id = $(this).attr('id');
            var txt = $(".get-text-"+id).text();
            $(".modal-body > p").text(txt);
        });
    }
</script>

@endsection
