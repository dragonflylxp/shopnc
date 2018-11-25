$(function() {

    var key = getCookie('key');

    if (!key) {

        window.location.href =  WapUrl + "/tmpl/member/login.html";

        return;

    }



    //加载验证码

    loadSeccode();

    $("#refreshcode").bind('click',function(){

        loadSeccode();

    });



    $.sValid.init({

        rules:{

            rc_sn:"required",

            captcha:"required"

        },

        messages:{

            rc_sn:"请输入平台充值卡号",

            captcha:"请填写验证码"

        },

        callback:function (eId,eMsg,eRules){

            if(eId.length >0){

                var errorHtml = "";

                $.map(eMsg,function (idx,item){

                    errorHtml += "<p>"+idx+"</p>";

                });

               layer.open({

                    content:errorHtml,

                    time:1.5

                });

               

            }

        }

    });



    $('#saveform').click(function(){

        if (!$(this).parent().hasClass('ok')) {

            return false;

        }



        if($.sValid()){

            var rc_sn = $.trim($("#rc_sn").val());

            var captcha = $.trim($("#captcha").val());

            var codekey = $.trim($("#codekey").val());

            $.ajax({

                type:'post',

                url:ApiUrl+"/index.php?con=member_fund&fun=run_rechargecard_add",

                data:{key:key,rc_sn:rc_sn,captcha:captcha,codekey:codekey},

                dataType:'json',

                success:function(result){

                    if(result.code == 200){

                        layer.open({content:'兑换成功'});

                        setTimeout(" location.href =ApiUrl + '/index.php?con=member_fund'", 1e3)

                       

                    }else{

                        loadSeccode();

                        layer.open({

                            content:result.datas.error,

                            time:1.5

                        });

                       

                    }

                }

            });

        }

    });

});

