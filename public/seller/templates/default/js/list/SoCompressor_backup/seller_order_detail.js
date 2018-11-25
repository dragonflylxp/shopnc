function select_input(obj){

    if($(obj).attr('id')=='d4'){

        $(obj).parent().siblings('#other_reason').show();

    }else{

        $(obj).parent().siblings('#other_reason').hide();

    }

}

$(function() {

    var r = getCookie("key");

    if (!r) {

        window.location.href =  WapUrl + "/tmpl/member/login.html";

    }

    $.getJSON(ApiUrl + "/index.php?con=seller_order&fun=order_info", {

        key: r,

        order_id: getQueryString("order_id")

    },

    function(t) {

        t.datas.order_info.ApiUrl = ApiUrl;

        $("#order-info-container").html(template.render("order-info-tmpl", t.datas.order_info));

        $(".cancel-order").click(e);

        $(".sure-order").click(o);

        $(".spay_price-order").click(n);

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

  

    function n() {

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

    function l() {

        var r = $(this).attr("order_id");

        location.href = WapSiteUrl + "/index.php?con=member_order&fun=deliver&order_id=" + r

    }



});