<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>WA Number</th>
      <th>Email</th>
      <th>Last Name</th>
      <th>Birthday</th>
      <th>Gender</th>
      <th>Country</th>
      <th>Province</th>
      <th>City</th>
      <th>Zip</th>
      <th>Status</th>
      <th>Hobby</th>
      <th>Occupation</th>
      <th>Religion</th>
      @if($import == 0)
        <th>Additional</th>
      @endif
    </tr>
  </thead>

@if($customer->count() > 0)
  <tbody>
      @foreach($customer as $row)
        <tr>
          <td>{{ $row->name }}</td>
          <td>{{ $row->telegram_number }}</td>
          <td>{{ $row->email }}</td>
          <td>{{ $row->last_name }}</td>
          <td>{{ Date("Y-m-d",strtotime($row->birthday)) }}</td>
          <td>{{ $row->gender }}</td>
          <td>
            @if($import == 0)
              {{ $fct->get_country_name($row->country) }}
            @else
              {{ $row->country }}
            @endif
          </td>
          <td>{{ $row->province }}</td>
          <td>{{ $row->city }}</td>
          <td>{{ $row->zip }}</td>
          <td>{{ $row->marriage }}</td>
          <td>
            @if($import == 0)
              {{ str_replace(";","|",$row->hobby) }}
            @else
              {{ $row->hobby }}
            @endif
          </td>
          <td>
            @if($import == 0)
              {{ str_replace(";","|",$row->occupation) }}
            @else
              {{ $row->occupation }}
            @endif
          </td>
          <td>{{ $row->religion }}</td>
          @if($import == 0)
            <td>
                @php 
                  $additional = array(); 
                  if($row->additional !== null)
                  {
                    $additional = json_decode($row->additional,true); 
                  }
                @endphp

                @if(count($additional) > 0)
                  @foreach($additional as $label=>$value)
                    {{ $label }} = {{ $value }} <br style="mso-data-placement:same-cell;" />
                  @endforeach
                @endif
            </td>
          @endif
        </tr> 
      @endforeach
  </tbody>
@endif
</table>