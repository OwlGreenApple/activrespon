  @if(count($events) > 0)
    <div id="notification_events"><!-- notification --></div>
    @foreach($events as $event)
      <div class="actcampaign_form">

        <div class="col-lg-12 pad-fix">
            <div class="board">
              <div class="left">Reminder Day : H{{$event->days}}</div>
              <div class="right">
                @if($total_message > 0)
                  <a class="icon icon-edit" data-id="{{$event->id}}" data-list_id="{{$event->list_id}}" data-campaign_id="{{$event->campaign_id}}" data-days="{{$event->days}}" data-event_time="{{$event->event_time}}" data-hour_time="{{$event->hour_time}}" data-message="{{$event->message}}" class="icon icon-edit"></a>

                 <a class="icon icon-delete" data-toggle="modal" data-target="#confirm-delete" data-id="{{$event->id}}"></a>
                @endif

                <a class="icon icon-carret-down-circle" id="{{ $event->id }}"></a>
              </div>
              <div class="clearfix"></div>
            </div>

            <div class="board-{{ $event->id }} slide_message">
                {{$event->message}}
            </div>
        </div>

      </div>
    @endforeach
  @endif