@extends('layouts.admin')

@section('content')

@if($data->count() > 0)

<div class="container table-responsive">

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
@endif

</div>


<script type="text/javascript">
  $(document).ready(function(){
    table();
    del_category();
    edit_category_child();
  });

  function table(){
    $("#data_utility").dataTable({
      'aaSorting':[]
    });
  }

  function edit_category_child() 
  {
    $("body").on("click",".save",function(){
      var id = $(this).attr('id');
      var value = $("#category-"+id).val();
       
      $.ajax({
        headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type : "POST",
        url : "{{ url('list-category-edit') }}",
        dataType : 'json',
        data : {'category':value,'id':id},
        success: function(result)
        {
          if(result.status == 1)
          {
            alert('Data telah di edit');
          }
          else if(result.status == 2)
          {
            alert('Invalid id');
          }
          else
          {
            alert('Error');
          }
        },
        error : function(xhr)
        {
          console.log(xhr.responseText);
        }
      });
    });
  }

  function del_category() {
    $("body").on("click",".del",function(){
      var conf = confirm('Apakah yakin mau menghapus kategori?');
      var id = $(this).attr('id');

      if(conf == true)
      {
        $.ajax({
          headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : "POST",
          url : "{{ url('list-delete') }}",
          data : {"id" : id},
          dataType : 'json',
          success: function(result)
          {
            if(result.status == 1)
            {
              alert('Kategori telah dihapus');
            }
            else
            {
              alert('Error-');
            }

            location.href="{{ url('list-category') }}/{{ $id }}";
          },
          error : function(xhr)
          {
            console.log(xhr.responseText);
          }
        });
      }
      else
      {
        return false;
      }
        
    });
  }

</script>

@endsection