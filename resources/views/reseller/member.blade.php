@extends('layouts.app')

@section('content')

<div class="container mb-5 main-cont" style="">
  <div class="row">
    <div class="col-md-12">
      <h2><b>Member</b></h2>  
      <h5>Jumlah user yang bergabung melalui anda</h5>
      <hr>
    </div>
    <!--  -->
    <div class="col-md-12">
     <div class="table-responsive container">
      <table id="member" class="table table-striped">
        <thead>
          <th>No</th>
          <th>Nama User</th>
          <th>Tanggal Bergabung</th>
          <th>Status</th>
        </thead>

        @if($data->count() > 0)
          <tbody>
            @php $no = 1 @endphp
            @foreach($data as $row)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->created_at }}</td>
                <td>@if($row->status == 1) <span class="text-primary">Aktif</span> @else Non-Aktif @endif</td>
              </tr>
            @endforeach
          </tbody>
        @endif

      </table>
    </div>
    <!--  -->
    </div>
    <!--  -->
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    display_table();
  });

  function display_table()
  {
    $("#member").DataTable({
      aasorting : []
    });
  }
</script>

@endsection