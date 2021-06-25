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
          <td>{{ $row->birthday }}</td>
          <td>
            @if($row->gender == "" || $row->gender == null)
              &nbsp;
            @else
              @if($import == 0)
                {{ $cs::$gender[$row->gender] }}
              @else
                {{ $row->gender }}
              @endif
            @endif
          </td>
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
          <td>
            @if($row->marriage == "" || $row->marriage == null)
              &nbsp;
            @else
              @if($import == 0)
                {{ $cs::$marriage[$row->marriage] }}
              @else
                {{ $row->marriage }}
              @endif
            @endif
          </td>
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
          <td>
            @if($row->religion == "" || $row->religion == null)
              &nbsp;
            @else
              @if($import == 0)
                {{ $cs::$religion[$row->religion] }}
              @else
                {{ $row->religion }}
              @endif
            @endif
          </td>
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
                  @php $index = 0; @endphp
                  @foreach($additional as $label=>$value)
                    @php $index++; @endphp

                    @if($index == count($additional))
                      {{ $label }} = {{ $value }}
                    @else
                      {{ $label }} = {{ $value }} <br style="mso-data-placement:same-cell;" />
                    @endif
                  @endforeach
                @endif
            </td>
          @else
            <td>{{ $row->additional }}</td>
          @endif
        </tr> 
      @endforeach
  </tbody>
@endif
</table>