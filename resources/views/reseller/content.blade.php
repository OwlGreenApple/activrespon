<div class="paging">
  {{ $pager }}
</div>

<table class="table responsive" id="myTable">
    <thead align="center">
      <!-- <th class="menu-mobile">
        Details
      </th> -->
      <th class="menu-nomobile" action="created_at">
        Invoice Date
      </th>
      <th class="menu-nomobile" action="no_order">
        No Invoice
      </th>
      <th class="menu-nomobile" action="package">
        Description
      </th>
      <th class="menu-nomobile" action="harga">
        Total
      </th>
      <th class="menu-nomobile">
        Payment Proof
      </th>
      <th class="menu-nomobile" action="keterangan">
        Notes
      </th>
      <th class="menu-nomobile">
        Details
      </th>
      <th class="menu-nomobile" action="Confirm">
        Confirmed On
      </th>
      <th class="header" action="status" style="width:145px">
        Action
      </th>
    </thead>

    <tbody>
      @foreach($orders as $order)
        <tr>
          <td class="menu-nomobile" data-label="Date">
            {{$order->created_at}}
          </td>
          <td class="menu-nomobile" data-label="No Order">
            {{$order->no_order}}  
          </td>
          <td class="menu-nomobile" data-label="Package">
            {{$order->package}}
          </td> 
          <td class="menu-nomobile text-right" data-label="Harga">
            Rp. <?php echo number_format($order->total) ?>
          </td>
          <td class="menu-nomobile" data-label="Bukti Bayar" align="center">
            @if($order->buktibayar=='' or $order->buktibayar==null)
              -
            @else
              <a class="popup-newWindow" href="<?php 
                // echo Storage::disk('public')->url('app/'.$order->buktibayar);
                echo Storage::disk('s3')->url($order->buktibayar);
              ?>">
                View
              </a>
            @endif
          </td>
          <td class="menu-nomobile" data-label="Keterangan">
            @if($order->keterangan=='' or $order->keterangan==null)
              -
            @else
              {{$order->keterangan}}
            @endif
          </td>
          <td><a target="_blank" href="{{ url('detail-invoice') }}/{{$order->package_title}}/0">Detail</a></td>
          <td class="menu-nomobile" data-label="Confirm">
             @if($order->date_confirm == null) - @else {{$order->date_confirm}} @endif
          </td>
          <td data-label="Status" class="text-center">
            @if($order->status==2)
              <button type="button" class="btn btn-primary btn-confirm" data-toggle="modal" data-target="#confirm-payment" data-id="{{$order->id}}" data-no-order="{{$order->no_order}}" data-package="{{$order->package}}" data-total="{{$order->grand_total}}" data-date="{{$order->created_at}}" data-keterangan="{{$order->keterangan}}" style="font-size: 13px; padding: 5px 8px;">
                Pay Now
              </button>
            @else 
              <span style="color: green">
                <b>Confirmed</b>
              </span>
            @endif
          </td>
        </tr>

        <tr class="details-{{$order->id}} d-none">
          <td>
            Package : <b>{{$order->package}}</b><br>
            Harga : <b>
                      Rp. <?php echo number_format($order->total) ?>    
                    </b><br>
            Discount : <b>
                        Rp. <?php echo number_format($order->discount) ?>
                       </b><br>
            Total : <b>
                      Rp. <?php echo number_format($order->grand_total) ?>
                    </b><br>
          </td>
          <td>
            Date : <b>{{$order->created_at}}</b><br>
            Bukti Bayar : 
              @if($order->buktibayar=='' or $order->buktibayar==null)
                -
              @else
                <a class="popup-newWindow" href="<?php echo Storage::url($order->buktibayar) ?>">
                  View
                </a>
              @endif
              <br>
            Keterangan : 
            <b>
              @if($order->keterangan=='' or $order->keterangan==null)
                -
              @else
                {{$order->keterangan}}
              @endif
            </b>
          </td>
        </tr>
      @endforeach
    </tbody>
</table>

<div class="paging">
  {{ $pager }}
</div>