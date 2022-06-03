function change_price_list()
{
    $("body").on("click",".pricing_list",function(){
        var time = $(this).attr('data-total');
        display_pricelist(time);
    });
}

function display_pricelist(target)
{
    $.ajax({
        type:'GET',
        url: target_url,
        data:{'default':target,'account':is_account},
        dataType:"html",
        success : function(result)
        {
            $(".price_list_data").html(result);   
        },
        error : function(xhr)
        {
            console.log(xhr.responseText);
        }
    });
}