@extends('layouts.admin')

@section('content')

<div class="container mb-2">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="msg"><!-- message --></div>
            <form id="save_category">
               <div class="form-group">
                  <label>Buat Kategori*</label>
                  <div class="col-sm-12 row">
                    <div class="col-lg-3 row">
                      <input name="category" class="form-control" autocomplete="off" />
                      <span class="error category"></span>
                    </div>

                    <div class="col-sm-6">
                      <select id="parent" name="id_category" class="form-control">
                        <option value="0">Pilih Kategori</option>
                      </select>
                      <span class="error country_name"></span>
                    </div>

                  </div>
                </div>

              <button id="submit" type="submit" class="btn btn-primary mb-2">Buat Kategori</button>
            </form>
        </div>

    </div>
    <!--  -->

    <div class="row justify-content-center">
        <div class="col-md-8">
          <div id="category"></div>
        </div>
    </div>

<!-- end container -->
</div>  

<script type="text/javascript">
  $(document).ready(function(){
    save_category();
    display_category();
    table();
    /*
    editCountry();
    delCountry();
    cancelUpdate();*/
  });

  function save_category()
  {
    $("#save_category").submit(function(e){
      e.preventDefault();
      var data = $(this).serialize();

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
          type : "POST",
          url : "{{ url('list-save-category') }}",
          data : data,
          dataType : 'json',
          beforeSend: function()
          {
            $("#submit").prop('disabled',true).html('Loading....');
          },
          success : function(result){
            
            if(result.status == '1')
            {
              $("#msg").html('<div class="alert alert-success">Kategori telah disimpan</div>');
            }
            else
            {
              $("#msg").html('<div class="alert alert-danger">Error.</div>');
            }

            $(".alert").delay(5000).fadeOut(2000);
            $("#submit").prop('disabled',false).html('Buat Kategori');
          },
          error : function(xhr)
          {
            $("#submit").prop('disabled',true).html('Loading....');
            console.log(xhr.responseText);
          }
      });
    });
  }

  function display_category(){ 
    var options = "";
    $.ajax({
      type : "GET",
      url : "{{ url('list-category') }}",
      dataType : 'json',
      success: function(result)
      {
        $.each(result,function(key, value){
          options += "<option value="+key+">"+value+"</option>";
        });
        // console.log(options);
        $("#parent").append(options);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  function table(){
      $("#category").dataTable();
  }

  /***************************/
 

  function cancelUpdate()
  {
      $("body").on("click",".cancel",function(){
          clearForm();
      });
  }

  function clearForm()
  {
      $("input").val('');
      $("#submit").removeAttr('update');
      $("#submit").html('Insert Country');
      $(".cancel").hide();
  }

  function editCountry() {
    $(".cancel").hide();

    $("body").on("click",".cedit",function(){
      var id = $(this).attr('id');
      var name = $(this).attr('data-name');
      var code = $(this).attr('data-code');
       
      $("input[name='country_name']").val(name);
      $("input[name='code_country']").val(code);
      $("#submit").html('Update');
      $("#submit").attr('update',id);
      $(".cancel").show();

      $('html, body').animate({
          scrollTop: $("#save_country").offset().top
      }, 500);
    });
  }

  function delCountry() {
    $("body").on("click",".cdel",function(){
      var conf = confirm('Are you sure to delete this country?');
      var id = $(this).attr('id');

      if(conf == true)
      {
        $.ajax({
          type : "GET",
          url : "{{ url('country-del') }}",
          data : {id : id},
          dataType : 'json',
          success: function(result)
          {
            alert(result.msg);
            displayCountry();
          },
          error : function(xhr)
          {
            console.log(xhr.responseText);
          }
        });
      }
      else
      {
        return false;
      }
        
    });
  }

</script>
@endsection