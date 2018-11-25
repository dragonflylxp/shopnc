$(function() {


    //加载验证码

    loadSeccode();

    $("#refreshcode").bind('click',function(){

        loadSeccode();

    });

    

    $.sValid.init({

        rules:{

            pwd_code:"required",

            captcha:"required"

        },

        messages:{

            pwd_code:"请填写红包卡密",

            captcha:"请填写验证码"

        },

        callback:function (eId,eMsg,eRules){

            if(eId.length >0){

                var errorHtml = "";

                $.map(eMsg,function (idx,item){

                    errorHtml += "<p>"+idx+"</p>";

                });
                 layer.open({

                        content:  errorHtml,

                        time: 1.5 

                    });
            

            }

        }

    });



    $('#saveform').click(function(){

        if (!$(this).parent().hasClass('ok')) {

            return false;

        }



        if($.sValid()){

            var pwd_code = $.trim($("#pwd_code").val());

            var captcha = $.trim($("#captcha").val());

            var codekey = $.trim($("#codekey").val());

            $.ajax({

                type:'post',

                url:ApiUrl+"/index.php?con=member_redpacket&fun=rp_pwex",

                data:{key:key,pwd_code:pwd_code,captcha:captcha,codekey:codekey},

                dataType:'json',

                success:function(result){

                    if(result.code == 200){
                         layer.open({
                                content:  '兑换成功!',
                                time: 1.5 
                            });
                        location.href = WapSiteUrl+'/index.php?con=member_redpacket';

                    }else{

                        loadSeccode();
                        layer.open({
                                content:  result.datas.error,
                                time: 1.5 
                            });
                       

                    }

                }

            });

        }

    });

});

