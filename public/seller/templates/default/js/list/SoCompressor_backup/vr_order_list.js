var page = pagesize;

var curpage = 1;

var hasMore = true;

var footer = false;

var reset = true;

var orderKey = "";

$(function() {

    var e = getCookie("key");

    if (!e) {

        window.location.href =  WapUrl + "/tmpl/member/login.html";

    }

    if (getQueryString("data-state") != "") {

        $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("data-state") + '"]').addClass("selected").siblings().removeClass("selected")

    }

    var t = false;

    var a = false;

    $("#search_btn").click(function() {

        reset = true;

        r()

    });

    $("#fixed_nav").waypoint(function() {

        $("#fixed_nav").toggleClass("fixed")

    },

    {

        offset: "50"

    });

    function r() {

        if (reset) {

            curpage = 1;

            hasMore = true;

            $("#footer").html("")

        }

        // $(".loading").remove();

        // if (!hasMore) {

        //     return false

        // }

        // hasMore = false;

        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");

        var a = $("#order_key").val();

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_vr_order&fun=order_list&page=" + page + "&curpage=" + curpage,

            data: {

                key: e,

                state_type: t,

                order_key: a

            },

            dataType: "json",

            success: function(e) {

                $("#loding").hide();

                checkLogin(e.login);

                curpage++;

                // hasMore = e.hasmore;

                // if (!hasMore) {

                //     get_footer()

                // }

                if (e.datas.order_list.length <= 0) {

                    $("#footer").addClass("posa")

                }

                var t = e.datas;

                t.WapSiteUrl = WapSiteUrl;

                t.ApiUrl = ApiUrl;

                t.key = getCookie("key");

                template.helper("$getLocalTime",

                function(e) {

                    var t = new Date(parseInt(e) * 1e3);

                    var a = "";

                    a += t.getFullYear() + "年";

                    a += t.getMonth() + 1 + "月";

                    a += t.getDate() + "日 ";

                    a += t.getHours() + ":";

                    a += t.getMinutes();

                    return a

                });

                template.helper("p2f",

                function(e) {

                    return (parseFloat(e) || 0).toFixed(2)

                });

                template.helper("parseInt",

                function(e) {

                    return parseInt(e)

                });

                var a = template.render("order-list-tmpl", t);

                if (reset) {

                    reset = false;

                    $("#loding").hide();

                    $("#order-list").html(a)

                } else {

                    $("#loding").hide();

                    $("#order-list").append(a)

                }

            }

        })

    }

    $.ajax({

        type: "get",

        url: ApiUrl + "/index.php?con=member_payment&fun=payment_list",

        data: {

            key: e

        },

        dataType: "json",

        success: function(e) {

            var r = {};

            $.each(e && e.datas && e.datas.payment_list || [],

            function(e, t) {

                r[t] = true

            });

            var o = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);

            if (parseInt(o && o[1] || 0) >= 5) {

                if (r.wxpay_jsapi) {

                    a = true

                }

            } else {

                if (r.alipay) {

                    t = true

                }

            }

        }

    });

    r(page, curpage);

    $(window).scroll(function() {

        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {

            r()

        }

    });

    $("#order-list").on("click", ".check-payment",

    function() {

        var e = $(this).attr("data-paySn");

        toPay(e, "member_vr_buy", "pay");

        return false

    });

    $("#order-list").on("click", ".cancel-order", o);

    function o() {

        var e = $(this).attr("order_id");

         layer.open({

            content: '确定取消订单？',

            btn: ['嗯', '不要'],

            yes: function(index){

                a(e)

                layer.close(index);

            }

        });

    }

    function i(t) {

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_vr_order&fun=order_cancel",

            data: {

                order_id: t,

                key: e

            },

            dataType: "json",

            success: function(e) {

                if (e.datas && e.datas == 1) {

                    r(page, curpage)

                } else {

               

                     layer.open({

                        content: e.datas.error,

                        time:1.5

                    });

                }

            }

        })

    }

    $("#order-list").on("click", ".evaluation-order",

    function() {

        var e = $(this).attr("order_id");

        location.href = WapSiteUrl + "/tmpl/member/member_vr_evaluation.html?order_id=" + e

    });

    $("#filtrate_ul").find("a").click(function() {

        $("#filtrate_ul").find("li").removeClass("selected");

        $(this).parent().addClass("selected").siblings().removeClass("selected");

        reset = true;

        window.scrollTo(0, 0);

        r()

    })

});

// function get_footer() {

//     if (!footer) {

//         footer = true;

//         $.ajax({

//             url: WapSiteUrl + "/js/tmpl/footer.js",

//             dataType: "script"

//         })

//     }

// }