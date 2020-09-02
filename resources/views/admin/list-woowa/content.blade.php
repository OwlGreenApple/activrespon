<?php 
use App\InvoiceOrder;
use App\PhoneNumber;
$i=1;
?>
@foreach($orders as $order)
  <tr>
    <!-- <td>
      {{$i}}
    </td> -->
    <td>
      <?php
        $phoneNumber = PhoneNumber::where("user_id",$order->user_id)->first();
        if (!is_null($phoneNumber)){
          echo $phoneNumber->phone_number;
        }
      ?>
    </td>
    <td data-label="No Order">
      {{$order->no_order}}
    </td>
    <!--
    <td data-label="grand_total" align="right">
      Rp. <?php echo number_format($order->grand_total); ?>
    </td>
    -->
    <td data-label="month" align="center">
      <?php 
        // echo strval(InvoiceOrder::where('order_id',$order->id)->count()+1)." of ".$order->month; 
        echo $order->label_month; 
      ?>
    </td>
    <!--
    <td data-label="tagihan" align="right">
      Rp. <?php echo number_format($order->grand_total / $order->month); ?>
    </td>
    -->
    <td data-label="tagihan" align="right">
      Rp. 125.000
    </td>
    <td data-label="Date">
      {{$order->created_at}}
    </td>
  </tr>
  <?php
  $i+=1;
  ?>
@endforeach
<input type="hidden" name="total_tagihan" id="total_tagihan" value="{{$totaltagihan}}">