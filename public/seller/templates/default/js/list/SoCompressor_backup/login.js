$(function() {
  
    var r = document.referrer;
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
        var e = $("#username").val();
        var i = $("#userpwd").val();
        var t = "wap";
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=login&fun=runlogin",
                data: {
                    username: e,
                    password: i,
                    client: t
                },
                dataType: "json",
                success: function(e) {
                    
                    a = true;
                    if (!e.datas.error) {
                        if (typeof e.datas.key == "undefined") {
                            return false
                        } else {
                            var i = 0;
                            if ($("#checkbox").prop("checked")) {
                                i = 188
                            }
                            layer.open({
                                type: 2,
                                content: "登录成功，正在跳转..."
                            
                            });
                            updateCookieCart(e.datas.key);
                            addCookie("username", e.datas.username, i);
                            addCookie("key", e.datas.key, i);
                            setTimeout(function () {
                                window.location.href = r;   
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
    $(".weibo").click(function() {
        location.href = ApiUrl + "/index.php?con=connect&fun=get_sina_oauth2"
    });
    $(".qq").click(function() {
        location.href = ApiUrl + "/index.php?con=connect&fun=get_qq_oauth2"
    })

    $(".weixin").click(function() {
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            window.location.href=ApiUrl+"/index.php?con=auto&fun=login&ref="+encodeURIComponent(window.location.href);
        }else{
            layer.open({
                content:'请在微信中使用微信登陆!',
                time:2
            });
        }
        
    })
});