@if($phoneNumbers->count() > 0)
  @foreach($phoneNumbers as $phoneNumber)
    <tr>
      <td class="text-center">1</td>
      <td class="text-center">{{$phoneNumber->phone_number}}</td>
      <td class="text-center account_status">
        <?php
            if ($phoneNumber->status == 0) {
                echo "<span class='text-danger'>Belum Dihubungkan</span>";
            }
            if ($phoneNumber->status == 1) {
                echo "<span class='down'>Terputus</span>";
            }
            if ($phoneNumber->status == 2) {
                echo "<span class='text-success'>Server Tersambung</span>";
            }
        ?>
      </td>
      <td class="text-center">
        <a class="icon icon-delete" data-id="{{$phoneNumber->id}}"></a>
      </td>
    </tr>
  @endforeach
@else
  <tr>
    <td colspan="4" class="text-center"> No data to display</td>
  </tr>
@endif
