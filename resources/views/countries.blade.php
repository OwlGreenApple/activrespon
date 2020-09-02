@if($data->count() > 0)
  @foreach($data as $row)
    <li class="calling_code" data-call="+{{ $row->code }}">{{ $row->name }} (+{{ $row->code }})</li>
  @endforeach
@endif
