$(function(){
    var mobile = getQueryString("mobile");
    var captcha = getQueryString("captcha");
    
    // 显示密码
    $('#checkbox').click(function(){
        if ($(this).prop('checked')) {
            $('#password').attr('type', 'text');
            $('#password_confirm').attr('type', 'text');
        } else {
            $('#password').attr('type', 'password');
            $('#password_confirm').attr('type', 'password');
        }
    });

    $.sValid.init({//注册验证
        rules:{
            password: {
                required:true,
                minlength:6,
                maxlength:20
            },
            password_confirm: {
                required:true,
                equalTo:'#password'
            }
        },
        messages:{
            password: {
                required:'密码不能为空',
                minlength:'密码长度应在6-20个字符之间',
                maxlength:'密码长度应在6-20个字符之间'
            },
            password_confirm: {
                required:'确认密码不能为空',
                equalTo:'两次输入的密码不一致'
            }
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide()
            }
        }  
    });
    
    $('#completebtn').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
        var username = $("#username").val();
        var password = $("#password").val();
        var referral_code = $("#referral_code").val();
        if($.sValid()){
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?con=connect&fun=sms_register",  
                data:{phone:mobile, captcha:captcha,username:username,referral_code:referral_code, password:password, client:'wap'},
                dataType:'json',
                success:function(result){
                    if(!result.datas.error){
                        addCookie('username',result.datas.username);
                        addCookie('key',result.datas.key);
                        location.href = WapSiteUrl + '/tmpl/member/member.html';
                    }else{
                        errorTipsShow("<p>"+result.datas.error+"</p>");
                    }
                }
            });         
        }
    });
});


