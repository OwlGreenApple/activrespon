@extends('layouts.app')

@section('content')

<div class="table-responsive container">
  <table class="table">
    <thead>
      <th>No</th>
      <th>Phone Number</th>
      <th>Package</th>
      <th>Pembayaran Bulan</th>
      <th>Total</th>
    </thead>

    @if($data->count() > 0)
      <tbody>
        @php $no = 1 @endphp
        @foreach($data as $row)
          <tr>
            <td>{{ $no }}</td>
            <td>{{ $row->phone_number }}</td>
            <td>{{ $row->package }}</td>
            <td>{{ $row->period }}</td>
            <td>{{ str_replace(",",".",number_format($row->total)) }}</td>
          </tr>
          @php $no++; @endphp
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Total :</td>
            <td><b>Rp {{ str_replace(",",".",number_format($total->gt)) }}</b></td>
        </tr>
      </tbody>
    @endif

  </table>
</div>

@endsection