@extends('layouts.app')

@section('content')

<div class="container alert h5">
  <div>Total Invoice : <b>Rp {{ str_replace(",",".",number_format($total->gt)) }},00</b></div>
  <div>Total Record : <b>{{ str_replace(",",".",number_format($data->count())) }}</b></div>
  <div>Total Phone : <b>{{ str_replace(",",".",number_format($total_phone->gt)) }}</b></div>
  <div>Total Empty Phone : <b>{{ str_replace(",",".",number_format($total_no_phone->gt)) }}</b></div>
  <div>Total Paket 1 WA : <b>{{ str_replace(",",".",number_format($total_package_1->gt)) }}</b></div>
  <div>Total Paket 2 WA : <b>{{ str_replace(",",".",number_format($total_package_2->gt)) }}</b></div>
  <div>Total Paket 3 WA : <b>{{ str_replace(",",".",number_format($total_package_3->gt)) }}</b></div>
  <div>Harga Paket 1 WA : <b>Rp {{ str_replace(",",".",number_format($package->package_list('Paket 1 WA')['price'])) }}</b></div>
  <div>Harga Paket 2 WA : <b>Rp {{ str_replace(",",".",number_format($package->package_list('Paket 2 WA')['price'])) }}</b></div>
  <div>Harga Paket 3 WA : <b>Rp {{ str_replace(",",".",number_format($package->package_list('Paket 3 WA')['price'])) }}</b></div>
</div>

<div class="table-responsive container">
  <table class="table table-striped">
    <thead>
      <th>No</th>
      <th>No Hp</th>
      <th>Quota Terpakai</th>
      <th>Paket</th>
      <th>Tanggal Order</th>
      <th>Pembayaran Bulan</th>
      <th class="text-right">Total</th>
    </thead>

    @if($data->count() > 0)
      <tbody>
        @php $no = 1 @endphp
        @foreach($data as $row)
          <tr>
            <td>{{ $no }}</td>
            <td>{{ $row->phone_number }}</td>
            <td>{{ $package->package_list($row->package)['quota'] - $row->quota }}</td>
            <td>{{ $row->package }}</td>
            <td>{{ $row->created_at }}</td>
            <td>{{ $row->period }}</td>
            <td class="text-right">{{ str_replace(",",".",number_format($row->total)) }},00</td>
          </tr>
          @php $no++; @endphp
        @endforeach
      </tbody>
    @endif

  </table>
</div>

@endsection