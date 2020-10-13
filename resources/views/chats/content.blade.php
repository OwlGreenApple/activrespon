<table id="chats" class="table table-striped">
  <thead>
      <th>No</th>
      <th>Chat Member</th>
      <th>WA Number</th>
      <th>Start Chat</th>
      <th>Delete</th>
  </thead>
  <tbody>
    @php $no = 1; @endphp
    @foreach($members as $row)
      <tr>
        <td>{{ $no }}</td>
        <td>{{ $row->member_name }}</td>
        <td>{{ $row->phone }}</td>
        <td><button id="{{ $row->id }}" type="button" class="btn btn-primary btn-sm">Start Chat</button></td>
        <td><button id="{{ $row->id }}" type="button" class="btn btn-danger btn-sm">Delete Member</button></td>
      </tr>
      @php $no++; @endphp
    @endforeach
  </tbody>
</table>

<script type="text/javascript">

  $(document).ready(function() {
    table();
  });

  function table(){
    $("#chats").dataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    });
  }

</script>