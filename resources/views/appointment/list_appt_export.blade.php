<table>
  <thead>
    <tr>
      <th>Date Appointment</th>
      <th>Name Contact</th>
      <th>WA Contact</th>
    </tr>
  </thead>

  <tbody>
    <tr><!-- give spacing on row --></tr>
    @if($campaigns->count() > 0)
      @foreach($campaigns as $row)
        <tr>
          <td>{{ $row->event_time }}</td>
          <td>{{ $row->name }}</td>
          <td> {!! $row->telegram_number !!}</td>
        </tr> 
      @endforeach
    @endif
  </tbody>
</table>