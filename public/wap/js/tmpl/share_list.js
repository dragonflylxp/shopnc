$(function (){
    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });
    template.helper('decodeURIComponent', function(o){
        return decodeURIComponent(o);
    });
    var _id = decodeURIComponent(getQueryString('id'));
    if(_id){
        var goodsarr = _id.split('|');
        var cart_list = new Array();
        var sum = 0;
        if(goodsarr.length>0){
            for(var i=0;i<goodsarr.length;i++){
                var info = goodsarr[i];
                if (isNaN(info)) continue;
                data = getGoods(info, 1);
                if ($.isEmptyObject(data)) continue;
                if (cart_list.length > 0) {
                    var has = false
                    for (var j=0; j<cart_list.length; j++) {
                        if (cart_list[j].store_id == data.store_id) {
                            cart_list[j].goods.push(data);
                            has = true
                        }
                    }
                    if (!has) {
                        var datas = {};
                        datas.store_id = data.store_id;
                        datas.store_name = data.store_name;
                        datas.goods_id = data.goods_id;
                        var goods = new Array();
                        goods = [data];
                        datas.goods = goods;
                        cart_list.push(datas);
                    }
                } else {
                    var datas = {};
                    datas.store_id = data.store_id;
                    datas.store_name = data.store_name;
                    datas.goods_id = data.goods_id;
                    var goods = new Array();
                    goods = [data];
                    datas.goods = goods;
                    cart_list.push(datas);
                }
                
                sum += parseFloat(data.goods_sum);
            }
        }
        var rData = {cart_list:cart_list, sum:sum.toFixed(2), cart_count:goodsarr.length, check_out:false};
        rData.WapSiteUrl = WapSiteUrl;
        var html = template.render('cart-list', rData);
        $('#cart-list').addClass('no-login');
        if (rData.cart_list.length == 0) {
            get_footer();
        }
        $("#cart-list-wp").html(html);
        $('.goto-settlement,.goto-shopping').parent().hide();
    }

    // 店铺全选
    $('#cart-list-wp').on('click', '.store_checkbox', function(){
        $(this).parents('.nctouch-cart-container').find('input[name="cart_id"]').prop('checked', $(this).prop('checked'));
    });
    // 所有全选
    $('#cart-list-wp').on('click', '.all_checkbox', function(){
        $('#cart-list-wp').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    })
    
    $('#cart-list-wp').on('click', '.btn', function(){
        cart();
    });
    
    
});

function cart() {
    var key = getCookie('key');//登录标记
    
    var totalPrice = parseFloat("0.00");
    $('.cart-litemw-cnt').each(function(){
        if ($(this).find('input[name="cart_id"]').prop('checked')) {
            var goods_id = $(this).find('input[name="cart_id"]').val();
            var quantity = 1;
                 if(!key){
                     var goods_info = decodeURIComponent(getCookie('goods_cart'));
                     if (goods_info == null) {
                         goods_info = '';
                     }
                     if(goods_id<1){
                         show_tip();
                         return false;
                     }
                     var cart_count = 0;
                     if(!goods_info){
                         goods_info = goods_id+','+quantity;
                         cart_count = 1;
                     }else{
                         var goodsarr = goods_info.split('|');
                         for (var i=0; i<goodsarr.length; i++) {
                             var arr = goodsarr[i].split(',');
                             if(contains(arr,goods_id)){
                                 show_tip();
                                 return false;
                             }
                         }
                         goods_info+='|'+goods_id+','+quantity;
                         cart_count = goodsarr.length;
                     }
                     // 加入cookie
                     addCookie('goods_cart',goods_info);
                     // 更新cookie中商品数量
                     addCookie('cart_count',cart_count);
                 }else{
                    $.ajax({
                       url:ApiUrl+"/index.php?con=member_cart&fun=cart_add",
                       data:{key:key,goods_id:goods_id,quantity:quantity},
                       type:"post",
                       async:false,
                       success:function (result){
                          var rData = $.parseJSON(result);
                          if(checkLogin(rData.login)){
                          }
                       }
                    })
                 }
        }
    });
    window.location.href = WapSiteUrl+'/tmpl/cart_list.html';
    return true;
}

function getGoods(goods_id, goods_num){
    var data = {};
    $.ajax({
        type:'get',
        url:ApiUrl+'/index.php?con=goods&fun=goods_detail&goods_id='+goods_id,
        dataType:'json',
        async:false,
        success:function(result){
            if (result.datas.error) {
                return false;
            }
            var pic = result.datas.goods_image.split(',');
            data.cart_id = goods_id;
            data.store_id = result.datas.store_info.store_id;
            data.store_name = result.datas.store_info.store_name;
            data.goods_id = goods_id;
            data.goods_name = result.datas.goods_info.goods_name;
            data.goods_price = result.datas.goods_info.goods_price;
            data.goods_num = goods_num;
            data.goods_image_url = pic[0];
            data.goods_sum = (parseInt(goods_num)*parseFloat(result.datas.goods_info.goods_price)).toFixed(2);
        }
    });
    return data;
}

function get_footer() {
        footer = true;
        $.ajax({
            url: WapSiteUrl+'/js/tmpl/footer.js',
            dataType: "script"
          });
}

function check_button() {
    var _has = false
    $('input[name="cart_id"]').each(function(){
        if ($(this).prop('checked')) {
            _has = true;
        }
    });
    if (_has) {
        $('.check-out').addClass('ok');
    } else {
        $('.check-out').removeClass('ok');
    }
}