$(function() {
  
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=seller_center&fun=statistics",
            dataType: "json",
            success: function(a) {
                $('.sale_goods').text(a.online);
                $('.ck_goods').text(a.offline);
                $('.wg_goods').text(a.lockup);
            }
        })
  

});