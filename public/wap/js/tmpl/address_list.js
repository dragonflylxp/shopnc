$(function(){
   // 根据querystring是否带有唯一agencyId/merchantId发起联合注册或登录请求
   var agencyId = getQueryString("agencyId"); 
   var merchantId = getQueryString("merchantId"); 
   if (agencyId != null && agencyId != "" && merchantId != null && merchantId != "") {
       comlog(agencyId, merchantId, initPage);
   }
   else{
       initPage();
   }
});
		
	//初始化列表
	function initPage(){
	        var key = getCookie('key');
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?con=member_address&fun=address_list",	
			data:{key:key},
			dataType:'json',
			success:function(result){
				checkLogin(result.login);
				if(result.datas.address_list==null){
					return false;
				}
				var data = result.datas;
				var html = template.render('saddress_list', data);
				$("#address_list").empty();
				$("#address_list").append(html);
				//点击删除地址
				$('.deladdress').click(function(){
				    var address_id = $(this).attr('address_id');
	                $.sDialog({
	                    skin:"block",
	                    content:'确认删除吗？',
	                    okBtn:true,
	                    cancelBtn:true,
	                    okFn: function() {
	                        delAddress(address_id);
	                    }
	                });
				});
			}
		});
	}
	//点击删除地址
	function delAddress(address_id){
                var key = getCookie('key');
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?con=member_address&fun=address_del",
			data:{address_id:address_id,key:key},
			dataType:'json',
			success:function(result){
				checkLogin(result.login);
				if(result){
					initPage();
				}
			}
		});
	}
