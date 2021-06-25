/*
	common javascript
*/

$(function(){
  get_province();
  fill_province();
  get_city();
  fill_city();
  get_zip();
  fill_zip();
  // get_country_filter();
  // change_country();
});

var delay_duration = 200;

// ..
function change_country()
{
	$("select[name='country']").change(function(){
		get_country_filter();
	});
}

function get_country_filter()
{
	var country_id = $("select[name='country']").val();
	if(country_id == 95)
	{
		$(".form_province").show();
		$(".form_province").removeAttr('readonly');
		$(".form_zip").hide();
	}
	else
	{
		$(".form_province").hide();
		$(".form_province").val('');
		$(".form_province").attr('readonly','readonly');
		$(".form_zip").show();
	}
}

function get_zip()
  {
    $("input[name='zip']").on("focusin",delay(function(){
    	$(".live-search-wrapper-zip").show();

    	var list_id;
    	var list_id_edit = $(this).attr('list_id');

    	if(list_id_edit == 0)
    	{
    	  list_id = $("select[name='list_id']").val();
    	}
    	else
    	{
    	  list_id = list_id_edit;
    	}

     	display_zip(list_id);
    },delay_duration));

    $("input[name='zip']").on("focusout",delay(function(){
    	$(".live-search-wrapper-zip").hide();
    },delay_duration));
}

function display_zip(list_id)
{
	var box = '';
	$.ajax({
	  type : 'GET',
	  url : url_zip,
	  data : {'list_id' : list_id},
	  dataType : 'json',
	  success : function(result)
	  {
	    $.each(result, function( index, value ) {
	      box += '<div id="'+index+'" class="zip_opt dropdown-item">'+value+'</div>';
	    });
	    $("#display_zip").html(box);
	  },
	  error : function(xhr)
	  {
	    console.log(xhr.responseText);
	  }
	});
}

function get_province()
  {
    $("input[name='province']").on("keyup focusin focusout",delay(function(){

      var country_id = $("select[name='country']").val();
	  if(country_id == 95)
	  {
	  	var val = $(this).val();
	    $(".live-search-wrapper").show();
	    display_province(val);
	  }
	  else
	  {
	  	return false;
	  }
     
    },delay_duration));
}
// ..

function display_province(name)
{
	var box = '';
	$.ajax({
	  type : 'GET',
	  url : url_province,
	  data : {'name' : name},
	  dataType : 'json',
	  success : function(result)
	  {
	    $.each(result, function( index, value ) {
	      box += '<div id="'+index+'" class="prov_opt dropdown-item">'+value+'</div>';
	    });
	    $("#display_province").html(box);
	  },
	  error : function(xhr)
	  {
	    console.log(xhr.responseText);
	  }
	});
}

function get_city()
  {
    $("input[name='city']").on("keyup focusin",delay(function(){
      var country_id = $("select[name='country']").val();
	  if(country_id == 95)
	  {
	  	 var val = $(this).val();
	     var id = $("input[name='province']").attr('data-id');
	     $(".live-search-wrapper-city").show();
	     display_city(val,id);
	  }
	  else
	  {
	  	return false;
	  }
     
    },delay_duration));
}

function display_city(name,province_id)
{
	var box = '';
	$.ajax({
	  type : 'GET',
	  url : url_city,
	  data : {'provinsi_id': province_id ,'name' : name},
	  dataType : 'json',
	  success : function(result)
	  {
	    $.each(result, function( index, value ) {
	      box += '<div id="'+index+'" class="city_opt dropdown-item">'+value+'</div>';
	    });
	    $("#display_city").html(box);
	  },
	  error : function(xhr)
	  {
	    console.log(xhr.responseText);
	  }
	});
}

function fill_zip()
{
	$("body").on("click",".zip_opt",function(){
	   var opt = $(this).text();
	   var id = $(this).attr('id');
	  $("input[name='zip']").val(opt);
	  $(".live-search-wrapper-zip").hide();
	});
}		

function fill_province()
{
	$("body").on("click",".prov_opt",function(){
	   var opt = $(this).text();
	   var id = $(this).attr('id');
	  $("input[name='province']").val(opt);
	  $("input[name='province']").attr('data-id',id);
	  $(".live-search-wrapper").hide();
	});

	$("input[name='province']").on("focusout",delay(function(){
    	$(".live-search-wrapper").hide();
    },delay_duration));
}		

function fill_city()
{
	$("body").on("click",".city_opt",function(){
	   var opt = $(this).text();
	  $("input[name='city']").val(opt);
	  $(".live-search-wrapper-city").hide();
	});

	$("input[name='city']").on("focusout",delay(function(){
    	$(".live-search-wrapper-city").hide();
    },delay_duration));
}

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
