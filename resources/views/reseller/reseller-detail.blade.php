@extends('layouts.app')

@section('content')

<div class="table-responsive container">
  <table class="table table-striped">
    <thead>
      <th>No</th>
      <th>Nama Reseller</th>
      <th>Nama User</th>
      <th>Paket</th>
      <th>Harga Paket Reseller</th>
      <th>Tanggal Invoice</th>
      <th>Bulan Invoice</th>
      <th class="text-right">Jumlah Komisi</th>
    </thead>

    @if($data->count() > 0)
      <tbody>
        @php $no = 1 @endphp
        @foreach($data as $row)
          <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $row->reseller_name }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->package }}</td>
            <td>{{ str_replace(",",".",number_format($row->grand_total)) }}</td>
            <td>{{ $row->created_at }}</td>
            <td>{{ $row->period }}</td>
            <td class="text-right"><b>{{ str_replace(",",".",number_format($row->total)) }},00</b></td>
          </tr>
        @endforeach
      </tbody>
    @endif

  </table>
</div>

@endsection