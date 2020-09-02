<!-- tab -->
@if($lists->count() > 0)
  <div class="paging">
    {{ $paginate }}
  </div>

  @foreach($lists as $rows)
    <div class="bg-dashboard cardlist col-lg-12">

      <div class="row">
        <div class="col-md-5 col-lg-5 col-card">
          <h5>{{$rows->label}}</h5>
            <div class="link_wrap">Link Form : <a href="{{env('APP_URL')}}{{$rows->name}}" target="_blank">{{env('APP_URL')}}{{$rows->name}}</a>
              <span>
                <a data-link="{{env('APP_URL')}}{{ $rows->name }}" class="btn-copy icon-copy"></a>
              </span>
            </div>

            <div>Created On : {{Date("M d, Y", strtotime($rows->created_at))}}</div> <!--Jan 23, 2020  -->
        </div>

        <div class="col-md-2 col-lg-2 pad-fix cardnumber">
          <div class="big-number">
            @if($listcontroller->newContact($rows->id) !== 0)+@endif {!! $listcontroller->newContact($rows->id) !!}
          </div>
          <div class="contact">New Contacts</div>
        </div> 

        <div class="col-md-2 col-lg-2 pad-fix cardnumber">
          <div class="big-number">
              {!! $listcontroller->contactList($rows->id) !!}
          </div>
          <div class="contact">Contacts</div>
        </div>

        <div class="col-md-3 col-lg-3 pad-fix col-button">
         <!--  <a href="{{url('list-contacts')}}/{{$rows->id}}" target="_blank" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="View Subscribers"><span class="icon-eye"></span></a>  -->
         <a href="{{url('list-edit')}}/{{$rows->id}}" target="_blank" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="View & Edit Subscribers"><span class="icon-eye"></span></a>
         <!--  <a href="{{url('list-edit')}}/{{$rows->id}}" class="btn btn-edit btn-sm"><span class="icon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></span></a> -->
          <a id="{{ $rows->id }}" class="btn btn-primary btn-sm open_export" data-toggle="tooltip" data-placement="top" title="Export"><span class="icon-export"></span></a>
          <button id="{{$rows->id}}" type="button" class="btn btn-success btn-sm duplicate" data-toggle="tooltip" data-placement="top" title="Duplicate" ><span class="icon-copy-text"></span></button>
          <a type="button" id="{{$rows->id}}" class="btn btn-danger btn-sm del" data-toggle="tooltip" data-placement="top" title="Delete"><span class="icon-delete"></span></a>
        </div> 
      </div>
    </div> 
  @endforeach
  <div class="paging">
    {{ $paginate }}
  </div>
  @else
  <div class="alert bg-dashboard cardlist">
    List not found
  </div>
@endif

<!-- end tab -->