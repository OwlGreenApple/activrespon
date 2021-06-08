/*
	common javascript
*/

$(function(){
  get_province();
  fill_province();
  get_city();
  fill_city();
});

var delay_duration = 200;

function get_province()
  {
    $("input[name='province']").on("keyup focusin",delay(function(){
      var val = $(this).val();
      $(".live-search-wrapper").show();
      display_province(val);
    },delay_duration));
}

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
      var val = $(this).val();
      var id = $("input[name='province']").attr('data-id');
      $(".live-search-wrapper-city").show();
      display_city(val,id);
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

function fill_province()
{
	$("body").on("click",".prov_opt",function(){
	   var opt = $(this).text();
	   var id = $(this).attr('id');
	  $("input[name='province']").val(opt);
	  $("input[name='province']").attr('data-id',id);
	  $(".live-search-wrapper").hide();
	});
}		

function fill_city()
{
	$("body").on("click",".city_opt",function(){
	   var opt = $(this).text();
	  $("input[name='city']").val(opt);
	  $(".live-search-wrapper-city").hide();
	});
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
