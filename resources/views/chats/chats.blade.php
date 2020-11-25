@if($error == null)
  @if(count($messages) > 0)
    @foreach($messages as $row)
        @if($row['key'] == 'reply')
          <div class="col-md-6"><div class="alert alert-primary chat-text">
            @if($row['val']['media_url'] !== null)
              <div><img class="image_preview" src="{{ $image }}{{ $row['val']['media_url'] }}" /></div>
              <div>{{ $row['val']['message'] }}</div>
            @else
                {{ $row['val']['message'] }}
            @endif
          </div></div>
        @endif
        
        @if($row['key'] =='sender')
          <div class="col-md-6 ml-auto text-right"><div class="alert alert-success chat-text">@if($row['val']['media_url'] !== null)
              <div> {{ wa_media_diference($row['val']['media_url']) }}

                <img class="image_preview" src="{{ $image }}{{ $row['val']['media_url'] }}" />
              </div>
              <div>{{ $row['val']['message'] }}</div>
          @else
              {{ $row['val']['message'] }}
          @endif
          </div></div>
        @endif
    @endforeach
  @endif

@else
  <div class="col-md-12 alert alert-warning">{{ $error }}</div>
@endif
