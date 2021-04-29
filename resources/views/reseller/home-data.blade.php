@if($data->count() > 0)
  <table class="table table-striped" id="data_customer">
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal Dibuat</th>
        <th>ID</th>
        <th>Phone Number</th>
        <th>Paket</th>
        <th>Nama Device / Server</th>
        <th>Status</th>
        <th>Hapus</th>
      </tr>
    </thead>
    <tbody >
      @php $no = 1 @endphp
      @foreach($data as $col)
        <tr>
          <td>{{ $no }}</td>
          <td>{{ Date('Y-M-d H:i:s',strtotime($col->created_at)) }}</td>
          <td>{{ $col->id }}</td>
          <td>{{ $col->phone_number }}</td>
          <td>{{ $col->package }}</td>
          <td>{{ $col->device_name }}</td>
          <td>@if($col->device_status == 1) Connected @else Disconnected @endif</td>
          <td class="text-center">@if($col->is_delete == 1) - @else <a id="{{ $col->id }}" class="btn btn-danger btn-sm text-white del-customer">Delete</a>@endif</td>
        </tr>
        @php $no++ @endphp
      @endforeach
    </tbody>
  </table>
@endif

<script type="text/javascript">
  $(document).ready(function(){
      $("#data_customer").DataTable({
        // "columnDefs" : [{targets:4,className: "alert alert-success"}],
        lengthMenu : [ 10, 25, 50, 75, 100, 250, 500 ],
        aaSorting: [],
      });
  });
</script>