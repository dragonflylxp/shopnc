var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = "";
function select_input(obj){
    if($(obj).attr('id')=='d4'){
        $(obj).parent().siblings('#other_reason').show();
    }else{
        $(obj).parent().siblings('#other_reason').hide();
    }

}

$(function() {

    

    if (getQueryString("data-state") != "") {

        $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("data-state") + '"]').addClass("selected").siblings().removeClass("selected")

    }

    $("#search_btn").click(function() {

        reset = true;

        t()

    });

    $("#fixed_nav").waypoint(function() {

        $("#fixed_nav").toggleClass("fixed")

    },

    {

        offset: "50"

    });

    function t() {

        if (reset) {

            curpage = 1;

            hasMore = true

        }

        $(".loading").remove();

        if (!hasMore) {

            return false

        }

        // hasMore = false;

        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");

        var r = $("#order_key").val();



        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=seller_order&fun=order_list&page=" + page + "&curpage=" + curpage,

            data: {
                state_type: t,
                order_key: r
            },

            dataType: "json",

            success: function(e) {

                $("#loding").hide();

                curpage++;

                // hasMore = e.hasmore;

                // if (!hasMore) {

                //     get_footer()

                // }

               

                var t = e;
                asa = t.datas.state_type;
                t.ApiUrl = ApiUrl;

                t.key = getCookie("key");

                template.helper("$getLocalTime",

                function(e) {

                    var t = new Date(parseInt(e) * 1e3);

                    var r = "";

                    r += t.getFullYear() + "年";

                    r += t.getMonth() + 1 + "月";

                    r += t.getDate() + "日 ";

                    r += t.getHours() + ":";

                    r += t.getMinutes();

                    return r

                });

                template.helper("p2f",

                function(e) {

                    return (parseFloat(e) || 0).toFixed(2)

                });

                template.helper("parseInt",

                function(e) {

                    return parseInt(e)

                });

                var r = template.render("order-list-tmpl", t);

                if (reset) {

                    reset = false;

                    $("#order-list").html(r)

                } else {

                    $("#order-list").append(r)

                }

            }

        })

    }

    $("#order-list").on("click", ".cancel-order", r);

    $("#order-list").on("click", ".sure-order", n);

    $("#order-list").on("click", ".spay_price-order",g);

    $("#order-list").on("click", ".evaluation-order", l);

    $("#order-list").on("click", ".evaluation-again-order", d);

    $("#order-list").on("click", ".viewdelivery-order", c);

    $("#order-list").on("click", ".store_send-order", k);

    $("#order-list").on("click", ".check-payment",

    function on() {

        var e = $(this).attr("data-paySn");

        toPay(e, "member_buy", "pay");

        return false

    });



    function r() {

        var e = $(this).attr("order_id");

          var order_sn =  $(this).attr("order_sn");

        $('.alert_box').find('.num').text(order_sn);

         var update_prcie = layer.open({

             title: '取消订单',

             content:$('.alert_box_two').html(),

             btn: ['确定'],

             yes: function(index){



                var d_id = $('.layermcont').find("input[type='radio']:checked").attr('id');

                var state_info = $('.layermcont').find("input[type='radio']:checked").val();

                if(d_id=='d4'){

                    var state_info1 = $('.layermcont').find("#other_reason_input").val();

                    if(state_info1==''){

                         layer.open({

                            content:'不得为空'

                         });

                    }

                }

                layer.open({type:2,content:"提交中..."});

                  $.ajax({

                    type: "post",

                    url: ApiUrl + "/index.php?con=seller_order&fun=order_cancel",

                    data: {

                        state_info1: state_info1,

                        order_id: e,

                        state_info: state_info

                    },

                    dataType: "json",

                    async: false,

                    success: function(e) {

                        if (e.code == 200) {

                            layer.open({

                                content:'取消成功!'

                            });

                             setTimeout(function () {

                                location.reload();

                                layer.close(index);

                            }, 1000);  

                           

                        } else {

                        

                            layer.open({

                                content:e.datas.error,

                                time:1.5

                             })

                            layer.close(index);

                        }

                    }

                });

               



             }

         })

    }



 

    function i(r) {

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_order&fun=order_delete",

            data: {

                order_id: r,

               

            },

            dataType: "json",

            success: function(e) {

                if (e.datas && e.datas == 1) {

                    reset = true;

                    t()

                } else {

                    layer.open({

                        content:  e.datas.error,

                        time: 1.5 

                    });

                }

            }

        })

    }

    function n() {

        var e = $(this).attr("order_id");

     

         layer.open({

            content: '确定收到了货物吗？',

            btn: ['嗯', '不要'],

            yes: function(index){

                 s(e)

                layer.close(index);

            }

        });

    }

    function s(r) {

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_order&fun=order_receive",

            data: {

                order_id: r,



            },

            dataType: "json",

            success: function(e) {

                if (e.datas && e.datas == 1) {

                    reset = true;

                    t()

                } else {

                    layer.open({

                        content:e.datas.error,

                        time:1.5

                    })

                }

            }

        })

    }

    function l() {

        var e = $(this).attr("order_id");

        location.href = ApiUrl + "/index.php?con=member_evaluate&order_id=" + e

    }

    function d() {

        var e = $(this).attr("order_id");

        location.href = ApiUrl + "/index.php?con=member_evaluate&fun=again_index&order_id=" + e

    }

    function c() {

        var e = $(this).attr("order_id");

        location.href = ApiUrl + "/index.php?con=seller_order&fun=deliver&order_id=" + e

    }

    function k() {

        var e = $(this).attr("order_id");

        location.href = ApiUrl + "/index.php?con=seller_order&fun=seller_send&order_id=" + e

    }

    function g(){

        var e = $(this).attr("order_id");

        var f = $(this).attr("goods_price");

        var t = $(this).attr("order_sn");

        var m = $(this).attr("mai_name");

        $('.alert_box').find('.num').text(t);

        $('.alert_box').find('.mai_name').text(m);

         $("#goods_amount").attr("value",f);

        $("input[name=order_id]").val(e);

         var update_prcie = layer.open({

             title: '修改价格',

             content:$('.alert_box_hide').html(),

             btn: ['修改'],

             yes: function(index){

                var order_id = $('.layermcont').find('#order_id').val();

                var goods_amount = $('.layermcont').find('#goods_amount').val();

                if(goods_amount=='' || isNaN(goods_amount)){

                     layer.open({

                        content:'价格有误'

                     });

                }

                layer.open({type:2,content:"提交中..."});

                  $.ajax({

                    type: "post",

                    url: ApiUrl + "/index.php?con=seller_order&fun=edit_order_price",

                    data: {

                        order_id: order_id,

                        goods_amount: goods_amount

                    },

                    dataType: "json",

                    async: false,

                    success: function(e) {

                        if (e.code == 200) {

                            layer.open({

                                content:'更新成功!'

                            });

                             setTimeout(function () {

                                location.reload();

                                layer.close(index);

                            }, 1000);  

                           

                        } else {

                        

                            layer.open({

                                content:e.datas.error,

                                time:1.5

                             })

                            layer.close(index);

                        }

                    }

                });

               



             }

         })

    }

    $("#filtrate_ul").find("a").click(function() {

        $("#filtrate_ul").find("li").removeClass("selected");

        $(this).parent().addClass("selected").siblings().removeClass("selected");

        reset = true;

        window.scrollTo(0, 0);

        t()

    });

    t();

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {

            t()

        }

    })

});

