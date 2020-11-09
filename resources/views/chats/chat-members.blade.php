@if(count($members) > 0)
  @foreach($members as $row)
    <div class="row chat-name">
      <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
        <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
      </div>

      <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
        <div id="{{ $row['invited_id'] }}" class="chat-user">{{ $row['name'] }}</div>
        <div class="chat-text-user">Available</div>
      </div>

      <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
        <div class="chat-time">09:00</div>
      </div>
      <!-- -->
    </div>
  @endforeach
@endif 