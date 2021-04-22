@extends('layouts.app')

@section('content')

<div class="table-responsive container">
  <table class="table">
    <thead>
      <th>No</th>
      <th>Phone Number</th>
      <th>Package</th>
      <th>Total</th>
      <th>Pembayaran Bulan</th>
    </thead>

    @if($data->count() > 0)
      <tbody>
        @php $no = 1 @endphp
        @foreach($data as $row)
          <tr>
            <td>{{ $no }}</td>
            <td>{{ $row->phone_number }}</td>
            <td>{{ $row->package }}</td>
            <td>{{ str_replace(",",".",number_format($row->total)) }}</td>
            <td>{{ $row->period }}</td>
          </tr>
          @php $no++; @endphp
        @endforeach
      </tbody>
    @endif

  </table>
</div>

@endsection