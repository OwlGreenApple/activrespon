@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>Contacts</h2>
    <h4>List name : {{ $label }}</h4>
  </div>

  <div class="act-tel-dashboard-right">
     <!-- <a href="{{url('list-form')}}" class="btn btn-custom">Create List</a> -->
  </div>
  <div class="clearfix"></div>
</div>

<div class="container text-center mt-4">
  <div class="col-lg-12">
    @if($contact->count() > 0)
    <table id="datasubscriber" class="display" style="width : 100%">
      <thead>
        <tr>
          <!-- <th><input id="chkbox" type="checkbox"/></th> -->
          <th>Email</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Date Added</th>
        </tr>
      </thead>
      <tbody>
          @php $x=1;  @endphp
          @foreach($contact as $rows)
            <tr>
              <!-- <td><input class="chkbox-{{$rows->id}}" type="checkbox"/></td> -->
              <td class="text-left">{{$rows->email}}</td>
              <td>{{$rows->name}}</td>
              <td>{{$rows->telegram_number}}</td>
              <td>{{$rows->created_at}}</td>
            </tr>
            @php $x++; @endphp
          @endforeach
      </tbody>
    </table> 
    @else
      <div class="text-center">You don't have customer</div>
    @endif
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function(){
     table();
  });
  
  function table(){
      $("#datasubscriber").DataTable({
         "lengthMenu": [ 10, 25, 50, 75, 100, 250, 500 ]
      });
  }

</script>
@endsection