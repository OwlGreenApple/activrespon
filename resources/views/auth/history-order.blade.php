@extends('layouts.app')

@section('content')

  <!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>HISTORY ORDER</h2>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container act-tel-history">
    <table class="table table-bordered mt-4">
      <thead class="bg-dashboard">
        <tr>
          <th class="text-center">No Order</th>
          <th class="text-center">Package</th>
          <th class="text-center">Price</th>
          <th class="text-center">Disc</th>
          <th class="text-center">Total</th>
          <th class="text-center">Date</th>
          <th class="text-center">Payment</th>
          <th class="text-center">Information</th>
          <th class="text-center">Status</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td class="text-center">ACT1891</td>
          <td class="text-center">Elite</td>
          <td class="text-center">Rp 29000</td>
          <td class="text-center">Rp 0</td>
          <td class="text-center">Rp 29000</td>
          <td class="text-center">Feb 01 - 2020</td>
          <td class="text-center"><a>View</a></td>
          <td class="text-center">Bukti</td>
          <td class="text-center"><span class="confirmed">Confirmed</span></td>
        </tr> 
        <tr>
          <td class="text-center">ACT1891</td>
          <td class="text-center">Elite</td>
          <td class="text-center">Rp 29000</td>
          <td class="text-center">Rp 0</td>
          <td class="text-center">Rp 29000</td>
          <td class="text-center">Feb 01 - 2020</td>
          <td class="text-center"><a>View</a></td>
          <td class="text-center">-</td>
          <td class="text-center"><span class="wait">Waiting Admin Confirmation</span></td>
        </tr> 
        <tr>
          <td class="text-center">ACT1891</td>
          <td class="text-center">Elite</td>
          <td class="text-center">Rp 29000</td>
          <td class="text-center">Rp 0</td>
          <td class="text-center">Rp 29000</td>
          <td class="text-center">Feb 01 - 2020</td>
          <td class="text-center">-</td>
          <td class="text-center">-</td>
          <td class="text-center"><a class="btn btn-custom btn-sm">Confirm Payment</a></td>
        </tr>
      </tbody>
    </table>
</div>
@endsection
