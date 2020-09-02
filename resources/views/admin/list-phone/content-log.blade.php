@foreach($logs as $log)
  <tr>
    <td data-label="Type">
      {{$log->type}}
    </td>
    <td data-label="Value">
      {{$log->value}}
    </td>
    <td data-label="Keterangan">
      {{$log->keterangan}}
    </td>
    <td data-label="Created At">
      {{$log->created_at}}
    </td>
  </tr>
@endforeach