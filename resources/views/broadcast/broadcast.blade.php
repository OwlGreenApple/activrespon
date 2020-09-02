<!-- tab 3 -->

@if(count($broadcast) > 0)
  @foreach($broadcast as $index => $row)
     <div class="bg-dashboard campaign row">
        <div class="col-lg-4 pad-fix col-card">
          <h5>{{ $row['campaign'] }}</h5>
          <div class="notes">
            <div>Type Campaign : <color><span class="gr">Broadcast</span></color></div>
            <div>Date Send : <b>{{ $row['day_send'] }}</b></div>
            @if($row['label'] !== null)
              <div>List : {{ $row['label'] }}</div>
            @elseif($row['label'] == null && $row['group_name'] !== null)
              <div>Telegram Group : {{ $row['group_name'] }}</div>
            @elseif($row['label'] == null && $row['channel'] !== null)
              <div>Telegram Channel : {{ $row['channel'] }}</div>
            @endif
          </div>
          <div class="created">
            Create On : {{ $row['created_at'] }}
          </div>
        </div>

        <div class="col-lg-5 pad-fix mt-4">
          <div class="row">
              @if($row['label'] !== null)
                <div class="col-lg-3 pad-fix cardnumber">
                  <div class="big-number">{{ $row['total_message'] }}</div>
                  <div class="contact">Message</div>
                </div>  
                <div class="col-lg-3 pad-fix cardnumber">
                  <div class="big-number">{{ $row['sent_message'] }}</div>
                  <div class="contact">Send</div>
                </div> 
              @endif
              <!--
              <div class="col-lg-3 pad-fix cardnumber">
                <div class="big-number">9</div>
                <div class="contact">Opened</div>
              </div>
              <div class="col-lg-3 pad-fix cardnumber">
                <div class="big-number">9%</div>
                <div class="contact">Open Rate</div>
              </div>
              -->
          </div>  
        </div>

        <div class="col-lg-3 pad-fix col-button">
          <!--
            <button id="{{ $row['id'] }}" type="button" class="btn btn-warning btn-sm"><span class="icon-eye"></span></button>
          -->
          <button id="{{ $row['id'] }}" type="button" class="btn btn-success btn-sm broadcast_duplicate"><span class="icon-copy-text"></span></button>
          <button id="{{ $row['id'] }}" type="button" class="btn btn-danger btn-sm broadcast-del"><span class="icon-delete"></span></button>
        </div>
    </div> 
  @endforeach
  @else
  <div class="bg-dashboard campaign row text-center">
     Currently data not available
  </div>
@endif