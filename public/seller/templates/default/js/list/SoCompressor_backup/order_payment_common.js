var key = getCookie("key");
var password, rcb_pay, pd_pay, payment_code;
function toPay(a, e, p) {
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?con=" + e + "&fun=" + p,
        data: {
            key: key,
            pay_sn: a
        },
        dataType: "json",
        success: function(p) {
            checkLogin(p.login);
            if (p.datas.error) {
                 layer.open({
                     content: p.datas.error,
                    time:1.5
                })
                return false
            }
            $.animationUp({
                valve: "",
                scroll: ""
            });
            $("#onlineTotal").html(p.datas.pay_info.pay_amount);
            if (!p.datas.pay_info.member_paypwd) {
                $("#wrapperPaymentPassword").find(".input-box-help").show()
            }
            var s = false;
            if (parseFloat(p.datas.pay_info.payed_amount) <= 0) {
                if (parseFloat(p.datas.pay_info.member_available_pd) == 0 && parseFloat(p.datas.pay_info.member_available_rcb) == 0) {
                    $("#internalPay").hide()
                } else {
                    $("#internalPay").show();
                    if (parseFloat(p.datas.pay_info.member_available_rcb) != 0) {
                        $("#wrapperUseRCBpay").show();
                        $("#availableRcBalance").html(parseFloat(p.datas.pay_info.member_available_rcb).toFixed(2))
                    } else {
                        $("#wrapperUseRCBpay").hide()
                    }
                    if (parseFloat(p.datas.pay_info.member_available_pd) != 0) {
                        $("#wrapperUsePDpy").show();
                        $("#availablePredeposit").html(parseFloat(p.datas.pay_info.member_available_pd).toFixed(2))
                    } else {
                        $("#wrapperUsePDpy").hide()
                    }
                }
            } else {
                $("#internalPay").hide()
            }
            password = "";
            $("#paymentPassword").on("change",
            function() {
                password = $(this).val()
            });
            rcb_pay = 0;
            $("#useRCBpay").click(function() {
                if ($(this).prop("checked")) {
                    s = true;
                    $("#wrapperPaymentPassword").show();
                    rcb_pay = 1
                } else {
                    if (pd_pay == 1) {
                        s = true;
                        $("#wrapperPaymentPassword").show()
                    } else {
                        s = false;
                        $("#wrapperPaymentPassword").hide()
                    }
                    rcb_pay = 0
                }
            });
            pd_pay = 0;
            $("#usePDpy").click(function() {
                if ($(this).prop("checked")) {
                    s = true;
                    $("#wrapperPaymentPassword").show();
                    pd_pay = 1
                } else {
                    if (rcb_pay == 1) {
                        s = true;
                        $("#wrapperPaymentPassword").show()
                    } else {
                        s = false;
                        $("#wrapperPaymentPassword").hide()
                    }
                    pd_pay = 0
                }
            });
            payment_code = "";
            if (!$.isEmptyObject(p.datas.pay_info.payment_list)) {
                var t = false;
                var r = false;
                var n = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);
                if (parseInt(n && n[1] || 0) >= 5) {
                    t = true
                } else {
                    r = true
                }
                for (var o = 0; o < p.datas.pay_info.payment_list.length; o++) {
                    var i = p.datas.pay_info.payment_list[o].payment_code;
                    if (i == "alipay" && r) {
                        $("#" + i).parents("label").show();
                        if (payment_code == "") {
                            payment_code = i;
                            $("#" + i).attr("checked", true).parents("label").addClass("checked")
                        }
                    }
                    if (i == "wxpay_jsapi" && t) {
                        $("#" + i).parents("label").show();
                        if (payment_code == "") {
                            payment_code = i;
                            $("#" + i).attr("checked", true).parents("label").addClass("checked")
                        }
                    }
                    if (i == "tenpay" && r) {
                        $("#" + i).parents("label").show();
                        if (payment_code == "") {
                            payment_code = i;
                            $("#" + i).attr("checked", true).parents("label").addClass("checked")
                        }
                    }
                    if (i == "jdpay" && r) {
                        $("#" + i).parents("label").show();
                        if (payment_code == "") {
                            payment_code = i;
                            $("#" + i).attr("checked", true).parents("label").addClass("checked")
                        }
                    }
                }
            }
            $("#alipay").click(function() {
                payment_code = "alipay"
            });
            $("#tenpay").click(function() {
                payment_code = "tenpay"
            });
            $("#wxpay_jsapi").click(function() {
                payment_code = "wxpay_jsapi"
            });
            $("#jdpay").click(function() {
                payment_code = "jdpay"
            });
            $("#toPay").click(function() {
                if (payment_code == "") {
                    layer.open({
                        content:"请选择支付方式",
                        time:1.5
                    })
                    return false
                }
                if (s) {
                    if (password == "") {
                          layer.open({
                                content:"请填写支付密码",
                                time:1.5
                            })
                        return false
                    }
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?con=member_buy&fun=check_pd_pwd",
                        dataType: "json",
                        data: {
                            key: key,
                            password: password
                        },
                        success: function(p) {
                            if (p.datas.error) {
                                layer.open({
                                    content:p.datas.error,
                                    time:1.5
                                })
                                return false
                            }
                            goToPayment(a, e == "member_buy" ? "pay_new": "vr_pay_new")
                        }
                    })
                } else {
                   
                    if(e=="member_fund"){
                         goToPayment(a, e = "pd_pay");
                    }else{
                         goToPayment(a, e == "member_buy" ? "pay_new": "vr_pay_new");
                    }
                   
                }
            })
        }
    })
}
function goToPayment(a, e) {
    if(e=='pd_pay'){
        location.href = ApiUrl + "/index.php?con=member_payment&fun=" + e + "&key=" + key + "&pay_sn=" + a + "&payment_code=" + payment_code
    }else{
        location.href = ApiUrl + "/index.php?con=member_payment&fun=" + e + "&key=" + key + "&pay_sn=" + a + "&password=" + password + "&rcb_pay=" + rcb_pay + "&pd_pay=" + pd_pay + "&payment_code=" + payment_code  
    }
    
}