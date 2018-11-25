$(function () {
    loadSeccode();
    $("#refreshcode").bind("click", function () {
        loadSeccode()
    });
    var e = getQueryString("mobile");
    var c = getQueryString("captcha");
    var a = getQueryString("codekey");
    $("#usermobile").html(e);
    send_sms(e, c, a);
    $("#again").click(function () {
        c = $("#captcha").val();
        a = $("#codekey").val();
        send_sms(e, c, a)
    });
    $("#register_mobile_password").click(function () {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        var c = $("#mobilecode").val();
        if (c.length == 0) {
            layer.open({
                content:'请填写验证码',
                time:1.5
            })
        }
        check_sms_captcha(e, c);
        return false
    })
});

function send_sms(e, c, a) {
    $.getJSON(ApiUrl + "/index.php?con=connect&fun=get_sms_captcha", {
        type: 1,
        phone: e,
        sec_val: c,
        sec_key: a
    }, function (e) {
        if (!e.datas.error) {
             layer.open({
                content:'发送成功',
                time:1.5
            })
            $(".code-again").hide();
            $(".code-countdown").show().find("em").html(e.datas.sms_time);
            var c = setInterval(function () {
                var e = $(".code-countdown").find("em");
                var a = parseInt(e.html() - 1);
                if (a == 0) {
                    $(".code-again").show();
                    $(".code-countdown").hide();
                    clearInterval(c)
                } else {
                    e.html(a)
                }
            }, 1e3)
        } else {
            loadSeccode();
            layer.open({
                content:e.datas.error,
                time:1.5
            })
           
        }
    })
}

function check_sms_captcha(e, c) {
    $.getJSON(ApiUrl + "/index.php?con=connect&fun=check_sms_captcha", {
        type: 1,
        phone: e,
        captcha: c
    }, function (a) {
        if (!a.datas.error) {
            window.location.href = ApiUrl + "/index.php?con=register&fun=register_mobile_password&mobile=" + e + "&captcha=" + c
        } else {
            loadSeccode();
            layer.open({
                content:a.datas.error,
                time:1.5
            })
           
        }
    })
}

