@if(count($messages) > 0)
  @foreach($messages as $row)
      @if($row['key'] == 'reply')
        <div class="col-md-6"><div class="alert alert-primary chat-text"></div></div>
      @endif
      
      @if($row['key'] =='sender')
        <div class="col-md-6 ml-auto text-right"><div class="alert alert-success chat-text">@if($row['val']['media_url'] !== null)
            <div><img class="image_preview" src="http://188.166.221.181/wamate-api/public{{ $row['val']['media_url'] }}"></div>
            <div>{{ $row['val']['message'] }}</div>
        @else
            {{ $row['val']['message'] }}
        @endif
        </div></div>
      @endif
  @endforeach
@endif
