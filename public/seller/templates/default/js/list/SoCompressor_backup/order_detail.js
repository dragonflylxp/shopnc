$(function() {

    var r = getCookie("key");

    if (!r) {

        window.location.href =  WapUrl + "/tmpl/member/login.html";

    }

    $.getJSON(ApiUrl + "/index.php?con=member_order&fun=order_info", {

        key: r,

        order_id: getQueryString("order_id")

    },

    function(t) {

        if(t.code==400){

            window.location.href = ApiUrl +'/index.php?con=member_refund';

        }

        t.datas.order_info.ApiUrl = ApiUrl;

        $("#order-info-container").html(template.render("order-info-tmpl", t.datas.order_info));

        $(".cancel-order").click(e);

        $(".sure-order").click(o);

        $(".evaluation-order").click(d);

        $(".evaluation-again-order").click(a);

        $(".all_refund_order").click(n);

        $(".goods-refund").click(c);

        $(".goods-return").click(_);

        $(".viewdelivery-order").click(l);

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_order&fun=get_current_deliver",

            data: {

                key: r,

                order_id: getQueryString("order_id")

            },

            dataType: "json",

            success: function(r) {

                $("#loding").remove();

                checkLogin(r.login);

                var e = r && r.datas;

                if (e.deliver_info) {

                    $("#delivery_content").html(e.deliver_info.context);

                    $("#delivery_time").html(e.deliver_info.time)

                }

            }

        })

    });

    function e() {

        var r = $(this).attr("order_id");

        layer.open({

            content: '确定取消订单？',

            btn: ['嗯', '不要'],

            yes: function(index){

                t(r);

                layer.close(index);

            }

        });

    }

    function t(e) {

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_order&fun=order_cancel",

            data: {

                order_id: e,

                key: r

            },

            dataType: "json",

            success: function(r) {

                if (r.datas && r.datas == 1) {

                    window.location.reload()

                }

            }

        })

    }

    function o() {

        var r = $(this).attr("order_id");

        layer.open({

            content: '确定收到了货物吗？',

            btn: ['嗯', '不要'],

            yes: function(index){

                i(r);

                layer.close(index);

            }

        });

    }

    function i(e) {

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_order&fun=order_receive",

            data: {

                order_id: e,

                key: r

            },

            dataType: "json",

            success: function(r) {

                if (r.datas && r.datas == 1) {

                    window.location.reload()

                }

            }

        })

    }

    function d() {

        var r = $(this).attr("order_id");

        location.href = WapSiteUrl + "/index.php?con=member_evaluate&order_id=" + r

    }

    function a() {

        var r = $(this).attr("order_id");

        location.href = WapSiteUrl + "/index.php?con=member_evaluate&fun=again_index&order_id=" + r

    }

    function n() {

        var r = $(this).attr("order_id");

        location.href = WapSiteUrl + "/index.php?con=member_refund&fun=refund_all&order_id=" + r

    }

    function l() {

        var r = $(this).attr("order_id");

        location.href = WapSiteUrl + "/index.php?con=member_order&fun=deliver&order_id=" + r

    }

    function c() {

        var r = $(this).attr("order_id");

        var e = $(this).attr("order_goods_id");

        location.href = WapSiteUrl + "/index.php?con=member_refund&fun=refund&order_id=" + r + "&order_goods_id=" + e

    }

    function _() {

        var r = $(this).attr("order_id");

        var e = $(this).attr("order_goods_id");

        location.href = WapSiteUrl + "/index.php?con=member_refund&fun=return&order_id=" + r + "&order_goods_id=" + e

    }

});