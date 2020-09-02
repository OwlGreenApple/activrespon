<?php 
use App\User;
?>
@foreach($phone_numbers as $phone_number)
  <tr>
    <td data-label="Email">
     {{$phone_number->email}}
    </td>
    <td data-label="Username">
      {{$phone_number->phone_number}}
    </td>
    <td data-label="Counter">
      {{$phone_number->counter}}
    </td>
    <td data-label="Created">
      {{$phone_number->created_at}}
    </td>
    <td data-label="Key Woowa">
      {{$phone_number->filename}}
    </td>
    <td>
      <?php if ($phone_number->mode==1){ ?>
        <a href="{{url('take-screenshoot').'/'.$phone_number->phone_number}}" target="_blank">Woowa</a>
      <?php } else if ($phone_number->mode==0){ ?>
        Spiderman. Status : 
      <?php } 
      if ($phone_number->status==1){
        echo "Disconnected";
      }
      if ($phone_number->status==2){
        echo "Connected";
      }
      ?>
      
    </td>
    <td>
      @if($phone_number->mode==0)
        <a id="btn-restart-{{ $phone_number->id }}" data-phone-id="{{ $phone_number->id }}" data-url="{{ $phone_number->url }}" data-folder="{{ $phone_number->label }}" class="btn btn-warning btn-sm server-restart">Restart</button>
      @endif
    </td>
  </tr>
@endforeach