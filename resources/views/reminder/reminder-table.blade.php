<!-- tab 2 -->
@if(count($reminder) > 0)
  @foreach($reminder as $index => $row)
  <div class="bg-dashboard campaign row">
    <div class="col-lg-4 pad-fix col-card">
      <h5>{{ $row['campaign_name'] }}</h5>
      <div class="notes">
        <div>Type Campaign : <color><span class="og">Auto Responder</span></color></div>
        <div>Date Send : <b>{{ $row['sending'] }}</b></div>
        <div>List : {{ $row['label'] }}</div>
      </div>
      <div class="created">
        Create On : {{ $row['created_at'] }}
      </div>
    </div>

    <div class="col-lg-5 pad-fix mt-4">
      <div class="row">
          <div class="col-lg-3 pad-fix cardnumber">
            <div class="big-number">{{ $row['total_message'] }}</div>
            <div class="contact">Message</div>
          </div>  
          <div class="col-lg-3 pad-fix cardnumber">
            <div class="big-number">{{ $row['sent_message'] }}</div>
            <div class="contact">Send</div>
          </div> 
          <!--
          <div class="col-lg-3 pad-fix cardnumber">
            <div class="big-number">90</div>
            <div class="contact">Opened</div>
          </div>
          <div class="col-lg-3 pad-fix cardnumber">
            <div class="big-number">90%</div>
            <div class="contact">Open Rate</div>
          </div>
         -->
      </div>  
    </div>

    <div class="col-lg-3 pad-fix col-button">
        <!--<button id="{{ $row['id'] }}" type="button" class="btn btn-warning btn-sm"><span class="icon-eye"></span></button>-->
        <!-- <button id="{{ $row['id'] }}" type="button" class="btn btn-success btn-sm responder_duplicate"><span class="icon-copy-text"></span></button> -->
        <button id="{{ $row['id'] }}" type="button" class="btn btn-danger btn-sm responder-del"><span class="icon-delete"></span></button>
        <div>
          <a href="{{url('add-message-auto-responder').'/'.$row['id']}}" class="btn btn-custom">Add Message</a>
        </div>
    </div>
  </div> 
  @endforeach
  @else
  <div class="bg-dashboard campaign row text-center">
     Currently data not available
  </div>
@endif