<div>Hai,</div>
<div>Kami mau mengingatkan, kamu belum melakukan transfer atas pembelian <b>Activrespon</b> dengan rincian: </div><br/>

<div>No Order        : {{ $data["no"] }}</div>
<div>Package         : {{ $data["package"] }}</div>
<?php if ($data["disc"]>0 || $data["price"]<$data["total"]) { ?>
<div>Harga           : {{ str_replace(",",".",number_format($data["price"])) }}</div>
<?php } ?>
<?php if ($data["disc"]>0) { ?>
<div>Discount        : {{ str_replace(",",".",number_format($data["disc"])) }}</div>
<?php } ?>
<div>Total Tagihan   : Rp. {{ str_replace(",",".",number_format($data["total"])) }}</div>

<br/>
Silakan transfer sekarang ke<br/>
<b>BCA :  8290-336-261 (Sugiarto Lasjim)</b>

<div>
Setelah transfer, jangan lupa konfirmasi ke link di bawah ini ya. <br/>
Klik ► <a href="{{ url('order') }}" target="_blank">Konfirmasi Pembayaran</a>
</div><br/>

Salam sukses selalu,<br/>
<b>Team Activrespon</b><br/>
------------------------------------------
<br>
<br>
Jika ada yang ingin ditanyakan,<br>
Silakan hubungi support kami di info@activomni.com <br>
Pesan Anda akan kami balas maximal 1x24 jam kerja. <br>
<br>
Atau Anda juga bisa menghubungi support kami di WA 0817-318-368 <br>
Pada jam kerja, Senin s/d Jumat jam 08.00-17.00<br>
