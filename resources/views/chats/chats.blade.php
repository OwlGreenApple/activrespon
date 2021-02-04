@if($error == null)
  <!-- reply -->
  @if(count($messages) > 0)
    @foreach($messages as $row)
        @if($row['key'] == 'sender')
        <div class="col-md-6">
            <div class="alert alert-light chat-text">
              <div>
                @if($row['val']['type'] == 'image')
                  @if($row['val']['media_url'] !== null)
                    <div>
                      <img class="image_preview" src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/image" />
                    </div>
                    <div class="chat-caption">{{ $row['val']['message'] }}</div>
                  @else
                    Sorry, image not available.
                  @endif
                @elseif($row['val']['type'] == 'text')
                  {!! str_replace("\n","<br/>",$row['val']['message']); !!}
                @else 
                    Sorry, currently we do not support media message except image
                @endif
              </div>
              <!-- time -->
              <div align="right"><small>{{ $row['val']['time'] }} 
                @if($row['val']['status'] == 'READ')
                  <span class="text-primary"><i class="fas fa-check-double"></i></span>
                @elseif($row['val']['status'] == 'DELIVERED')
                  <span><i class="fas fa-check-double"></i></span>
                @else
                  <span><i class="fas fa-check"></i></span>
                @endif
                </small></div>
          </div>
        </div>
        @endif

        <!-- sender -->
        
        @if($row['key'] =='reply')
        <div class="col-md-6 ml-auto text-right">
            <div class="alert alert-success chat-text text-justify">
              <div>
                @if($row['val']['type'] == 'image')
                  @if($row['val']['media_url'] !== null)
                    <div>
                      <img class="image_preview" src="{{url('get_media')}}/{{ $app->media_link_parse($row['val']['media_url']) }}/image" />
                    </div>
                    <div class="chat-caption">{{ $row['val']['message'] }}</div>
                  @else
                    Sorry, image not available.
                  @endif
                @elseif($row['val']['type'] == 'text')
                    {!! str_replace("\n","<br/>",$row['val']['message']); !!}
                @else
                    Sorry, currently we do not support media message except image
                @endif
              </div>
               <!-- time -->
              <div align="right"><small>{{ $row['val']['time'] }}
                @if($row['val']['status'] == 'READ')
                  <span class="text-primary"><i class="fas fa-check-double"></i></span>
                @elseif($row['val']['status'] == 'DELIVERED')
                  <span><i class="fas fa-check-double"></i></span>
                @else
                  <span><i class="fas fa-check"></i></span>
                @endif
              </small></div>
          </div>
        </div>
        @endif
    @endforeach
  @endif

@else
  <div class="col-md-12 alert alert-warning">{{ $error }}</div>
@endif