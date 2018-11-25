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

    $("#email").on("blur",function() {



        if ($(this).val() != "" && !/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/.test($(this).val())) {

            layer.open({

                content:'邮箱格式不正确!'

            });

        }

    });

    

    $.sValid.init({

        rules: {

            captcha: {

                required: true,

                minlength: 4

            },

            email: {

                required: true,

                email: true

            }

        },

        messages: {

            captcha: {

                required: "请填写图形验证码",

                minlength: "图形验证码不正确"

            },

            email: {

                required: "请填写邮箱",

                email: "邮箱不正确"

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

            var a = $.trim($("#email").val());

            var t = $.trim($("#captcha").val());

            var o = $.trim($("#codekey").val());

            $.ajax({

                type: "post",

                url: ApiUrl + "/index.php?con=member_account&fun=bind_email_step1",

                data: {

                    key: e,

                    email: a,

                    captcha: t,

                    codekey: o

                },

                dataType: "json",

                success: function(e) {

                    if (e.code == 200) {

                        $("#send").hide();

                        $("#auth_code").removeAttr("readonly");

                        $(".code-countdown").show().find("em").html(e.datas.sms_time);

                        layer.open({

                            content: '验证码已发出',

                            time:1.5

                        });

                        var a = setInterval(function() {

                            var e = $(".code-countdown").find("em");

                            var t = parseInt(e.html() - 1);

                            if (t == 0) {

                                $("#send").show();

                                $(".code-countdown").hide();

                                clearInterval(a);

                                $("#codeimage").attr("src", ApiUrl + "/index.php?con=seccode&fun=makecode&k=" + $("#codekey").val() + "&t=" + Math.random());

                                $("#captcha").val("")

                            } else {

                                e.html(t)

                            }

                        },

                        1e3)

                    } else {

                        if(e.datas.error.status ==1){

                            //询问框

                                layer.open({

                                    content: '你已经绑定过邮箱，需要解绑嘛？',

                                    btn: ['嗯', '不要'],

                                    yes: function(index){

                                        window.location.href = ApiUrl + "/index.php?con=member_account&fun=member_email_modify";

                                    },no:function(index){

                                         layer.close(index);

                                    }

                                });

                        }else{

                            layer.open({

                            content: e.datas.error,

                            time:1.5

                            }); 

                        }

                        

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

        var em = $.trim($("#email").val());

        if (a) {

            $.ajax({

                type: "post",

                url: ApiUrl + "/index.php?con=member_account&fun=bind_email_step2",

                data: {

                    key: e,

                    auth_code: a,

                    email:em



                },

                dataType: "json",

                success: function(e) {

                    if (e.code == 200) {

                         layer.open({

                            content: '绑定成功',

                            time:1.5

                        });

                        setTimeout("location.href = ApiUrl + '/index.php?con=member_account'", 2e3)

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