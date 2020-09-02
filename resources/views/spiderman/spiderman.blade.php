@extends('layouts.admin')

@section('content')
    <div class="container">
      <div class="col-lg-6">
        <form id="confirmationForm">
           <div class="form-group">
               <label for="name">Phone Number:</label>
               <input type="text" class="form-control" name="phone"/>
           </div> 
           <div class="form-group">
               <label for="name">Message:</label>
               <input type="text" class="form-control" name="message"/>
           </div> 
           <div class="form-group">
               <label for="name">Select Phone:</label>
               <select name="port" class="form-control">
                 <option value="3000">6287775000283</option>
                 <option value="3001">62817318368</option>
                <!--  <option value="3007">6285967284581</option> -->
               </select>
           </div>
           <button class="btn btn-primary">Send</button>
        </form>
      </div>

      <div class="col-lg-6 mt-2">
        <button type="button" id="get-status" class="btn btn-info">Get Status</button>
      </div>
      <div class="col-lg-6 mt-2">
        <button type="button" id="start" class="btn btn-warning">Start</button>
      </div>  
      <div class="col-lg-6 mt-2">
        <button type="button" id="scan" class="btn btn-success">Scan</button>
      </div> 
      <div class="col-lg-6 mt-2">
        <div id="qr-code"></div>
      </div>
    </div>

    <script type="text/javascript">

      $(document).ready(function(){
        sendmessage();
        getStatus();
        startSpiderman();
        scanSpiderman();
      });

      /* Ajax for confirmation */
       function sendmessage(){
          $("body").on("submit","#confirmationForm",function(e){
            e.preventDefault();
            var data = $(this).serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type : "POST",
                url: "{{ url('sendmessage') }}",
                data: data,
                timeout: 600000,
                success: function(result){
                   alert(result.sent);
                },
                error : function(xhr){
                  console.log(xhr.responseText);
                  alert('server error');
                }
            });

           });
        };


        function getStatus(){
          $("body").on("click","#get-status",function(){
            var port = $("select[name='port'] > option:selected").val();
            $.ajax({
                type : "GET",
                url: "{{ url('statusmessage') }}",
                data : {port : port},
                timeout: 600000,
                dataType : 'json',
                success: function(result){
                   alert("run : "+result.running+"\n"+"connected : "+result.connected+"\n"+"phone : "+result.phone_number);
                },
                error : function(xhr){
                  console.log(xhr.responseText);
                  alert('server not ready');
                }
            });

           });
        };

        function startSpiderman()
        {
          $("body").on("click","#start",function(){
            var port = $("select[name='port'] > option:selected").val();
            $.ajax({
                type : "GET",
                url: "{{ url('start') }}",
                data : {port : port},
                timeout: 600000,
                dataType : 'json',
                success: function(result){
                   alert(result.detail);
                },
                error : function(xhr){
                  console.log(xhr.responseText);
                  alert('server not ready');
                }
            });

           });
        };

        function scanSpiderman()
        {
          $("body").on("click","#scan",function(){
            var port = $("select[name='port'] > option:selected").val();
            $.ajax({
                type : "GET",
                url: "{{ url('scan') }}",
                data : {port : port},
                dataType : 'html',
                timeout: 600000,
                success: function(result){
                   $('#qr-code').html(result);
                },
                error : function(xhr){
                  console.log(xhr.responseText);
                  alert('server not ready');
                }
            });

           });
        };

    </script>      
@endsection