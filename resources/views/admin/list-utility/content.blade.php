@extends('layouts.admin-empty')

@section('content')

@if($data->count() > 0)
@if($id !== null)
  <div class="container table-responsive">
@endif
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
        <td>{{ $col->category }}</td>
        <td>
          @if($callback->call_display_function($col->id)->count() > 0)
            <a target="_blank" href="{{ url('list-category') }}/{{ $col->id }}">View</a>
          @endif
        </td>
        <td>
          <button id="{{ $col->id }}" type="button" id="save" class="btn btn-primary btn-sm">Save</button>
          <button id="{{ $col->id }}" type="button" id="del" class="btn btn-danger btn-sm">Delete</button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endif

@if($id !== null)
  </div>
@endif

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

@endsection