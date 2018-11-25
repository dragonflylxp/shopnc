$(function(){
		var key = getCookie('key');
		if(key==''){
			location.href = 'login.html';
		}
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?con=get_qrcode",	
			data:{key:key},
			dataType:'json',
			//jsonp:'callback',
			success:function(result){
				if(result.datas.error){
                                            $.sDialog({
                                                skin: "red",
                                                content: result.datas.error,
                                                okBtn: false,
                                                cancelBtn: false
                                            });					
				}
				
				
				
				
				checkLogin(result.login);
				//$('#username').html(result.datas.member_info.user_name);
				//$('#point').html(result.datas.member_info.point);
				//$('#predepoit').html(result.datas.member_info.predepoit);
				if(result.datas.qrcode_img)
				{
					$('#qrcode').attr("src",result.datas.qrcode_img);
				}
				else
			    {
					$('#qrcode').css("display","none");
					$("#qrcode_img_msg").html(result.datas.qrcode_img_msg)
				}
				
				//$("#count1").html(result.datas.count1);
				//$("#count2").html(result.datas.count2);
				//$("#count3").html(result.datas.count3);
				//$("#total_count").html(result.datas.total_count);
				return false;
			}
		});
});