@if($data->count() > 0)
  <table class="table table-striped" id="data_customer">
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal Dibuat</th>
        <th>Deskripsi</th>
        <th>Bukti Pembayaran</th>
        <th>Detail</th>
        <th>Komisi</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody >
      @php $no = 1 @endphp
      @foreach($data as $col)
        <tr>
          <td>{{ $no++ }}</td>
          <td>{{ Date('Y-M-d H:i:s',strtotime($col->created_at)) }}</td>
          <td>{{ $col->package }}</td>
          <td>@if($col->buktibayar=='' or $col->buktibayar==null)
              -
            @else
              <a class="preview_payment text-success" data-img="<?php 
                echo Storage::disk('s3')->url($col->buktibayar);
              ?>">View</a>
            @endif
          </td>
          <td><a target="_blank" href="{{ url('reseller-detail') }}/{{$col->package_title}}">Detail</a></td>
          <td>Rp {{ str_replace(",",".",number_format($col->grand_total)) }}</td>
          <td>@if($col->status == 1) Menunggu Pembayaran @else <span class="text-primary"><b>Sudah Di Bayar</b></span> @endif</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

<script type="text/javascript">
  $(document).ready(function(){
      $("#data_customer").DataTable({
        // "columnDefs" : [{targets:4,className: "alert alert-success"}],
        lengthMenu : [ 10, 25, 50, 75, 100, 250, 500 ],
        aaSorting: [],
      });
  });
</script>