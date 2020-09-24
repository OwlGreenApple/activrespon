@foreach($messages as $message)
  <tr>
    <td data-label="Username">
      {{$message->phone_number}}
    </td>
    <td data-label="Membership">
      {{$message->message}}
    </td>
    <td data-label="Created">
      {{$message->created_at}}
    </td>
    <td data-label="Membership">
      {{$message->status}}
    </td>
    <td data-label="Action">
      <button type="button" class="btn btn-primary btn-resend" >
        Resend
      </button>
    </td>
  </tr>
@endforeach