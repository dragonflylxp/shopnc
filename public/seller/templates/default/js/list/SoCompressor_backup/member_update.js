$(function() {

    var e = getCookie("key");

    if (!e) {

        window.location.href =  WapUrl + "/tmpl/member/login.html";

        return

    }

   

   

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=member_account&fun=ajax_member_info",

        data: {

            key: e,

            info:'member_truename'

        },

        dataType: "json",

        success: function(data) {

            if(data.member_truename =='undefined'){

                $("#truename").val()

            }else{

                $("#truename").val(data.member_truename)

            }

            

        }

    });

    $.sValid.init({

        rules: {

            captcha: {

                required: true,

                minlength: 4

            },

            mobile: {

                required: true,

                mobile: true

            }

        },

        messages: {

            captcha: {

                required: "请填写图形验证码",

                minlength: "图形验证码不正确"

            },

            mobile: {

                required: "请填写手机号",

                mobile: "手机号码不正确"

            }

        },

        callback: function(e, a, t) {

            if (e.length > 0) {

                var o = "";

                $.map(a,

                function(e, a) {

                    o += "<p>" + e + "</p>"

                });

                errorTipsShow(o)

            } else {

                errorTipsHide()

            }

        }

    });



    $("#nextform").click(function() {

        if (!$(this).parent().hasClass("ok")) {

            return false

        }

        var a = $.trim($("#auth_code").val());

        if (a) {

            $.ajax({

                type: "post",

                url: ApiUrl + "/index.php?con=member_account&fun=bind_mobile_step2",

                data: {

                    key: e,

                    auth_code: a

                },

                dataType: "json",

                success: function(e) {

                    if (e.code == 200) {

                        layer.open({

                            content: '绑定成功',

                            time: 1.5

                        });

                        setTimeout("location.href = ApiUrl+'/index.php?con=member_account'", 2e3)

                    } else {

                        errorTipsShow("<p>" + e.datas.error + "</p>")

                    }

                }

            })

        }

    })

});