@extends('layouts.admin')

@section('content')

<!-- navbar -->
<div class="container mb-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('home')}}">Home</a>
                </li> 
                <li class="nav-item">
                  <a class="nav-link" href="{{url('setupconfig')}}">Setup Config</a>
                </li>
              </ul>
            </nav>
        </div>
    </div>
</div>
<!- end navbar -->

<div class="container mb-2">
    <!-- Profile List -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="table-responsive">
                    <h4>List of User</h4>
                    <hr/>
                    <table class="table table-striped table-responsive" id="user-list">
                        <thead>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @php 
                                $no =1;
                            @endphp

                            @if($data->count() > 0)
                            @foreach($data as $row)
                                <tr>
                                    <td>{{$no}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->email}}</td>
                                    <td>
                                        <a href="{{url('loginuser/'.$row->id.'')}}" class="btn btn-info btn-sm">Login</a>
                                    </td>
                                </tr>
                                @php 
                                    $no++;
                                @endphp
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>

        </div>
    </div>
<!-- end container -->
</div>  

<script type="text/javascript">
    $(document).ready(function(){
        table();
    });

     function table(){
        $("#user-list").dataTable({
            'pageLength':10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
    }
</script>

@endsection