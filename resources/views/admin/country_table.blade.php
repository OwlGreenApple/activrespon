@php 
  $no =1;
@endphp

@if($country->count() > 0)
@foreach($country as $row)
    <tr>
        <td>{{$no}}</td>
        <td>{{$row->name}}</td>
        <td>{{$row->code}}</td>
        <td>
            <a id="{{ $row->id }}" data-name="{{$row->name}}" data-code="{{$row->code}}" class="btn btn-info btn-sm cedit">Edit</a>
        </td>
        <td>
            <a id="{{ $row->id }}" class="btn btn-danger btn-sm cdel">Delete</a>
        </td>
    </tr>
    @php 
        $no++;
    @endphp
@endforeach
@endif