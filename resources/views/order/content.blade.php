<div class="paging">
  {{ $pager }}
</div>

<table class="table responsive" id="myTable">
    <thead align="center">
      <th class="menu-mobile">
        Details
      </th>
      <th class="menu-nomobile" action="no_order">
        No Order
      </th>
      <th class="menu-nomobile" action="package">
        Package
      </th>
      <th class="menu-nomobile" action="harga">
        Price
      </th>
      <th class="menu-nomobile" action="discount">
        Discount
      </th>
      <th class="menu-nomobile" action="grand_total">
        Total
      </th>
      <th class="menu-nomobile" action="created_at">
        Date
      </th>
      <th class="menu-nomobile">
        Upload Image
      </th>
      <th class="menu-nomobile" action="keterangan">
        Notes
      </th>
      <th class="header" action="status" style="width:145px">
        Status
      </th>
    </thead>

    <tbody>
      @foreach($orders as $order)
        <tr>
          <td data-label="No Order">
            <div class="menu-mobile">
              <div class="view-details" data-id="{{$order->id}}">
                <span class="menu-mobile icon-dropdown">
                  <i class="fas fa-sort-down"></i>
                </span>  
                {{$order->no_order}}
              </div>
            </div>

            <div class="menu-nomobile">
              {{$order->no_order}}  
            </div>
          </td>
          <td class="menu-nomobile" data-label="Package">
            <?php 
              $strMonth = " Month";
              if ($order->month>1) {
                $strMonth = " Months";
              }
            ?>
            {{$order->package_title." ".$order->month.$strMonth}}
          </td> 
          <td class="menu-nomobile" data-label="Harga">
            Rp. <?php echo number_format($order->total) ?>
          </td>
          <td class="menu-nomobile" data-label="Discount">
            Rp. <?php echo number_format($order->discount) ?>
          </td>
          <td class="menu-nomobile" data-label="Total">
            Rp. <?php echo number_format($order->grand_total) ?>
          </td>
          <td class="menu-nomobile" data-label="Date">
            {{$order->created_at}}
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
          <td data-label="Status">
            @if($order->status==0)
              <button type="button" class="btn btn-primary btn-confirm" data-toggle="modal" data-target="#confirm-payment" data-id="{{$order->id}}" data-no-order="{{$order->no_order}}" data-package="{{$order->package}}" data-total="{{$order->grand_total}}" data-discount="{{$order->discount}}" data-date="{{$order->created_at}}" data-keterangan="{{$order->keterangan}}" style="font-size: 13px; padding: 5px 8px;">
                Confirm Payment
              </button>
            @elseif($order->status==1)
              <span style="color: orange">
                <b>Waiting Admin Confirmation</b>
              </span>
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