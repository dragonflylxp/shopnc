$(function(){
	$(".gift_nav label").click(function(){		
		$(".gift_nav label").removeClass("giftNow");
		$(this).addClass("giftNow");
		id=$(this).attr("id").replace("labels_","");;
		$("div.gift_list").addClass("none");
		$("#gift_"+id).removeClass("none");
		$("#type_"+id+"_0").removeClass("none");
		lable=0;
		});
	$("li.ptChoseGift").click(function(){
		$("li.ptChoseGift").removeClass("giftselected");
		$(this).addClass("giftselected");
		});
	$(".giftpopLeft").click(function(){
		id=$(this).parent().attr("id").replace("gift_","");
		if((lable-1)<0){}
		else{
			$("#type_"+id+"_"+lable).addClass("none");
			lable=lable-1;
			$("#type_"+id+"_"+lable).removeClass("none");			
			}		
		});
	$(".giftpopRight").click(function(){
		id=$(this).parent().attr("id").replace("gift_","");
		countid=$(this).parent().children().length-2;
		//countid=$(this).prev().attr("id").replace("gift_","");
		if((lable+1)>=countid){}
		else{
			$("#type_"+id+"_"+lable).addClass("none");
			lable=lable+1;
			$("#type_"+id+"_"+lable).removeClass("none");	
			}		
		});
	$("li.ptChoseGift").hover(
		function(){
			var t = $(this).offset().top;
			var l = $(this).offset().left;
			txt=$(this).attr("tip");
			$("#needCoinNum").css("display","block");
			$("#needCoinNum").css("left",l-290);
			$("#needCoinNum").css("top",t-160);
			$("#needCoinNum").html(txt);
			},function(){
				$("#needCoinNum").css("display","none");
				}
	);
	$(".zb_title label").click(function(){		
		$(".zb_title label").removeClass("msgNow");
		$(this).addClass("msgNow");
		id=$(this).attr("id").replace("label_","");;
		$("#msg1").addClass("none");$("#msg2").addClass("none");$("#msg3").addClass("none");$("#msg4").addClass("none");
		$("#msg"+id).removeClass("none");
		});
});
function showlabel(id){
	if("undefined" == typeof giftlable){ var giftlable=0;$(".monday_gift").addClass("none"); $("#type_"+id+"_"+giftlable).removeClass("none");}
	$(".gift_list").addClass("none");
	$(".gift_list").addClass("none");
	$("#gift_"+id).removeClass("none");
	giftlable=0;
	$("#type_"+id+"_0").removeClass("none");
	$(".gift_nav label").removeClass("giftNow");
	$("#labels_"+id).addClass("giftNow");
	}
function labeljian(id){
	if("undefined" == typeof giftlable){ var giftlable=0;var giftlable=0;$(".monday_gift").addClass("none"); $("#type_"+id+"_"+giftlable).removeClass("none");}
	if(giftlable-1>=0){		
		$("#type_"+id+"_"+giftlable).addClass("none");
		giftlable=giftlable-1;
		$("#type_"+id+"_"+giftlable).removeClass("none");
		}
	}
function labeljia(id,anum){
	if("undefined" == typeof giftlable ){ var giftlable=0;var giftlable=0;$(".monday_gift").addClass("none"); $("#type_"+id+"_"+giftlable).removeClass("none");}
	if(giftlable+1<=Math.ceil((anum-1)/14)){
		$("#type_"+id+"_"+giftlable).addClass("none");
		giftlable=giftlable+1;
		$("#type_"+id+"_"+giftlable).removeClass("none");
		}
	}