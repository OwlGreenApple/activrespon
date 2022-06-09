function change_price_list()
{ 
    $(".pricing_list").click(function(){
        $(".pricing_list").removeClass('active');
        var id = $(this).attr('id');
        $("#"+id).addClass('active');

        if(id == "year")
        {
            $(".month").addClass('d-none');
            $(".year").removeClass('d-none');
        }
        else
        {
            $(".month").removeClass('d-none');
            $(".year").addClass('d-none');
        }
        
    });
}