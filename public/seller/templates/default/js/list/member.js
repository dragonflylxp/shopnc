$(function() {
    if (getQueryString("key") != "") {
        var a = getQueryString("key");
        addCookie("key", a);
    } else {
        var a = getCookie("key");
        
    }
    if (a) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member&fun=ajax_member",
            data: {
                key: a
            },
            dataType: "json",
            success: function(a) {
                checkLogin(a.login);
                var e = '<div class="member-info">' + '<a href="'+ApiUrl + '/index.php?con=member_account"><div class="user-avatar"> <img src="' + a.datas.member_info.avator + '"/><sup>' + a.datas.member_info.level_name + '</sup> </div></a><div class="level_bar"><h4>经验值</h4><span><em style="width: '+a.datas.member_info.exppoints_rate+'%;"></em></span></div><div style="position: relative; margin: 0 auto; width: 8rem;"><div class="paopao"><i>'+ a.datas.member_info.tipinfo +'</i></div></div>' + '<div class="user-name"> <span>' + a.datas.member_info.user_name + "</span> </div>" + "</div>" + '<div class="member-collect"><span><a href='+ApiUrl+'/index.php?con=member_favorites><em>' + a.datas.member_info.favorites_goods + "</em>" + "<p>商品收藏</p>" + '</a> </span><span><a href="'+ApiUrl+'/index.php?con=member_favorites_store"><em>' + a.datas.member_info.favorites_store + "</em>" + "<p>店铺收藏</p>" + '</a> </span><span><a href="'+ApiUrl+'/index.php?con=member_goodsbrowse"><i class="goods-browse"></i>' + "<p>我的足迹</p>" + "</a> </span></div>";
                $(".member-top").html(e);
                // addCookie('avator_img',a.datas.member_info.avator);
                //  + '<li><a href="'+ApiUrl + '/index.php?con=member_order&fun=index&data-state=state_notakes">' + (a.datas.member_info.order_notakes_count > 0 ? "<em></em>": "") + '<i class="cc-03"></i><p>待自提</p></a></li>'
                var e = '<li><a href="'+ApiUrl + '/index.php?con=member_order&fun=index&data-state=state_new">' + (a.datas.member_info.order_nopay_count > 0 ? "<em></em>": "") + '<i class="cc-01"></i><p>待付款</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=member_order&fun=index&data-state=state_send">' + (a.datas.member_info.order_noreceipt_count > 0 ? "<em></em>": "") + '<i class="cc-02"></i><p>待收货</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=member_order&fun=index&data-state=state_noeval">' + (a.datas.member_info.order_noeval_count > 0 ? "<em></em>": "") + '<i class="cc-04"></i><p>待评价</p></a></li>' + '<li><a href="'+ApiUrl+'/index.php?con=member_refund">' + (a.datas.member_info.
                return > 0 ? "<em></em>": "") + '<i class="cc-05"></i><p>退款/退货</p></a></li>';
                $("#order_ul").html(e);
                // + '<li><a href="'+ApiUrl + '/index.php?con=member_redpacket"><i class="cc-09"></i><p>红包</p></a></li>'
                var e = '<li><a href="'+ApiUrl + '/index.php?con=member_fund&fun=predepositlog_list"><i class="cc-06"></i><p>预存款</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=member_fund"><i class="cc-07"></i><p>充值卡</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=member_voucher"><i class="cc-08"></i><p>代金券</p></a></li>'  + '<li><a href="'+ApiUrl + '/index.php?con=member_points"><i class="cc-10"></i><p>积分</p></a></li>';
                $("#asset_ul").html(e);
                return false
            }
        })
    } else {
        var i = '<div class="member-info">' + '<a href="'+ApiUrl + '/index.php?con=login" class="default-avatar" style="display:block;"></a>' + '<a href="login.html" class="to-login">点击登录</a>' + "</div>" + '<div class="member-collect"><span><a href="'+ApiUrl + '/index.php?con=login"><i class="favorite-goods"></i>' + "<p>商品收藏</p>" + '</a> </span><span><a href="'+ApiUrl + '/index.php?con=login"><i class="favorite-store"></i>' + "<p>店铺收藏</p>" + '</a> </span><span><a href="'+ApiUrl + '/index.php?con=login" ><i class="goods-browse"></i>' + "<p>我的足迹</p>" + "</a> </span></div>";
        $(".member-top").html(i);
        var i = '<li><a href="login.html"><i class="cc-01"></i><p>待付款</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=login"><i class="cc-02"></i><p>待收货</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=login"><i class="cc-03"></i><p>待自提</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=login"><i class="cc-04"></i><p>待评价</p></a></li>' + '<li><a href="'+ApiUrl + '/index.php?con=login"><i class="cc-05"></i><p>退款/退货</p></a></li>';
        $("#order_ul").html(i);
        return false
    }
    $.scrollTransparent()
});