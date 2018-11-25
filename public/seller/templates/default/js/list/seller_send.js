TouchSlide( { slideCell:"#tabBox1",

	endFun:function(i){ //高度自适应
		var bd = document.getElementById("tabBox1-bd");
		bd.parentNode.style.height = bd.children[i].children[0].offsetHeight+"px";
		if(i>0)bd.parentNode.style.transition="200ms";//添加动画效果
	}

} );
function setAddress(order_id,daddress_id,obj){

		$(obj).addClass('acheck').siblings('a').removeClass('acheck');
		$('.layermcont').find('#daddress_id').val(daddress_id);
		$('.layermcont').find('#dorder_id').val(order_id);
}
$(function(){
	$('#shr_edit').click(function(){
	 var order_id = $(this).attr('order_id');
	 var update = layer.open({
         title: '收货人信息',
         content:$('.alert_box_hide').html(),
         btn: ['修改'],
         yes: function(index){
         	var reciver_name = $('.layermcont').find('#reciver_name').val();
            var reciver_area = $('.layermcont').find('#area').val();
            var reciver_street = $('.layermcont').find('#street').val();
            var reciver_mob_phone = $('.layermcont').find('#mob_phone').val();
            var reciver_tel_phone = $('.layermcont').find('#tel_phone').val();
          
            layer.open({type:2,content:"提交中..."});
              $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=seller_order&fun=buyer_address_save",
                data: {
                	order_id:order_id,
                    reciver_name: reciver_name,
                    reciver_area: reciver_area,
                    reciver_street:reciver_street,
                    reciver_mob_phone:reciver_mob_phone,
                    reciver_tel_phone:reciver_tel_phone
                },
                dataType: "json",
                async: false,
                success: function(e) {
                    if (e.code == 200) {
                        layer.open({
                            content:'更新成功!'
                        });
                         setTimeout(function () {
                            location.reload();
                            layer.close(index);
                        }, 1000);  
                       
                    } else {
                    
                        layer.open({
                            content:e.datas.error,
                            time:1.5
                         })
                        layer.close(index);
                    }
                }
            });
           

         }
      })
	})

	$('#select_daddress').click(function(){
	 var order_id = $(this).attr('order_id');
	 var update = layer.open({
         title: '选择发货地址',
         content:$('.fh_area').html(),
         btn: ['确定'],
         yes: function(index){

         	var daddress_id = $('.layermcont').find('#daddress_id').val();
            var dorder_id = $('.layermcont').find('#dorder_id').val();
            layer.open({type:2,content:"提交中..."});
              $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=seller_order&fun=send_address_save",
                data: {
                    daddress_id: daddress_id,
                    order_id: dorder_id
                },
                dataType: "json",
                async: false,
                success: function(e) {
                    if (e.code == 200) {
                        layer.open({
                            content:'更新成功!'
                        });
                         setTimeout(function () {
                            location.reload();
                            layer.close(index);
                        }, 1000);  
                       
                    } else {
                    
                        layer.open({
                            content:e.datas.error,
                            time:1.5
                         })
                        layer.close(index);
                    }
                }
            });
           

         }
      })
	})

})