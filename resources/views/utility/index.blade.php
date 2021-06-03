@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>FORM TARGETING</h2>
  </div>

  <div class="clearfix"></div>
</div>

<div class="container mb-2 act-tel-campaign">

    <div class="row justify-content-center">
        <div class=" col-sm-4 col-md-6 col-lg-9">
            <div id="msg"><!-- message --></div>
            <form class="px-0" id="save_hobby">
               <div class="form-group">
                  <label><b>Hobby*</b></label>
                    <div class="col-lg-6 row">
                      <input name="category" class="form-control" autocomplete="off" />
                      <small>Please use lowercase letter ex : sport <b>NOT</b> Sport</small>
                      <span class="error category"></span>
                    </div>
                </div>

              <button id="submit_hobby" type="submit" class="btn btn-primary mb-2">Fill Hobby</button>
            </form>

            <!-- table -->
            <div class="form-group justify-content-center mt-3">
                <div id="category"></div>
            </div>
        </div>
    </div>
    <!--  -->

   <div class="row justify-content-center">
        <div class=" col-sm-4 col-md-6 col-lg-9">
            <div id="msg"><!-- message --></div>
            <form class="px-0" id="save_job">
               <div class="form-group">
                  <label><b>Occupation*</b></label>
                    <div class="col-lg-6 row">
                      <input name="category" class="form-control" autocomplete="off" />
                      <span class="error category_occupation"></span>
                    </div>
                </div>

              <button id="save_job" type="submit" class="btn btn-primary mb-2">Fill Occupation</button>
            </form>

            <!-- table -->
            <div class="form-group justify-content-center mt-3">
                <div id="category_job"></div>
            </div>
        </div>
    </div>

<!-- end container -->
</div>  

<script type="text/javascript">
  $(document).ready(function(){
    save_hobby();
    display_hobby();
    save_job();
    display_job();

    edit_category();
    del_category();
  });


  /*function table(){
    $("#category").eq(0).dataTable({
      'aaSorting':[]
    }); 

    $("#category_job").eq(1).dataTable({
      'aaSorting':[]
    });
  }*/


  function save_hobby()
  {
    $("#save_hobby").submit(function(e){
      e.preventDefault();
      var data = $(this).serializeArray();
      data.push({name:'id_category', value : 2});
      save_category(data,"hobby");
    });
  }

  function save_job()
  {
    $("#save_job").submit(function(e){
      e.preventDefault();
      var data = $(this).serializeArray();
      data.push({name:'id_category', value : 3});
      save_category(data,"job");
    });
  }

  function save_category(data,notes)
  {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type : "POST",
      url : "{{ url('targeting-save') }}",
      data : data,
      dataType : 'json',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result){
        
        if(result.status == '1')
        {
          $("#msg").html('<div class="alert alert-success">Kategori telah disimpan</div>');
        }
        else if(result.status == 2)
        {
          if(result.category !== undefined && result.idc == 2)
          {
            $(".category").html(result.category);
          }
          else if(result.category !== undefined && result.idc == 3)
          {
            $(".category_occupation").html(result.category);
          }
          else
          {
            $("#msg").html('<div class="alert alert-danger">Error.</div>');
          }
        }
        else if(result.status == 3)
        {
          if(result.idc == 2)
          {
            $(".category").html("Max hobby category is "+result.max);
          }
          else
          {
            $(".category_occupation").html('Max occupation category is '+result.max);
          }
        }
        else
        {
          $("#msg").html('<div class="alert alert-danger">Error.</div>');
        }

        $(".alert").delay(5000).fadeOut(2000);

        if(notes == 'hobby')
        {
          display_hobby();
        }
        else
        {
          display_job();
        }

        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
    // ajax
  }

  function display_hobby()
  {
    display_category_table(2);
  } 

  function display_job() 
  { 
    display_category_table(3);
  }

  function display_category_table(id){
    $.ajax({
      type : "GET",
      url : "{{ url('targeting-list') }}",
      data : {'id':id},
      dataType : 'html',
      success: function(result)
      {
        if(id == 2)
        {
          $("#category").html(result);
        }
        else
        {
          $("#category_job").html(result);
        }
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
        url : "{{ url('targeting-edit') }}",
        dataType : 'json',
        data : {'category':value,'id':id},
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(result)
        {
          if(result.status == 1)
          {
            if(result.idc == 2)
            {
              display_hobby();
            }
            else
            {
              display_job();
            }

            $("#msg").html('<div class="alert alert-success">Data telah di edit.</div>');
          }
          else if(result.status == 2)
          {
             $("#msg").html('<div class="alert alert-danger">Invalid id</div>');
          }
          else
          {
             $("#msg").html('<div class="alert alert-danger">Error</div>');
          }
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
        },
        error : function(xhr)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });
    });
  }

  function del_category() {
    $("body").on("click",".del",function(){
      var conf = confirm('Do you want delete this category?');
      var id = $(this).attr('id');

      if(conf == true)
      {
        $.ajax({
          // headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : "GET",
          url : "{{ url('targeting-del') }}",
          data : {"id" : id},
          dataType : 'json',
          success: function(result)
          {
            if(result.status == 1)
            {
              if(result.idc == 2)
              {
                $(".category").html('<div class="alert alert-success">Category has been deleted.</div>');
                display_hobby();
              }
              else
              {
                $(".category_occupation").html('<div class="alert alert-success">Category has been deleted.</div>');
                display_job();
              }
            }
            else
            {
              $("#msg").html('<div class="alert alert-danger">Error-.</div>');
            }
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