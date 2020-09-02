<div class="act-tel-tab">
  @if(count($data) > 0)

  <!-- PAGINATION -->
  <div class="paging">
    {{ $paginate }}
  </div>

    @foreach($data as $row)
      <div class="bg-dashboard campaign">
        <div class="row">
        <div class="col-md-5 col-lg-5 pad-fix col-card">
          <h5>
            <span class="campaignid-{{$row['id']}}">{{ $row['campaign_name'] }}</span>
            <span>
              <a data-name="{{ $row['campaign_name'] }}" id="{{ $row['id'] }}" class="edit icon-edit"></a>
            </span>  
          </h5> 

          <div class="notes">
            <div>
              Status : <color>@if($row['published'] == 0) draft @else published @endif</color> <span class="created">
                <!-- Created On :  $row['created_at'] -->
                @if($row['event_time'] == '-')
                  Date Event :  -
                @else
                Date Event : <span class="campaign_event_id-{{$row['id']}} mr-1"><b>{{ Date('M d, Y',strtotime($row['event_time'])) }}</b></span>
                @if($row['total_message'] > 0)
                  <span>
                    <a data-toggle="tooltip" data-toggle="tooltip" data-placement="right" title="Edit Event Date" data-name="{{ $row['event_time'] }}" id="{{ $row['id'] }}" class="edit_date icon-calendar"></a>
                  </span> 
                @endif
            @endif 
            </span>
            </div>

            <div>List : <a target="_blank" href="{{ url('list-edit') }}/{{ $row['list_id'] }}">{{ $row['label'] }}</a></div>
          </div>

        </div>

        <div class="col-md-4 col-lg-4 pad-fix">
          <div class="row">
             <!--  <div class="col-lg-3 pad-fix cardnumber">
                <div class="big-number">100</div>
                <div class="contact">Opened</div>
              </div> -->

              <div class="col-md-4 col-lg-4 pad-fix cardnumber">
                <div class="big-number">
                    <a class="contacts" href="{{url('add-message-event').'/'.$row['id']}}">{{ $row['total_template'] }}</a>
                </div>
                <div class="contact">Total Template</div>
              </div>

              <div class="col-md-3 col-lg-3 pad-fix cardnumber">
                <div class="big-number">
                    <a class="contacts" href="{{url('list-campaign').'/'.$row['id'].'/1/1'}}">{{ $row['total_message'] }}</a>
                </div>
                <div class="contact">Queue</div>
              </div>  
              <div class="col-md-3 col-lg-3 pad-fix cardnumber">
                <div class="big-number">
                    <a class="contacts" href="{{url('list-campaign').'/'.$row['id'].'/1/0'}}">{{ $row['sent_message'] }}</a>
                </div>
                <div class="contact">Delivered</div>
              </div> 
              
          </div>  
        </div>

        <div class="col-md-3 col-lg-3 pad-fix col-button">
          <!--
          <a href="{{url('report-reminder')}}" id="{{ $row['id'] }}" class="btn btn-warning btn-sm"><span class="icon-eye"></span></a>
          -->
            <a href="{{url('add-message-event').'/'.$row['id']}}" class="btn btn-custom btn-sm">
              Add & Edit Message
            </a>
            @if($row['published'] == 1)
              <button type="button" id="{{ $row['id'] }}" data-list-id="{{ $row['list_id'] }}"  class="btn btn-success event_duplicate btn-sm" data-toggle="tooltip" title="Button Duplicate"><span class="icon-copy-text"></span></button>
            @else 
              <button type="button" id="{{ $row['id'] }}"  class="btn btn-primary published btn-sm" data-toggle="tooltip" title="Button Publish">Publish</button>
            @endif
            <button type="button" id="{{ $row['id'] }}" class="btn btn-danger event-del btn-sm" data-toggle="tooltip" data-placement="top" title="Button Delete"><span class="icon-delete"></span></button>
        </div>
        <!-- end row -->
      </div> 
      </div> 
    @endforeach

  <!-- PAGINATION -->
  <div class="paging">
    {{ $paginate }}
  </div>
  @else
    <div class="alert bg-dashboard cardlist">
      Event not found
    </div>
  @endif
</div>

<script type="text/javascript">
  $(document).ready(function(){
     $('[data-toggle="tooltip"]').tooltip({
        'placement':'top'
      });       
  });
</script>
