$(function() {
  
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=seller_center&fun=statistics",
            dataType: "json",
            success: function(a) {
                $('.sale_goods').text(a.online);
                $('.ck_goods').text(a.offline);
                $('.wg_goods').text(a.lockup);
                if(a.payment > 0){
                    $('.state_new').show();
                }
                 if(a.delivery > 0){
                    $('.state_pay').show();
                }
                if(a.send > 0){
                    $('.state_send').show();
                }
                if(a.evalcount >0 ){
                    $('.state_success').show();
                }
                if(a.cancel > 0){
                     $('.state_cancel').show();
                }

                if(a.refund_lock >0 ){
                    $('.order_refund').show();
                }
                if(a.refund > 0){
                     $('.order_refund1').show();
                }
                 if(a.return_lock >0 ){
                    $('.order_return').show();
                }
                if(a.return > 0){
                     $('.order_return1').show();
                }
            }
        })
  

});