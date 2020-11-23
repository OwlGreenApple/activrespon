<table id="chats" class="table table-striped">
  <thead>
      <th>No</th>
      <th>Chat Member</th>
      <th>WA Number</th>
      <th>Status</th>
      <th>Start Chat</th>
      <th>Delete</th>
  </thead>
  <tbody>
  @if(count($members) > 0)
    @php $no = 1; @endphp
    @foreach($members as $row)
      <tr>
        <td>{{ $no }}</td>
        <td>{{ $row['name'] }}</td>
        <td>{{ $row['phone_number'] }}</td>
        <td>
          @if($row['member_status'] == 0)
            <a id="{{ $row['id'] }}" data-invited="{{ $row['invitor'] }}" data-status="1" class="btn btn-success btn-sm response">Accept Invitation</a>
            <a id="{{ $row['id'] }}" data-invited="{{ $row['invitor'] }}" data-status="0" class=" btn btn-warning btn-sm response">Decline Invitation</a>
          @elseif($row['member_status'] == 1)
            <div class="badge badge-info px-2 py-2">Waiting Approval</div>
          @elseif($row['member_status'] == 2)
            <div class="badge badge-success px-2 py-2">Approved</div>
          @elseif($row['member_status'] == 3)
            <div class="badge badge-danger px-2 py-2">Declined</div>
          @else
            <div class="badge badge-dark px-2 py-2">Deleted</div>
          @endif
        </td>
        <td>
          @if($row['member_status'] == 2)
            <button id="{{ $row['invited_id'] }}" type="button" class="btn btn-primary btn-sm btn-chat">Start Chat</button>
          @else
            -
          @endif
        </td>
        <td>
          @if($row['member_status'] == 2 || $row['member_status'] == 3)
            <button id="{{ $row['id'] }}" type="button" class="btn btn-danger btn-sm delete-member">Delete Member</button>
          @else
            -
          @endif
        </td>
      </tr>
      @php $no++; @endphp
    @endforeach
  @endif
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