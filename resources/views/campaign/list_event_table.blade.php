 <!-- EVENT -->

 <table id="event_list" class="display w-100">
  <thead class="bg-dashboard">
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Date Event</th>
      <th class="text-center">H</th>
      @if($active == 0)
        <th class="text-center">Date</th>
      @endif
        <th class="text-center">Name Contact</th>
        <th class="text-center">WA Contact</th>
      @if($active == 1)
        <th class="text-center">Delete</th>
      @else
        <th class="text-center">Status</th>
      @endif
    </tr>
  </thead>

  <tbody>
    @if($campaigns->count() > 0)
      @php $x = 1 @endphp
      @if($active == 1)
        @foreach($campaigns as $row)
          <tr>
            <td class="text-center">{{ $x }}</td>
            <td class="text-center">{{ $row->event_time }}</td>
            <td class="text-center">H{{ $row->days }}</td>
            <td class="text-center">{{ $row->name }}</td>
            <td class="text-center">{{ $row->telegram_number }}</td>
            <td class="text-center"><a id="{{ $row->rcid }}" class="icon-cancel"></a></td> 
          </tr> 
         @php $x++ @endphp
        @endforeach
      @else
        @foreach($campaigns as $row)
          <tr>
            <td data="{{ $row->rcid }}" class="text-center">{{ $x }}</td>
            <td class="text-center">{{ $row->event_time }}</td>
            <td class="text-center">H{{ $row->days }}</td>
            <td class="text-center">{{ Date('M d Y h:i:s A',strtotime($row->updated_at)) }}</td>
            <td class="text-center">{{ $row->name }}</td>
            <td class="text-center">{{ $row->telegram_number }}</td>
            <td colspan="2" class="text-center"> {!! message_status($row->status) !!}</td>
          </tr> 
         @php $x++ @endphp
        @endforeach
      @endif
    @endif
  </tbody>
</table>

@if($campaigns->count() > 0)
<!-- Modal resend -->
<div class="modal fade" id="resend_popup" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="message_resend"></div>
      <div class="modal-header">
        <h5>Are you sure to reset broadcast message?</h5>
      </div>
      <div class="modal-body">
         <button id="resend_message" class="btn btn-primary">Resend</button>
         <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
      
  </div>
</div>
<!-- End Modal -->
@endif

<script type="text/javascript">
  $(document).ready(function(){
    table_event();
    @if($active == 0)
      addResendBtn('#event_list_length');
    @endif
    resendBtn();
    $('[data-toggle="popover"]').popover({
      trigger : 'click hover'
    });
  });

  function table_event()
  {
    $("#event_list").DataTable({
      "lengthMenu": [ 10, 25, 50, 75, 100, 250, 500 ],
    });
  } 

  function addResendBtn(elem)
  {
    var message = "You can resend message if status are : 'phone offline or queued'";
    var tooltip='<span style="font-size : 18px" data-toggle="popover" data-content="'+message+'"><i class="fa fa-question-circle"></i></span>';

    $(elem).append("<label class='ml-2'><button id='resend' class='btn btn-info text-white btn-sm'>Resend</button></label><label class='ml-1'>"+tooltip+"</label>");
  }

  function resendBtn()
  {
    $("body").on('click','#resend',function(){
      $("#resend_popup").modal();
    });
 
    $("body").on('click','#resend_message',function(){
      resend();
    });
  }

  function resend()
  {
    $.ajax({
      type : 'GET',
      url : '{{url("resend_campaign")}}',
      data : {campaign_id : "{{ $campaign_id }}"},
      dataType : "json",
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result)
      {
         if(result.success == 1)
         {
           $("#resend_popup").modal('hide');
           data_event();
         }
         else if(result.success == 0)
         {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            $("#resend_popup").modal('hide');
            $('.message_resend').html('<div class="alert alert-danger">Sorry, currently our server is too busy, please try again later.</div>')
         }
      },
      error: function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    }); 
  }

</script>