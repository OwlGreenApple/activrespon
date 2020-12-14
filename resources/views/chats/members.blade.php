@if(count($chats) > 0)
 @foreach($chats AS $key=>$row)
    @php $random = rand(1,12); @endphp
    <div id="{{ $row['id'] }}" class="col-md-12 mb-2 chat_room_box">
     <div class="row chat-name">
        <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
          <img class="rounded-circle chat-image" alt="100x100" src="{{ asset('assets/wachat/avatar') }}{{$random}}.png" data-holder-rendered="true"/>
        </div>

        <div class="col-lg-10 col-md-10 col-sm-10 col-10 pr-0">
          <div><span class="chat-user">{{$row['name']}}</span> <span @if($row["notif"] > 0)style="display: block;"@endif class="chat-note-{{ $row['id'] }} float-right chat-notification">{{$row['notif']}}</span>
            <div class="clearfix"></div>
          </div>
          <div class="chat-text-user"><!-- Available --></div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
          <div class="chat-time"></div>
        </div>
        <!-- -->
      </div>
    </div>
  @endforeach
@else
  {{$error}}
@endif