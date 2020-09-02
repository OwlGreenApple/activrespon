<table id="list_appointment" class="display" style="width : 100%">
  <thead class="bg-dashboard">
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Date Appointment</th>
      <th class="text-center">H-</th>
      <th class="text-center">Name Contact</th>
      <th class="text-center">WA Contact</th>
      @if($active == 1)
        <th class="text-center">Edit</th>
        <th class="text-center">Delete</th>
      @else
        <th class="text-center">Status</th>
      @endif
    </tr>
  </thead>

  <tbody>
     @if($active == 1)
        @if($campaigns->count() > 0)
          @php $x =1 @endphp
          @foreach($campaigns as $row)
            <tr>
              <td class="text-center">{{ $x }}</td>
              <td class="text-center">{{ $row->event_time }}</td>
              <td class="text-center">H-{{ abs($row->days) }}</td>
              <td class="text-center">{{ $row->name }}</td>
              <td class="text-center">{{ $row->telegram_number }}</td>
              <td class="text-center">
                <a id="{{ $row->campaign_id }}" data-ev="{{ $row->event_time }}" data-name="{{ $row->name }}" data-phone="{{ $row->telegram_number }}" data-customer-id="{{ $row->id }}" class="icon-edit"></a>
              </td>
              <td class="text-center"><a id="{{ $row->rid }}" class="icon-cancel"></a></td> 
            </tr> 
            @php $x++ @endphp
          @endforeach
        @endif
    @else <!-- inactive -->
        @if($campaigns->count() > 0)
          @php $x =1 @endphp
          @foreach($campaigns as $row)
            <tr>
              <td class="text-center">{{ $x }}</td>
              <td class="text-center">{{ $row->event_time }}</td>
              <td class="text-center">H-{{ abs($row->days) }}</td>
              <td class="text-center">{{ $row->name }}</td>
              <td class="text-center">{{ $row->telegram_number }}</td>
              <td colspan="2" class="text-center">{!! message_status($row->status) !!}</td>
            </tr> 
            @php $x++ @endphp
          @endforeach
        @endif
    @endif
  </tbody>
</table>

<script type="text/javascript">
  $(document).ready(function(){
    tableData();
    @if($active == 0)
      addResendBtn('#list_appointment_length');
    @endif
    resendBtn();
     $('[data-toggle="popover"]').popover({
      trigger : 'click hover'
    });
  });
   
  function tableData()
  {
    $("#list_appointment").DataTable({
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
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
         
        if(result.success == 1)
        {
          $("#resend_popup").modal('hide');
          display_data();
        }
        else if(result.success == 0)
        {
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