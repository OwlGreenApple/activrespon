@extends('layouts.app')

@section('content')

<!-- navbar -->
<div class="container mb-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('home')}}">Broadcast</a>
                </li>
              </ul>
            </nav>
        </div>
    </div>
</div>
<!- end navbar -->

<!-- -->
<div class="container mb-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Scan Your Device Here</b></div>
                <div class="col-md-12 mt-2"><h5>Please scan within approximately <span class="countdown font-weight-bold">30</span> seconds until page reload</h5></div>
                <div class="card-body">
                    @if (session('deviceid'))
                        {!! $qrcode->getScanBarcodeAuthorize(session('deviceid')) !!}
                    @endif
                </div>

            </div>
        </div>
    </div>
<!-- end container -->
</div>

<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){getProfileDevice('{{session("deviceid")}}')},30000);
        countDownTimer();
    });

    function countDownTimer()
    {
        var sec = $(".countdown").text();
        sec = parseInt(sec);
        var timer = setInterval(function(){
              sec = sec - 1
              $(".countdown").html(sec);

              if(sec == 0)
              {
                clearInterval(timer);
              }
        },
            1000
        );
    }

    function getProfileDevice(id)
    {
         var checkid = id.length;
         if(checkid > 0)
         {
            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
             });
             $.ajax({
                type : 'POST',
                url : '{{route("updatenumber")}}',
                data : {'deviceid':id},
                dataType : 'json',
                success : function(result)
                {
                    if(result.status == true)
                    {
                        /* this is true (status changed) */
                        location.href='{{route("devices")}}';
                    }
                    else
                    {
                        /* this is false (status won't changed) */
                        alert('Please check your device, if there is trouble please reauthorize');
                        location.href='{{route("devices")}}';
                    }
                }
            });
         }
         else
         {
            return false;
         }
        
    }
</script>
@endsection
