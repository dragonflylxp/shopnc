$(function() {
        function p() {
            $.ajax({
                url: ApiUrl + "/index.php?con=pointcart&fun=get_cart_list",
                type: "post",
                dataType: "json",
                data: {
                   
                },
                success: function(t) {
                    if (checkLogin(t.login)) {
                        if (!t.datas.error) {
                           
                            var a = t.datas;
                            a.WapSiteUrl = WapSiteUrl;
                            a.check_out = true;
                            template.helper("$getLocalTime",
                            function(t) {
                                var a = new Date(parseInt(t) * 1e3);
                                var e = "";
                                e += a.getFullYear() + "年";
                                e += a.getMonth() + 1 + "月";
                                e += a.getDate() + "日 ";
                                return e
                            });
                            var e = template.render("cart-list", a);
                        
                            $("#cart-list-wp").html(e);
                            $(".goods-del").click(function() {
                                var t = $(this).attr("cart_id");
                                layer.open({
                                    content: '确认删除吗？',
                                    btn: ['嗯', '不要'],
                                    yes: function(index){
                                        f(t);
                                        layer.close(index);
                                    }
                                });
                            });
                            $(".minus").click(h);
                            $(".add").click(g);
                            $(".buynum").blur(m);
                           
                            $(".store-activity").click(function() {
                                $(this).css("height", "auto")
                            })
                        } else {
                            layer.open({
                                content:t.msg,
                                time:1.5
                            })
                        }
                    }
                }
            })
        }
        p();
        function f(a) {
            $.ajax({
                url: ApiUrl + "/index.php?con=pointcart&fun=drop",
                type: "post",
                data: {
             
                    pc_id: a
                },
                dataType: "json",
                success: function(t) {
                 
                        if (t.done) {
                            p();
                      
                           
                        } else {
                            layer.open({
                                content:t.msg,
                                time:1.5
                            })
                        }
              
                }
            })
        }
        function h() {
            var t = this;
            _(t, "minus")
        }
        function g() {
            var t = this;
            _(t, "add")
        }
        function _(a, e) {
            var r = $(a).parents(".cart-litemw-cnt");
            var o = r.attr("cart_id");
            var i = r.find(".buy-num");
   
            var c = parseInt(i.val());
            var s = 1;
            if (e == "add") {
                s = parseInt(c + 1)
            } else {
                if (c > 1) {
                    s = parseInt(c - 1)
                } else {
                    return false
                }
            }
            $(".pre-loading").removeClass("hide");
            $.ajax({
                url: ApiUrl + "/index.php?con=pointcart&fun=update",
                type: "post",
                data: {
                  
                    pc_id: o,
                    quantity: s
                },
                dataType: "json",
                success: function(t) {
             
                        if (t.done) {
                            i.val(s);
                            
                            calculateTotalPrice()
                        } else {
                              layer.open({
                                    content:  t.msg,
                                    time:1.5
                                });
                        }
                        $(".pre-loading").addClass("hide")
               
                }
            })
        }
        $("#cart-list-wp").on("click", ".check-out > a",
        function() {
            if (!$(this).parent().hasClass("ok")) {
                return false
            }
            var t = [];
            $(".cart-litemw-cnt").each(function() {
                if ($(this).find('input[name="pcart_id"]').prop("checked")) {
                    var a = $(this).find('input[name="pcart_id"]').val();
                    var e = parseInt($(this).find(".value-box").find("input").val());
                    var r = a + "|" + e;
                    t.push(r)
                }
            });
            var a = t.toString();
            window.location.href = ApiUrl + "/index.php?con=pointcart&fun=step1&plist="+a;
        });
        $.sValid.init({
            rules: {
                buynum: "digits"
            },
            messages: {
                buynum: "请输入正确的数字"
            },
            callback: function(t, a, e) {
                if (t.length > 0) {
                    var r = "";
                    $.map(a,
                    function(t, a) {
                        r += "<p>" + t + "</p>"
                    });
                     layer.open({
                        content:  r,
                        time:1.5
                    });
                }
            }
        });
        function m() {
            $.sValid()
        }

    $("#cart-list-wp").on("click", ".store_checkbox",
    function() {
        $(this).parents(".nctouch-cart-container").find('input[name="pcart_id"]').prop("checked", $(this).prop("checked"));
        calculateTotalPrice()
    });
    $("#cart-list-wp").on("click", ".all_checkbox",
    function() {
        $("#cart-list-wp").find('input[type="checkbox"]').prop("checked", $(this).prop("checked"));
        calculateTotalPrice()
    });
    $("#cart-list-wp").on("click", 'input[name="pcart_id"]',
    function() {
        calculateTotalPrice()
    })
});
function calculateTotalPrice() {
    var t = parseInt("0");
    $(".cart-litemw-cnt").each(function() {
        if ($(this).find('input[name="pcart_id"]').prop("checked")) {
            t += parseFloat($(this).find(".goods-price").find("em").html()) * parseInt($(this).find(".value-box").find("input").val())
        }
    });
    $(".total-money").find("em").html(t);
    check_button();
    return true
}


function check_button() {
    var t = false;
    $('input[name="pcart_id"]').each(function() {
        if ($(this).prop("checked")) {
            t = true
        }
    });
    if (t) {
        $(".check-out").addClass("ok")
    } else {
        $(".check-out").removeClass("ok")
    }
}