<strong>Hi, {{$user->name}}</strong> <br>
<br>
Terima kasih, anda telah melakukan pemesanan Activrespon service.<br>
Berikut ini adalah invoice Anda: <br>
<br>
<strong>No Order :</strong> {{$no_order}} <br>
<strong>Nama :</strong> {{$user->name}} <br>
<strong>Status Order :</strong> Pending <br>
Anda telah memesan Paket {{$nama_paket}} <br>
<br>
<?php if ($order->total_upgrade>0 || $order->discount>0 || $order->total<$order->grand_total) { ?>
<strong>Rp. {{number_format($order->total)}} </strong><br>
<?php } ?>
<?php if ($order->total_upgrade>0) { ?>
	<strong>Upgrade Price :</strong>Rp. {{number_format($order->total_upgrade)}} </strong><br>
<?php } ?>
<?php if ($order->discount>0) { ?>
<strong>Diskon :</strong>Rp. {{number_format($order->discount)}} </strong><br>
<?php } ?>
<strong>Total :</strong>Rp. {{number_format($order->grand_total)}} </strong><br>

<br>
	Harap SEGERA melakukan pembayaran,<br> 
	<strong>TRANSFER Melalui :</strong><br>
	<br>
	<strong>Bank BCA</strong><br>
    8290-336-261<br>
    Sugiarto Lasjim<br>
  <br>
	
	
	Dan setelah selesai membayar<br>
	Silahkan lakukan konfirmasi pembayaran di menu Orders, atau bisa dengan mengklik <a href="{{url('order')}}"> KONFIRMASI PEMBAYARAN </a> disini. <br>

<br> Terima kasih, 
<br>
Team Activrespon<br>
<span style="font-style: italic;">*Activrespon is part of Activomni.com</span>

<br>
<br>
Jika ada yang ingin ditanyakan,<br>
Silakan hubungi support kami di info@activomni.com <br>
Pesan Anda akan kami balas maximal 1x24 jam kerja. <br>
<br>
Atau Anda juga bisa menghubungi support kami di WA 0817-318-368 <br>
Pada jam kerja, Senin s/d Jumat jam 08.00-17.00<br>
