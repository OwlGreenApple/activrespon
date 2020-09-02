@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>SETUP Appointment</h2>
  </div>

  <div class="clearfix"></div>
</div>

<!-- NUMBER -->
<div class="container act-tel-apt">
  <div id="error_db" class="col-lg-12"></div>
  <form id="save_apt">
      
      <div class="form-group row lists">
        <label class="col-sm-4 col-lg-3 col-form-label">Select List :</label>
        <div class="col-sm-8 col-lg-9 relativity">
           <select name="list_id" class="custom-select-campaign form-control">
              @if(count($lists) > 0)
                @foreach($lists as $row)
                  <option value="{{$row['id']}}">{{ $row['customer_count'] }} {{$row['label']}}</option>
                @endforeach
              @endif
           </select>
           <span class="icon-carret-down-circle"></span>
           <span class="error list_id"></span>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-4 col-lg-3 col-form-label">Name of appointment :</label>
        <div class="col-sm-8 col-lg-9">
          <input type="text" name="name_app" class="form-control" />
          <span class="error name_app"></span>
        </div>
      </div>

      <div class="text-right">
        <button class="btn btn-custom">SAVE</button>
      </div>
  </form>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    saveAppointment();
  });

  function saveAppointment()
  {
    $("#save_apt").submit(function(e){
      e.preventDefault();
      var data = $(this).serialize();
      var id;

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{url("save-apt")}}',
          data : data,
          dataType : 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result){

            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            if(result.success == 0)
            {  
                $(".error").show();
                $("#error_db").html('<div class="alert alert-danger">'+result.message+'</div>');
                $(".list_id").html(result.list_id);
                $(".name_app").html(result.name_app);
            }
            else
            {
                $(".error").hide();
                id = result.id;
                $("#error_db").html('<div class="alert alert-success">'+result.message+'</div>');
                //alert(result.message);
                setTimeout(function(){
                  location.href='{{ url("edit-apt") }}/'+id;
                },1000);
            }
          },
          error : function(xhr,attribute,throwable)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
      });
      //ajax
    });
  }
</script>
@endsection
