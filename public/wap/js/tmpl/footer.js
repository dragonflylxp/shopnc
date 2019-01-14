$(function (){
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    var html = '<div class="nctouch-footer-wrap posr">'
        +'<div class="nav-text">';

    var agencyid = getCookie('agencyid');
    var merchantid = getCookie('merchantid');
    if(agencyid && merchantid){
        html += '<a href="'+WapSiteUrl+'/tmpl/member/member.html">我的商城</a>'
             + '<a href="'+SellerUrl+'/index.php?con=seller_center&fun=index">商家管理</a>'
             + '<a href="'+WapSiteUrl+'/tmpl/member/member_feedback.html">反馈</a>';
    }else{

        if(key){
            html += '<a href="'+WapSiteUrl+'/tmpl/member/member.html">我的商城</a>'
                 + '<a id="logoutbtn" href="javascript:void(0);">注销</a>'
                 + '<a href="'+SellerUrl+'/index.php?con=seller_center&fun=index">商家管理</a>'
                 + '<a href="'+WapSiteUrl+'/tmpl/member/member_feedback.html">反馈</a>';
        } else {
            html += '<a href="'+WapSiteUrl+'/tmpl/member/login.html">登录</a>'
                 + '<a href="'+WapSiteUrl+'/tmpl/member/register.html">注册</a>'
                 + '<a href="'+SellerUrl+'/index.php?con=seller_center&fun=index">商家管理</a>'
                 + '<a href="'+WapSiteUrl+'/tmpl/member/login.html">反馈</a>';
        }
    }
    html += '<a href="javascript:void(0);" class="gotop">返回顶部</a>'
        +'</div>'
        +'<div class="nav-pic">'
			+'<a href="'+SiteUrl+'/index.php?con=mb_app" class="app"><span><i></i></span><p>客户端</p></a>'
			+'<a href="javascript:void(0);" class="touch"><span><i></i></span><p>触屏版</p></a>'
            +'<a href="'+SiteUrl+'/index.php?pc=1" class="pc"><span><i></i></span><p>电脑版</p></a>'
         +'</div>'
		 +'<div class="copyright">'
    	 +'</div>';
	$("#footer").html(html);
    var key = getCookie('key');
	$('#logoutbtn').click(function(){
		var username = getCookie('username');
		var key = getCookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?con=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					location.href = WapSiteUrl;
				}
			}
		});
	});
});
