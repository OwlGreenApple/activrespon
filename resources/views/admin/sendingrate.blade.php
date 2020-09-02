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
              @if(session('status'))
                  <div class="alert alert-success" role="alert">{{ session('status') }}</div>
              @endif 

              @if(session('error'))
                  <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
              @endif 

            <!-- Total deliver message per : -->
            <div class="col-md-12">
                <form class="form-inline" method="POST" action="{{url('savesettings')}}">
                  @csrf
                  <div class="col-sm-12 form-group"><label>Total deliver message per :</label></div>
                  <div class="form-group mb-2">
                    <select name="total_sending_start" class="form-control">
                      @for($x=1;$x<=10;$x++)
                        @if($x == $data['sending_start'])
                          <option value="{{$x}}" selected>{{$x}}</option>
                        @else
                          <option value="{{$x}}">{{$x}}</option>
                        @endif
                      @endfor
                    </select>
                  </div>
                  <div class="form-group mx-sm-3 mb-2">
                    <select name="total_sending_end" class="form-control">
                      @for($x=1;$x<=10;$x++)
                        @if($x == $data['sending_end'])
                          <option value="{{$x}}" selected>{{$x}}</option>
                        @else
                          <option value="{{$x}}">{{$x}}</option>
                        @endif
                      @endfor
                    </select>
                  </div>

                  <!-- Delay message per : -->
                  <div class="col-sm-12 form-group"><label>Delay send message per :</label></div>
                  <div class="form-group mb-2">
                    <select name="delay_sending_start" class="form-control">
                      @for($x=1;$x<=10;$x++)
                         @if($x == $data['delay_start'])
                          <option value="{{$x}}" selected="">{{$x}}</option>
                         @else
                          <option value="{{$x}}">{{$x}}</option>
                         @endif
                      @endfor
                    </select>
                  </div>
                  <div class="form-group mx-sm-3 mb-2">
                    <select name="delay_sending_end" class="form-control">
                      @for($x=1;$x<=10;$x++)
                         @if($x == $data['delay_end'])
                          <option value="{{$x}}" selected>{{$x}}</option>
                         @else
                          <option value="{{$x}}">{{$x}}</option>
                         @endif
                      @endfor
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary mb-2">Set Settings</button>
                </form>
            </div>
           
        </div>
    </div>
<!-- end container -->
</div>  

@endsection