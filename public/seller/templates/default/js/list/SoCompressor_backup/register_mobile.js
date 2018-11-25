$(function() {
    loadSeccode();
    $("#refreshcode").bind("click",
    function() {
        loadSeccode()
    });
    $.sValid.init({
        rules: {
            usermobile: {
                required: true,
                mobile: true
            }
        },
        messages: {
            usermobile: {
                required: "请填写手机号！",
                mobile: "手机号码不正确"
            }
        },
        callback: function(e, i, r) {
            if (e.length > 0) {
                var l = "";
                $.map(i,
                function(e, i) {
                    l += "<p>" + e + "</p>"
                });
                layer.open({
                    content:l,
                    time:1.5
                })
            }
        }
    });
    $("#refister_mobile_btn").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if ($.sValid()) {
            $(this).attr("href",ApiUrl + "/index.php?con=register&fun=register_mobile_code&mobile=" + $("#usermobile").val() + "&captcha=" + $("#captcha").val() + "&codekey=" + $("#codekey").val())
        } else {
            return false
        }
    })
});