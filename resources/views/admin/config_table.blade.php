@php 
  $no =1;
@endphp

@if($configs->count() > 0)
@foreach($configs as $row)
    <tr>
        <td>{{$no}}</td>
        <td>{{$row->config_name}}</td>
        <td>{{$row->value}}</td>

        @if($panel == false)
        <td>
            <a id="{{ $row->id }}" data-name="{{$row->config_name}}" data-code="{{$row->value}}" class="btn btn-info btn-sm cedit">Edit</a>
        </td>
        @else
        <td>
            <a id="{{ $row->id }}" data-status ="{{ $row->value }}" class="btn btn-info btn-sm btn-status">Change</a>
        </td>
        @endif
       <!--  <td>
            <a id="{{ $row->id }}" class="btn btn-danger btn-sm cdel">Delete</a>
        </td> -->
    </tr>
    @php 
        $no++;
    @endphp
@endforeach
@endif