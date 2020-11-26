@if($error == null)
  @if(count($messages) > 0)
    @foreach($messages as $row)
        @if($row['key'] == 'reply')
          <div class="col-md-6"><div class="alert alert-primary chat-text">
            @if($row['val']['type'] == 'image')
              <div>
                <img class="image_preview" src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/image" />
              </div>
              <div class="chat-caption">{{ $row['val']['message'] }}</div>
            @elseif($row['val']['type'] == 'video')
                <video autoplay="" controls>
                  <source src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/video" type="video/mp4">
                Your browser does not support the video tag.
                </video>
            @elseif($row['val']['type'] == 'audio')
                <audio controls>
                  <source src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/audio" type="audio/ogg">
                Your browser does not support the audio element.
                </audio>
            @elseif($row['val']['type'] == 'text')
                {{ $row['val']['message'] }}
            @else
                Sorry, media message we only support : mp4(video), ogg(audio)
            @endif
          </div></div>
        @endif

        <!-- -->
        
        @if($row['key'] =='sender')
          <div class="col-md-6 ml-auto text-right"><div class="alert alert-success chat-text">@if($row['val']['type'] == 'image')
              <div>
                <img class="image_preview" src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/image" />
              </div>
              <div class="chat-caption">{{ $row['val']['message'] }}</div>
          @elseif($row['val']['type'] == 'video')
              <video autoplay="" controls>
                <source src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/video" type="video/mp4">
              Your browser does not support the video tag.
              </video>
          @elseif($row['val']['type'] == 'audio')
              <audio controls>
                <source src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/audio" type="audio/ogg">
              Your browser does not support the audio element.
              </audio>
          @elseif($row['val']['type'] == 'text')
              {{ $row['val']['message'] }}
          @else
              Sorry, media message we only support : mp4(video), ogg(audio)
          @endif
          </div></div>
        @endif
    @endforeach
  @endif

@else
  <div class="col-md-12 alert alert-warning">{{ $error }}</div>
@endif
