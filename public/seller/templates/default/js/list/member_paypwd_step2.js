$(function() {

    var e = getCookie("key");

    if (!e) {

        window.location.href = WapUrl + "/tmpl/member/login.html";;

        return

    }



    $.sValid.init({

        rules: {

            password: {

                required: true,

                minlength: 6,

                maxlength: 20

            },

            password1: {

                required: true,

                equalTo: "#password"

            }

        },

        messages: {

            password: {

                required: "请填写支付密码",

                minlength: "请正确填写支付密码",

                maxlength: "请正确填写支付密码"

            },

            password1: {

                required: "请填写确认密码",

                equalTo: "两次密码输入不一致"

            }

        },

        callback: function(e, r, a) {

            if (e.length > 0) {

                var t = "";

                $.map(r,

                function(e, r) {

                    t += "<p>" + e + "</p>"

                });

                layer.open({

                    content:t,

                    time:1.5

                });

            } 

        }

    });

    $("#nextform").click(function() {

        if (!$(this).parent().hasClass("ok")) {

            return false

        }

        if ($.sValid()) {

            var r = $.trim($("#password").val());

            var a = $.trim($("#password1").val());

            $.ajax({

                type: "post",

                url: ApiUrl + "/index.php?con=member_account&fun=modify_paypwd_step5",

                data: {

                    key: e,

                    password: r,

                    password1: a

                },

                dataType: "json",

                success: function(e) {

                    if (e.code == 200) {

                         layer.open({

                            content: '支付密码设置成功!',

                            time: 1.5

                        });

                        setTimeout("location.href = WapSiteUrl+'/index.php?con=member_account'", 2e3)

                    } else {

                       layer.open({

                            content: e.datas.error,

                            time:1.5

                        });

                    }

                }

            })

        }

    })

});