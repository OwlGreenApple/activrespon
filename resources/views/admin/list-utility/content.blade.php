<table class="table table-striped" id="data_utility">
  <thead>
    <tr>
      <th>No</th>
      <th>Category</th>
      <th>Childs</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody >
    @php $no = 1 @endphp
    @foreach($data as $col)
      <tr>
        <td>{{ $no++ }}</td>
        <td><input id="category-{{ $col->id }}" class="form-control form-control-sm" value="{{ $col->category }}"/></td>
        <td>
          @if($callback->call_display_function($col->id)->count() > 0)
            <a target="_blank" href="{{ url('list-category') }}/{{ $col->id }}">View</a>
          @endif
        </td>
        <td>
          <button id="{{ $col->id }}" type="button" class="btn btn-primary btn-sm save">Save</button>
          <button id="{{ $col->id }}" type="button" class="btn btn-danger btn-sm del">Delete</button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>


<script type="text/javascript">
  $(document).ready(function(){
    table();
  });

  function table(){
    $("#data_utility").dataTable({
      'aaSorting':[]
    });
  }

</script>