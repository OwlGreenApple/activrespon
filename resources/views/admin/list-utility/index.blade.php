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
                        <!--  -->
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

    <div class="row justify-content-center mt-3">
        <div id="category" class="col-md-8"></div>
    </div>

<!-- end container -->
</div>  

<script type="text/javascript">
  $(document).ready(function(){
    save_category();
    display_category();
    display_category_table();
    edit_category();
    del_category();
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
            display_category();
            display_category_table();
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
    var options = "<option value='0'>Pilih Kategori</option>";
    $.ajax({
      type : "GET",
      url : "{{ url('list-category-option') }}",
      dataType : 'json',
      success: function(result)
      {
        $.each(result,function(key, value){
          options += "<option value="+key+">"+value+"</option>";
        });
        // console.log(options);
        $("#parent").html(options);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  function display_category_table(){
    $.ajax({
      type : "GET",
      url : "{{ url('list-category') }}",
      data : {'id':0},
      dataType : 'html',
      success: function(result)
      {
        $("#category").html(result);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  function edit_category() 
  {
    $("body").on("click",".save",function(){
      var id = $(this).attr('id');
      var value = $("#category-"+id).val();
       
      $.ajax({
        headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type : "POST",
        url : "{{ url('list-category-edit') }}",
        dataType : 'json',
        data : {'category':value,'id':id},
        success: function(result)
        {
          if(result.status == 1)
          {
            alert('Data telah di edit');
          }
          else if(result.status == 2)
          {
            alert('Invalid id');
          }
          else
          {
            alert('Error');
          }
        },
        error : function(xhr)
        {
          console.log(xhr.responseText);
        }
      });
    });
  }

  function del_category() {
    $("body").on("click",".del",function(){
      var conf = confirm('Apakah yakin mau menghapus kategori?');
      var id = $(this).attr('id');

      if(conf == true)
      {
        $.ajax({
          headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : "POST",
          url : "{{ url('list-delete') }}",
          data : {"id" : id},
          dataType : 'json',
          success: function(result)
          {
            if(result.status == 1)
            {
              alert('Kategori telah dihapus');
            }
            else
            {
              alert('Error-');
            }

            display_category_table();
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