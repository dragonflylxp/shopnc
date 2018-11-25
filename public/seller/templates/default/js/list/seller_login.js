$(function() {
  

    $.sValid.init({
        rules: {
            username: "required",
            userpwd: "required"
        },
        messages: {
            username: "用户名必须填写！",
            userpwd: "密码必填!"
        },
        callback: function(e, r, a) {
            if (e.length > 0) {
                var i = "";
                $.map(r,
                function(e, r) {
                    i += "<p>" + e + "</p>"
                });
     
                layer.open({
                    content:i,
                    time:1.5
                })
            }
        }
    });
    var a = true;
    $("#loginbtn").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if (a) {
            a = false
        } else {
            return false
        }
        var e = $("#seller_name").val();
        var i = $("#password").val();
        var t = "wap";
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=seller_login&fun=runlogin",
                data: {
                    seller_name: e,
                    password: i,
                    client: t
                },
                dataType: "json",
                success: function(e) {
                    
                    a = true;
                    if (!e.datas.error) {
                        if (typeof e.datas.sellerkey == "undefined") {
                            return false
                        } else {
                            var i = 188;
                            
                            layer.open({
                                type: 2,
                                content: "登录成功，正在跳转..."
                            
                            });
                            addCookie("seller_name", e.datas.seller_name, i);
                            addCookie("store_name", e.datas.store_name, i);
                            addCookie("sellerkey", e.datas.sellerkey, i);
                            setTimeout(function () {
                                window.location.href = ApiUrl + "/index.php?con=seller_center&fun=index";   
                            }, 1500);  
                            
                        }

                       
                    } else {
                        layer.open({
                            content:e.datas.error,
                            time:1.5
                        })
                       
                    }
                }
            })
        }
    });

});