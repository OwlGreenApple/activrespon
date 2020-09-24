@foreach($messages as $message)
  <tr>
    <td data-label="">
      {{$message->phone_number}}
    </td>
    <td data-label="">
      {{$message->message}}
    </td>
    <td data-label="">
      <?php 
        if ($message->status==1) {
          echo "Sent";
        }
        else if ($message->status==2) {
          echo "Phone Offline";
        }
        else if ($message->status==3) {
          echo "No WA not registered / error";
        }
        else {
          echo "Processed";
        }
      ?>
    </td>
    <td data-label="Created">
      {{$message->created_at}}
    </td>
    <td data-label="Action">
      <button type="button" class="btn btn-primary btn-resend" data-toggle="modal" data-target="#modal-confirm-delete" data-id="{{$message->id}}">
        Resend
      </button>
    </td>
  </tr>
@endforeach