$(function() {
    var e = getQueryString("email");
    var a = getQueryString("captcha");
    $("#checkbox").click(function() {
        if ($(this).prop("checked")) {
            $("#password").attr("type", "text")
        } else {
            $("#password").attr("type", "password")
        }
    });
    $.sValid.init({
        rules: {
            password: "required"
        },
        messages: {
            password: "密码必填!"
        },
        callback: function(e, a, r) {
            if (e.length > 0) {
                var s = "";
                $.map(a,
                function(e, a) {
                    s += "<p>" + e + "</p>"
                });
                layer.open({
                    content:s,
                    time:1.5
                })
            
            } 
        }
    });
    $("#completebtn").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        var r = $("#password").val();
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=connect&fun=email_register",
                data: {
                    email: e,
                    captcha: a,
                    password: r,
                    client: "wap"
                },
                dataType: "json",
                success: function(e) {
                    if (!e.datas.error) {
                       // addCookie("username", e.datas.username);
                       // addCookie("key", e.datas.key);
                        location.href = ApiUrl + "/index.php?con=member"
                    } else {
                         layer.open({
                            content:e.datas.er,
                            time:1.5
                        }) 
                    }
                }
            })
        }
    })
});