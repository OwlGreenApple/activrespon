@foreach($invoices as $invoice)
  <tr>
    <td data-label="No Invoice">
      {{$invoice->no_invoice}}
    </td>

    <td data-label="Total">
      Rp. <?php echo number_format($invoice->total) ?>
    </td>
    <td data-label="Date">
      {{$invoice->created_at}}
    </td>
    <td data-label="Bukti Bayar" align="center">
      @if($invoice->buktibayar=='' or $invoice->buktibayar==null)
        -
      @else
        <a class="popup-newWindow" href="<?php 
            echo Storage::disk('s3')->url($invoice->buktibayar); 
          ?>">
          View
        </a>
      @endif
    </td>
    <td data-label="Keterangan">
      @if($invoice->keterangan=='' or $invoice->keterangan==null)
        -
      @else
        {{$invoice->keterangan}}
      @endif
    </td>
    <td data-label="Status">
    
      @if($user->is_admin==1)
        @if($invoice->status==0)
          <button type="button" class="btn btn-primary btn-confirm" data-toggle="modal" data-target="#confirm-invoice" data-id="{{$invoice->id}}" data-no-invoice="{{$invoice->no_invoice}}" data-total="{{$invoice->total}}" data-date="{{$invoice->created_at}}" data-keterangan="{{$invoice->keterangan}}">
            Confirm
          </button>
        @else 
          <span style="color: green">
            <b>Confirmed</b>
          </span>
        @endif
      @endif
        <button type="button" class="btn btn-primary btn-show" data-toggle="modal" data-target="#view-details" data-id="{{$invoice->id}}">
          Lihat Order
        </button>
    </td>
  </tr>
@endforeach