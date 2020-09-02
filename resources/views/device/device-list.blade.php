@extends('layouts.app')

@section('content')

<!-- Modal Authorize -->
  <div class="modal fade child-modal" id="openAuthorize" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Authorize Your Phone</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div id="authorizescan"></div>
        </div>

        <div class="modal-body">
            <div id="authorizecomplete"></div>
        </div>
      </div>
      
    </div>
  </div>

<!-- Modal Change Number -->
  <div class="modal fade child-modal" id="openChange" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Scan To Change Your Number</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div id="changenumberscan"></div>
        </div>

        <div class="modal-body">
            <div id="changenumbercomplete"></div>
        </div>

        <div class="col-md-12">
          <div>If you want to change your number, please logout from whatsapp web on your device.</div>
          <div>After scan wait approximately <b>1-3 minutes</b> then refresh your browser </div> 
        </div>

      </div>
      
    </div>
  </div>

<!-- -->
<div class="container mb-2">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header"><b>My Devices</b></div>

                <div class="card-body">
                    <h6 class="alert alert-info">If your number doesn't change after scan qr code on <b>Change Number</b> please logout from your whatssapp web and then rescan</h5>
                    <table class="table table-striped table-responsive" id="user-list">
                        <thead>
                            <th>No</th>
                            <th>Device Name</th>
                            <th>WA Number</th>
                            <th>Status</th>
                            <th>Authorize</th>
                            <th>Change Number</th>
                            <!--<th>Delete Device</th>-->
                        </thead>
                        <tbody>
                            @if(count($data) > 0)
                                @php $no = 1; @endphp
                                @foreach($data['sender'] as $row)
                                 <tr>
                                    <td>{{$no}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->wa_number}}</td>
                                    <td id="devicestatus">{{$data[$row->id]['status']}}</td>
                                    <td><a id="{{$row->device_id}}" class="btn btn-info btn-sm authorize">Authorize</a></td>
                                    <td><a id="{{$row->device_id}}" class="btn btn-warning btn-sm changenumber">Change Number</a></td>
                                    <!--<td><a id="row->device_id" class="btn btn-danger btn-sm deletedevice">Delete Device</a></td>-->
                                  </tr>
                                @php $no++; @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
<!-- end container -->
</div>


<script type="text/javascript">
    $(document).ready(function(){
        getScanBarcodeAuthorize();
        getScanBarcodeChangeNumber();
    });

    function getScanBarcodeAuthorize()
    {
        $(".authorize").click(function(){
            var device_id = $(this).attr('id');
            $("#openAuthorize").modal();
            var svg = false;

            $.ajax({
                beforeSend: function() {
                    $("#authorizescan").text('Loading....');
                },
                type : 'GET',
                url : '{{url("authorize")}}/'+device_id,
                dataType : 'html',
                success : function(result,textStatus,jqXHR)
                {
                    var response = jqXHR.responseText;
                    if(response.indexOf('svg') > 0)
                    {
                        $("#authorizescan").html(result);
                        svg = true;
                    }
                    else
                    {
                        var obj =jQuery.parseJSON(response);
                        $("#authorizescan").text('Status : '+obj.status+', '+obj.message);
                    }
                },
                complete: function(xhr, textStatus) {
                    //console.log(xhr.status);
                    if(xhr.status == 200 && svg == true)
                    {
                        $("#authorizecomplete").text('Please wait until it reload');
                        setTimeout(function(){checkAuthorize(device_id)},20000);
                    }
                }, 
                error : function(jqXHR,textStatus,error)
                {
                    console.log(error);
                }
            })

        });

    }

    function checkAuthorize(id)
    {
        var current_status = $("#devicestatus").text();
         $.ajax({
            type : 'GET',
            url : '{{url("devicestatus")}}/'+id,
            dataType : 'json',
            success : function(result)
            {
                if(result.status !== current_status)
                {
                    /* this is true (status changed) */
                    location.href='{{route("devices")}}';
                }
                else
                {
                    /* this is false (status won't changed) */
                    //setInterval(function(){alert('aaaa')},3000)
                    alert('Sorry, something wrong with system, please reload your browser and authorize again')
                }
            }
        })
    }

    function getScanBarcodeChangeNumber()
    {
         $(".changenumber").click(function(){
            var device_id = $(this).attr('id');
            var svg = false;
            $("#openChange").modal();

            /*checkCurrentNumber(device_id,
                function(current_number){ put ajax here...  }
            );*/
                    
            $.ajax({
                beforeSend: function() {
                    $("#changenumberscan").text('Loading....');
                },
                type : 'GET',
                url : '{{route("scan")}}',
                data : {'id':device_id},
                dataType : 'html',
                success : function(result,textStatus,jqXHR)
                {
                    var response = jqXHR.responseText;
                    if(response.indexOf('svg') > 0)
                    {
                        $("#changenumberscan").html(result);
                        svg = true;
                    }
                    else if(response.indexOf('message') > 0)
                    {
                        var obj =jQuery.parseJSON(result);
                        $("#changenumberscan").text('Status : '+obj.status+', '+obj.message);
                    }
                    else
                    {
                        $("#changenumberscan").text(result);
                    }  
                },
                complete: function(xhr, textStatus) {
                    //console.log(xhr.status);
                    if(xhr.status == 200 && svg == true)
                    {
                        $("#changenumbercomplete").text('Please wait 30 seconds until page reload');
                        setTimeout(function(){updateNumber(device_id)},30000);
                    }
                }, 
                error : function(jqXHR,textStatus,error)
                {
                    console.log(error);
                }
            })

              
        });
    }

     function updateNumber(id,current_number)
    {
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            type : 'POST',
            url : '{{route("updatenumber")}}',
            dataType : 'json',
            data : {deviceid : id},
            success : function(response){
               if(response.status == true)
               {
                    alert("Your phone number has been updated");
               }
               else
               {
                    alert("Your phone number failed to updated, please wait 1-2 minutes then try to change again");
               }
               location.href='{{route("devices")}}';
            }
        })
    }

    /* currently 
    function checkCurrentNumber(id,callback)
    {
        var phone;
        $.ajax({
            type : 'GET',
            url : '{{url("devicedetail")}}/'+id,
            dataType : 'json',
            success : function(data){
                callback(data.phone);
            }
        })
    } 
    */
</script>

@endsection
