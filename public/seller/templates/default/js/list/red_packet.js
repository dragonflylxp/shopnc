$(function (){ 
    var id = getQueryString("id");
    alert(id);
    if(!id){
    	layer.open({
    		content:'红包不存在!'
    	})
    }
    //渲染页面
    $.ajax({
       url:ApiUrl+"/index.php?con=red_packet&fun=getinfo",
       type:"get",
       data:{id:id},
       dataType:"json",
       success:function(result){
          var data = result.datas;
          if(data.error){
		
                layer.open({
				    content:  data.error + '~',
				    btn: ['去首页看看'],
				    shadeClose: false,
				    yes: function(){
				       location.href = WapSiteUrl;
				    }
				});
         
          }else{
			  //var html = template.render('packet_detail', data);
			  //$("packet_detail").html(html);
		  }
       }
    });

	$('#rush_get').click(function(){//领取红包
    
		
			$.ajax({
			   url:ApiUrl+"/index.php?con=member_redpacket&fun=getpack",
			   type:"get",
			   data:{id:id,key:key},
			   dataType:"json",
			   success:function(result){
				  var data = result.datas;
				  if(data.error){
					  //更改样式
					  document.getElementById('chaihongbao').style.display="none";
					  document.getElementById('fenxiang').style.display="block";
					  $.sDialog({
						content: data.error,
						okBtn:false,
						cancelBtnText:'查看我的红包',
						cancelFn: function() { location.href = WapSiteUrl+'/tmpl/member/redpacket_list.html'; }
					 });
				  }else{
					  //更改样式
					  document.getElementById('chaihongbao').style.display="none";
					  document.getElementById('fenxiang').style.display="block";
					  $.sDialog({
						content:'恭喜您获得'+data.packet_price+'元红包~',
						okBtn:false,
						cancelBtnText:'手气不错',
						cancelFn: function() { location.href = WapSiteUrl+'/tmpl/member/redpacket_list.html'; }
					 });
				  }
			   }
			});
	
	});

});