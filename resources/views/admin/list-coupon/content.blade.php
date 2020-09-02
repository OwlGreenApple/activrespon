@foreach($coupons as $coupon)
  <tr>
    <td data-label="Kode Kupon">
      {{$coupon->kodekupon}}
    </td>
    <td data-label="Diskon (Nominal)">
      Rp. <?php echo number_format($coupon->diskon_value) ?>
    </td>
    <td data-label="Diskon (Persen)">
      {{$coupon->diskon_percent}}%
    </td>  
    <td data-label="Jenis Kupon">
      @if($coupon->coupon_type == 1)
        Kupon Normal
      @else
        Kupon Upgrade
      @endif
    </td> 
    <td data-label="Valid Until">
      {{$coupon->valid_until}}
    </td>
    <td data-label="Valid To">
      {{$coupon->valid_to}}
    </td>
    <td data-label="Keterangan">
      {{$coupon->keterangan}}
    </td>
    <td data-label="Paket">
      {!! getPackage($coupon->package_id) !!}
    </td>
    <td data-label="Action">
      <button type="button" class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#edit-coupon" data-id="{{$coupon->id}}" data-kodekupon="{{$coupon->kodekupon}}" data-nominal="{{$coupon->diskon_value}}" data-persen="{{$coupon->diskon_percent}}" data-validuntil="{{$coupon->valid_until}}" data-validto="{{$coupon->valid_to}}" data-keterangan="{{$coupon->keterangan}}" data-paket="{{$coupon->package_id}}" data-type="{{ $coupon->coupon_type }}">
        <!-- <i class="fas fa-pen"></i> -->Edit
      </button>  
      <button type="button" class="btn btn-sm btn-danger btn-delete" data-toggle="modal" data-target="#confirm-delete" data-id="{{$coupon->id}}">
        <!-- <i class="far fa-trash-alt"></i> --> Delete
      </button>  
    </td>
  </tr>
@endforeach