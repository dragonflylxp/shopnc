$(function() {
  
    var r = getQueryString("order_id");
  $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?con=seller_order&fun=search_deliver",
        data:{order_id:r},
        dataType:'json',
        success:function(result) {
            //检测是否登录了
            // checklogin(result.login);

            var data = result && result.datas;
            if (!data) {
                data = {};
                data.err = '暂无物流信息';
            }else{
			    var t = template.render("order-delivery-tmpl", data);
                $("#order-delivery").html(t)
			}
        }
    });
});