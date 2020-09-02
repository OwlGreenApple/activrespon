  @if($customer->count() > 0)
    <ul>
       @foreach($customer as $row)
      	<li>
          <a id="{{ $row->id }}" cname="{{ $row->name }}" phone="{{ $row->telegram_number }}" class="adding-number">{{ $row->name }} -- {{ $row->telegram_number }}</a>
        </li>
       @endforeach
    </ul>
  @else
    <div align="center">No Result</div>
     <div class="mt-2 text-center"><a class="btn btn-custom btn-sm px-3" href="{{ env('APP_URL') }}{{ $url }}" target="_blank">Register Customer</a></div>
  @endif