$(function(){
	getNewMessages();
});

function getNewMessages()
{
	var get_messages = setInterval(function()
	{
	    getNotification();
	},5000);
} 

function getNotification()
{
 $.ajax({
  async: false,
  type : 'GET',
  url : notification_page,
  data : {'device_id': device_id,"device_key" : device_key},
  success: function(result){
    var id = $(".btn-send").attr('id');
    if(result.total_data > 0)
    {
      searchChat();
      if(id !== undefined)
      {
        setTimeout(function(){
          load_messages(id,null);
        },100);
      }
    }
  },
  error : function(xhr)
  {
    console.log(xhr.responseText);
  }
});
}
