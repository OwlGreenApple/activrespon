  @if(count($reminders)==0)
    <tr>
      <td colspan="5" class="text-center">
        No data to display
      </td>
    </tr>
  @endif
  @foreach($reminders as $reminder)
    <tr>
      <td class="text-center">H{{$reminder->days}}</td>
      <td class="text-center">{{$reminder->hour_time}}</td>
      <td>{{$reminder->message}}</td>
      <td class="text-center"><a class="icon icon-edit" data-id="{{$reminder->id}}" data-list_id="{{$reminder->list_id}}" data-campaign_id="{{$reminder->campaign_id}}" data-days="{{$reminder->days}}" data-event_time="{{$reminder->event_time}}" data-hour_time="{{$reminder->hour_time}}" data-message="{{$reminder->message}}" ></a></td>
      <td class="text-center"><a class="icon icon-delete" data-toggle="modal" data-target="#confirm-delete" data-id="{{$reminder->id}}" ></a></td>
    </tr>
  @endforeach