@if($reminder->count() > 0)
  @foreach($reminder as $row)
    <div class="aptform">
      <div class="col-lg-12">
          <div class="board">
            <div class="left">Reminder Day : {{ $row->days }}</div>
            <div class="right">
              <a id="{{ $row->id }}" class="icon icon-edit"></a>
              <a id="{{ $row->id }}" class="icon icon-delete"></a>
              <a id="{{ $row->id }}" class="icon icon-carret-down-circle"></a>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="board-{{ $row->id }} slide_message">
              {{ $row->message }}
          </div>
      </div>
    </div>
  @endforeach
@endif