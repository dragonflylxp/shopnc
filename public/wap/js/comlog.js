/**
 * 联合登录
 *   @merchantId: 跳转携带的商户id
 *   @callbak: 登录之后的回调逻辑
 */ 

function comlog(agencyId, merchantId, callback){
    // 记录旧的agencyid/marchantid
    var agencyid = getCookie("agencyid");
    var merchantid = getCookie("merchantid");
    // 刷新agencyid/merchantid cookie
    addCookie('agencyid',agencyId);
    addCookie('merchantid',merchantId);
    var key = getCookie("key");
    if (parseInt(agencyId)>0 && parseInt(merchantId) > 0){
        if (agencyId != agencyid || merchantId != merchantid){
            // 联合登录
            $.ajax({
               url: ApiUrl + "/index.php?con=login&fun=comlogin",
               type: 'post',
               dataType: 'json',
               timeout:5000,
               data:{agencyid:agencyId,merchantid:merchantId,key:key,client:'wap'},
               success: function(result) {
                   if(!result.datas.error){
                       if(typeof(result.datas.key)=='undefined'){
                           delCookie('key')
                           delCookie('username')
                           addCookie('agencyid',"-1");
                           addCookie('merchantid',"-1");
                       }else{
                           // 更新cookie购物车
                           updateCookieCart(result.datas.key);
                           addCookie('username',result.datas.username);
                           addCookie('key',result.datas.key);
                          
                       }
                   }else{
                       delCookie('key')
                       delCookie('username')
                       addCookie('agencyid',"-1");
                       addCookie('merchantid',"-1");
                       errorTipsShow("<p>"+result.datas.error+"</p>");
                   }
                   // 登录返回之后回调后续逻辑  
                   callback();

               },

               complete: function (XMLHttpRequest, textStatus) {
                   if(textStatus == 'timeout'){
                       delCookie('key')
                       delCookie('username')
                       addCookie('agencyid',"-1");
                       addCookie('merchantid',"-1");
                       errorTipsShow("<p>联合登录超时</p>");
                   }
                   // 登录返回之后回调后续逻辑  
                   callback();
               },

               error: function (XMLHttpRequest, textStatus) {
                   delCookie('key')
                   delCookie('username')
                   addCookie('agencyid',"-1");
                   addCookie('merchantid',"-1");
                   errorTipsShow("<p>联合登录出错</p>");
                   // 登录返回之后回调后续逻辑  
                   callback();
               }
            });
        }
        else {
            if (key == null || key == ""){
                delCookie('key')
                delCookie('username')
                addCookie('agencyid',"-1");
                addCookie('merchantid',"-1");
            }
            // 登录返回之后回调后续逻辑  
            callback();
        }
    } else {
        if (key != null && key != ""){
            // 联合注销 
            $.ajax({
               url: ApiUrl + "/index.php?con=logout&fun=comlogout",
               type: 'post',
               dataType: 'json',
               data:{agencyid:agencyid,merchantid:merchantid,key:key,client:'wap'},
               success: function(result) {
                   if(!result.datas.error){
                       delCookie('key')
                       delCookie('username')
                       addCookie('agencyid',"-1");
                       addCookie('merchantid',"-1");
                   }else{
                       errorTipsShow("<p>"+result.datas.error+"</p>");
                   }
                   // 注销返回之后回调后续逻辑  
                   callback();
               }
            });
        }
        else{
            delCookie('key')
            delCookie('username')
            addCookie('agencyid',"-1");
            addCookie('merchantid',"-1");
            // 注销返回之后回调后续逻辑  
            callback();
        }
   }
}
