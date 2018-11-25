var key = getCookie("key");
var ifcart = getQueryString("ifcart");
if (ifcart == 1) {
    var cart_id = getQueryString("cart_id")
} else {
    var cart_id = getQueryString("goods_id") + "|" + getQueryString("buynum")
}
var pay_name = "online";
var invoice_id = 0;
var address_id, vat_hash, offpay_hash, offpay_hash_batch, voucher, pd_pay, password, fcode = "",
rcb_pay, rpt, payment_code;
var message = {};
var freight_hash, city_id, area_id;
var area_info;
var goods_id;
$(function() {
    $("#list-address-valve").click(function() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_address&fun=address_list",
            data: {
                key: key
            },
            dataType: "json",
            async: false,
            success: function(e) {
                checkLogin(e.login);
                if (e.datas.address_list == null) {
                    return false
                }
                var a = e.datas;
                address_id = a.address_id;
                var i = template.render("list-address-add-list-script", a);
                $("#list-address-add-list-ul").html(i)
            }
        })
    });
    $.animationLeft({
        valve: "#list-address-valve",
        wrapper: "#list-address-wrapper",
        scroll: "#list-address-scroll"
    });
    $("#list-address-add-list-ul").on("click", "li",
    function() {
        $(this).addClass("selected").siblings().removeClass("selected");
        eval("address_info = " + $(this).attr("data-param"));
        _init(address_info.address_id);
        $("#list-address-wrapper").find(".header-l > a").click()
    });
    $.animationLeft({
        valve: "#new-address-valve",
        wrapper: "#new-address-wrapper",
        scroll: ""
    });
    $.animationLeft({
        valve: "#select-payment-valve",
        wrapper: "#select-payment-wrapper",
        scroll: ""
    });
    $("#new-address-wrapper").on("click", "#varea_info",
    function() {
        $.areaSelected({
            success: function(e) {
                city_id = e.area_id_2 == 0 ? e.area_id_1: e.area_id_2;
                area_id = e.area_id;
                area_info = e.area_info;
                $("#varea_info").val(e.area_info)
            }
        })
    });
    $.animationLeft({
        valve: "#invoice-valve",
        wrapper: "#invoice-wrapper",
        scroll: ""
    });
    template.helper("isEmpty",
    function(e) {
        var a = true;
        $.each(e,
        function(e, i) {
            a = false;
            return false
        });
        return a
    });
    template.helper("pf",
    function(e) {
        return parseFloat(e) || 0
    });
    template.helper("p2f",
    function(e) {
        return (parseFloat(e) || 0).toFixed(2)
    });
    var _init = function(e) {
        var a = 0;
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_buy&fun=buy_step1",
            dataType: "json",
            data: {
                key: key,
                cart_id: cart_id,
                ifcart: ifcart,
                address_id: e 
            },
            success: function(e) {
                checkLogin(e.login);
                if (e.datas.error) {
                    layer.open({
                        content:e.datas.error,
                        time:1.5
                    })
                    return false
                }
                e.datas.ApiUrl = ApiUrl;
                var i = template.render("goods_list", e.datas);
                $("#deposit").html(i);
                if (fcode == "") {
                    for (var t in e.datas.store_cart_list) {
                        if (e.datas.store_cart_list[t].goods_list[0].is_fcode == "1") {
                            $("#container-fcode").removeClass("hide");
                            goods_id = e.datas.store_cart_list[t].goods_list[0].goods_id
                        }
                        break
                    }
                }
                $("#container-fcode").find(".submit").click(function() {
                    fcode = $("#fcode").val();
                    if (fcode == "") {

                        layer.open({
                            content:"请填写F码",
                            time:1.5
                        })

                        return false
                    }
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?con=member_buy&fun=check_fcode",
                        dataType: "json",
                        data: {
                            key: key,
                            goods_id: goods_id,
                            fcode: fcode
                        },
                        success: function(e) {
                            if (e.datas.error) {
                                 layer.open({
                                         content: e.datas.error,
                                        time:1.5
                                    })
                                return false
                            }

                            layer.open({
                                content:"验证成功",
                                time:1.5
                            })
                            $("#container-fcode").addClass("hide")
                        }
                    })
                });
                if ($.isEmptyObject(e.datas.address_info)) {

                    layer.open({
                        content: '请添加地址',
                        btn: ['嗯', '不要'],
                        yes: function(index){
                            $("#new-address-valve").click();
                            layer.close(index);
                        },no:function(index){
                            history.go( -1);
                             layer.close(index);
                        }
                    });
                    return false
                }
                if (typeof e.datas.inv_info.inv_id != "undefined") {
                    invoice_id = e.datas.inv_info.inv_id
                }
                $("#invContent").html(e.datas.inv_info.content);
                vat_hash = e.datas.vat_hash;
                freight_hash = e.datas.freight_hash;
                insertHtmlAddress(e.datas.address_info, e.datas.address_api);
                voucher = "";
                voucher_temp = [];
                for (var t in e.datas.store_cart_list) {
                    voucher_temp.push([e.datas.store_cart_list[t].store_voucher_info.voucher_t_id + "|" + t + "|" + e.datas.store_cart_list[t].store_voucher_info.voucher_price])
                }
                voucher = voucher_temp.join(",");
                for (var t in e.datas.store_final_total_list) {
                    $("#storeTotal" + t).html(e.datas.store_final_total_list[t]);
                    a += parseFloat(e.datas.store_final_total_list[t]);
                    message[t] = "";
                    $("#storeMessage" + t).on("change",
                    function() {
                        message[t] = $(this).val()
                    })
                }
                rcb_pay = 0;
                rpt = "";
                var s = 0;
                if (!$.isEmptyObject(e.datas.rpt_info)) {
                    $("#rptVessel").show();
                    var n = (parseFloat(e.datas.rpt_info.rpacket_limit) > 0 ? "满" + parseFloat(e.datas.rpt_info.rpacket_limit).toFixed(2) + "元，": "") + "优惠" + parseFloat(e.datas.rpt_info.rpacket_price).toFixed(2) + "元";
                    $("#rptInfo").html(n);
                    rcb_pay = 1;
                    s = parseFloat(e.datas.rpt_info.rpacket_price)
                } else {
                    $("#rptVessel").hide()
                }
                password = "";
                $("#useRPT").click(function() {
                    if ($(this).prop("checked")) {
                        rpt = e.datas.rpt_info.rpacket_t_id + "|" + parseFloat(e.datas.rpt_info.rpacket_price);
                        var i = a - s
                    } else {
                        rpt = "";
                        var i = a
                    }
                    if (i <= 0) {
                        i = 0
                    }
                    $("#totalPrice,#onlineTotal").html(i.toFixed(2))
                });
                var r = a - s;
                if (r <= 0) {
                    r = 0
                }
                $("#totalPrice,#onlineTotal").html(r.toFixed(2))
            }
        })
    };
  
    var insertHtmlAddress = function(e, a) {
        address_id = e.address_id;
      
        $("#true_name").html(e.true_name);
        $("#mob_phone").html(e.mob_phone);

        $("#address").html(e.area_info + e.address);
        area_id = e.area_id;
        city_id = e.city_id;
        if (a.content) {
            for (var i in a.content) {
                $("#storeFreight" + i).html(parseFloat(a.content[i]).toFixed(2))
            }
        }
        offpay_hash = a.offpay_hash;
        offpay_hash_batch = a.offpay_hash_batch;
        if (a.allow_offpay == 1) {
            $("#payment-offline").show()
        }
        if (!$.isEmptyObject(a.no_send_tpl_ids)) {
            $("#ToBuyStep2").parent().removeClass("ok");
            for (var t = 0; t < a.no_send_tpl_ids.length; t++) {
                $(".transportId" + a.no_send_tpl_ids[t]).show()
            }
        } else {
            $("#ToBuyStep2").parent().addClass("ok")
        }
    };

    rcb_pay = 0;
    pd_pay = 0;
    _init();
    $("#payment-online").click(function() {
        pay_name = "online";
        $("#select-payment-wrapper").find(".header-l > a").click();
        $("#select-payment-valve").find(".current-con").html("在线支付");
        $(this).addClass("sel").siblings().removeClass("sel")
    });
    $("#payment-offline").click(function() {
        pay_name = "offline";
        $("#select-payment-wrapper").find(".header-l > a").click();
        $("#select-payment-valve").find(".current-con").html("货到付款");
        $(this).addClass("sel").siblings().removeClass("sel")
    });
    $.sValid.init({
        rules: {
            vtrue_name: "required",
            vmob_phone: "mobile",
            varea_info: "required",
            vaddress: "required"
        },
        messages: {
            vtrue_name: "姓名必填！",
            vmob_phone: "手机号格式不正确！",
            varea_info: "地区必填！",
            vaddress: "街道必填！"
        },
        callback: function(e, a, i) {
            if (e.length > 0) {
                var t = "";
                $.map(a,
                function(e, a) {
                    t += "<p>" + e + "</p>"
                });
                layer.open({
                    content:t,
                    time:1.5
                });
            
            }
        }
    });
    $("#add_address_form").find(".btn").click(function() {
        if ($.sValid()) {
            var e = {};
            e.key = key;
            e.true_name = $("#vtrue_name").val();
            e.mob_phone = $("#vmob_phone").val();
            e.address = $("#vaddress").val();
            e.city_id = city_id;
            e.area_id = area_id;
            e.area_info = $("#varea_info").val();
            e.is_default = 0;
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=member_address&fun=address_add",
                data: e,
                dataType: "json",
                success: function(a) {
                    if (!a.datas.error) {
                        e.address_id = a.datas.address_id;
                        _init(e.address_id);
                        $("#new-address-wrapper,#list-address-wrapper").find(".header-l > a").click()
                    }
                }
            })
        }
    });
    $("#invoice-noneed").click(function() {
        $(this).addClass("sel").siblings().removeClass("sel");
        $("#invoice_add,#invoice-list").hide();
        invoice_id = 0
    });
    $("#invoice-need").click(function() {
        $(this).addClass("sel").siblings().removeClass("sel");
        $("#invoice-list").show();
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_invoice&fun=invoice_content_list",
            data: {
                key: key
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                var a = e.datas;
                var i = "";
                $.each(a.invoice_content_list,
                function(e, a) {
                    i += '<option value="' + a + '">' + a + "</option>"
                });
                $("#inc_content").append(i)
            }
        });
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_invoice&fun=invoice_list",
            data: {
                key: key
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                var a = template.render("invoice-list-script", e.datas);
                $("#invoice-list").html(a);
                if (e.datas.invoice_list.length > 0) {
                    invoice_id = e.datas.invoice_list[0].inv_id
                }
                $(".del-invoice").click(function() {
                    var e = $(this);
                    var a = $(this).attr("inv_id");
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?con=member_invoice&fun=invoice_del",
                        data: {
                            key: key,
                            inv_id: a
                        },
                        success: function(a) {
                            if (a) {
                                e.parents("label").remove()
                            }
                            return false
                        }
                    })
                })
            }
        })
    });
    $('input[name="inv_title_select"]').click(function() {
        if ($(this).val() == "person") {
            $("#inv-title-li").hide()
        } else {
            $("#inv-title-li").show()
        }
    });
    $("#invoice-div").on("click", "#invoiceNew",
    function() {
        invoice_id = 0;
        $("#invoice_add,#invoice-list").show()
    });
    $("#invoice-list").on("click", "label",
    function() {
        invoice_id = $(this).find("input").val()
    });
    $("#invoice-div").find(".btn-l").click(function() {
        if ($("#invoice-need").hasClass("sel")) {
            if (invoice_id == 0) {
                var e = {};
                e.key = key;
                e.inv_title_select = $('input[name="inv_title_select"]:checked').val();
                e.inv_title = $("input[name=inv_title]").val();
                e.inv_content = $("select[name=inv_content]").val();
                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?con=member_invoice&fun=invoice_add",
                    data: e,
                    dataType: "json",
                    success: function(e) {
                        if (e.datas.inv_id > 0) {
                            invoice_id = e.datas.inv_id
                        }
                    }
                });
                $("#invContent").html(e.inv_title + " " + e.inv_content)
            } else {
                $("#invContent").html($("#inv_" + invoice_id).html())
            }
        } else {
            $("#invContent").html("不需要发票")
        }
        $("#invoice-wrapper").find(".header-l > a").click()
    });
    $("#ToBuyStep2").click(function() {
        var e = "";
        for (var a in message) {
            e += a + "|" + message[a] + ","
        }
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_buy&fun=buy_step2",
            data: {
                key: key,
                ifcart: ifcart,
                cart_id: cart_id,
                address_id: address_id,
                vat_hash: vat_hash,
                offpay_hash: offpay_hash,
                offpay_hash_batch: offpay_hash_batch,
                pay_name: pay_name,
                invoice_id: invoice_id,
                voucher: voucher,
                pd_pay: pd_pay,
                password: password,
                fcode: fcode,
                rcb_pay: rcb_pay,
                rpt: rpt,
                pay_message: e
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                if (e.datas.error) {
                   
                    layer.open({
                        content: e.datas.error,
                        time: 1.5 
                    });
                    return false
                }
                if (e.datas.payment_code == "offline") {
                    window.location.href = WapSiteUrl + "/index.php?con=member_order&fun=index"
                } else {
                    delCookie("cart_count");
                    toPay(e.datas.pay_sn, "member_buy", "pay")
                }
            }
        })
    })
});