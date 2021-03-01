@if($customer->count() > 0)
<div class="table-responsive">
  <table class="table" id="data_customer">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Additional</th>
        <th>Date Added</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      @foreach($customer as $col)
        <tr>
          <td>{{ $col->name }}</td>
          <td>{{ $col->email }}</td>
          <td>{{ $col->telegram_number }}</td>
          <td>
            @if($col->additional <> null)
              <a additional="{{ $col->additional }}" class="btn btn-info btn-sm text-white view">View Addtional</a>
            @else
              -
            @endif
          </td>
          <td>{{ Date('Y-M-d H:i:s',strtotime($col->created_at)) }}</td>
          <td><a id="{{ $col->id }}" data-name="{{ $col->name }}" data-last_name="{{ $col->last_name }}" data-email="{{ $col->email }}" data-phone="{{ $col->telegram_number }}" data-code="{{ $col->code_country }}" class="btn btn-info btn-sm text-white edit_customer">Edit</a></td>
          <td><a id="{{ $col->id }}" class="btn btn-danger btn-sm text-white del-customer">Delete</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
  
@else
  <h2>Add Your Contact</h2>
  <h6 class="mt-3">From <a id="tab-contact">Add Contact</a> or <a id="tab-form">Form</a></h6>
@endif

<script type="text/javascript">
  $(document).ready(function(){
      var month = new Array();
      month[1] = "Jan";
      month[2] = "Feb";
      month[3] = "Mar";
      month[4] = "Apr";
      month[5] = "May";
      month[6] = "Jun";
      month[7] = "Jul";
      month[8] = "Aug";
      month[9] = "Sep";
      month[10] = "Oct";
      month[11] = "Nov";
      month[12] = "Dec";

      $("#data_customer").DataTable({
        // "columnDefs" : [{targets:4,className: "alert alert-success"}],
        lengthMenu : [ 10, 25, 50, 75, 100, 250, 500 ],
        aaSorting: [],
        // aaSorting: [[4, 'desc']],
       /* aoColumnDefs: [
            { "aTargets": [ 0 ], "bSortable": false },
            { "aTargets": [ 1 ], "bSortable": false },
            { "aTargets": [ 2 ], "bSortable": false },
            { "aTargets": [ 3 ], "bSortable": false },
            { "mRender": function ( data, type, row ) {
                    
                    var date = new Date(data);
                    // var date = new Date(data).toISOString().slice(0, -1);
                    // date = date.replace(/T|Z|000|\./gi,' ');
                    return date;
                },"aTargets": [ 4 ],
                "bSortable": true 
            }
        ],*/
        "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem('offersDataTables', JSON.stringify(oData));
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse(localStorage.getItem('offersDataTables'));
        }
      });
  });
</script>