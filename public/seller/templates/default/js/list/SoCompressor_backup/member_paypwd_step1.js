$(function() {

    var e = getCookie("key");

    if (!e) {

        window.location.href = WapUrl + "/tmpl/member/login.html";;

        return

    }

    loadSeccode();

    $("#refreshcode").bind("click",

    function() {

        loadSeccode()

    });



    $.sValid.init({

        rules: {

            captcha: {

                required: true,

                minlength: 4

            }

        },

        messages: {

            captcha: {

                required: "请填写图形验证码",

                minlength: "图形验证码不正确"

            }

        },

        callback: function(e, a, t) {

            if (e.length > 0) {

                var o = "";

                $.map(a,

                function(e, a) {

                    o += "<p>" + e + "</p>"

                });

                layer.open({

                    content:o,

                    time:1.5

                });

               

            }

        }

    });

    $("#send").click(function() {

        if ($.sValid()) {

            var a = $.trim($("#captcha").val());

            var t = $.trim($("#codekey").val());

            $.ajax({

                type: "post",

                url: ApiUrl + "/index.php?con=member_account&fun=modify_paypwd_step2",

                data: {

                    key: e,

                    captcha: a,

                    codekey: t

                },

                dataType: "json",

                success: function(e) {

                    if (e.code == 200) {

                        $("#send").hide();

                        $(".code-countdown").show().find("em").html(e.datas.sms_time);

                        layer.open({

                            content: '短信验证码已发出',

                            time:1.5

                        });

                        var a = setInterval(function() {

                            var e = $(".code-countdown").find("em");

                            var t = parseInt(e.html() - 1);

                            if (t == 0) {

                                $("#send").show();

                                $(".code-countdown").hide();

                                clearInterval(a);

                                $("#codeimage").attr("src", ApiUrl + "/index.php?con=seccode&fun=makecode&k=" + $("#codekey").val() + "&t=" + Math.random())

                            } else {

                                e.html(t)

                            }

                        },

                        1e3)

                    } else {

                       

                         layer.open({

                            content: e.datas.error,

                            time:1.5

                        });

                        $("#codeimage").attr("src", ApiUrl + "/index.php?con=seccode&fun=makecode&k=" + $("#codekey").val() + "&t=" + Math.random());

                        $("#captcha").val("")

                    }

                }

            })

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

                url: ApiUrl + "/index.php?con=member_account&fun=modify_paypwd_step3",

                data: {

                    key: e,

                    auth_code: a

                },

                dataType: "json",

                success: function(e) {

                    if (e.code == 200) {

                    

                        layer.open({type: 2,content:'手机验证成功，正在跳转'});

                        setTimeout("location.href = ApiUrl + '/index.php?con=member_account&fun=modify_paypwd_step4'", 1e3)

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