//20160906

$(function(){
    if (getQueryString("key") != "") {

        var a = getQueryString("key");

        addCookie("key", a);

    } else {

        var a = getCookie("key");

        

    }


    function initPage(){
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?con=member_invite&fun=maker_qrcode",
            data:{key:key},
            dataType:'json',
            success:function(result){
                //检测是否登录
                checkLogin(result.login);

                var data = result;
                var html = template.render('invite_code', data);
                $("#invite_code_list").html(html);

            }
        });
    }
initPage();
});
