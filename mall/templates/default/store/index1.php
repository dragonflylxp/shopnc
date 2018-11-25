<?php defined('Inshopec') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/store_css/base.css" />	
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/store_css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/store_css/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/store_css/purebox.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/store_css/quickLinks.css" />

<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/store_css/other/store_css.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/store_css/preview.css" />

<?php if(!$output['store_decoration_only']) {?>
	<input type="hidden" value="<?php echo $output['store_id']; ?>" id="merchantId" class="merchantId" name="merchantId">
<script type="text/javascript">
$(".header-wrap").remove();$(".public-nav-layout").remove();$(".ncsl-nav").remove();//$("#cti").remove();
</script>	
    <div class="shop-list-main">
    	<div class="pc-wrapper"><?php echo $output['pc_page']['out']; ?></div>
    </div>













<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/visual_js/jquery.json.js"></script> 
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/visual_js/transport_jquery.js"></script> 
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/visual_js/utils.js"></script> 
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/visual_js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/visual_js/jquery.yomi.js"></script> 
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/visual_js/jquery.nyroModal.js"></script> 
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store_js/dsc-common.js"></script> 
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store_js/jquery.purebox.js"></script> 
    <script type="text/javascript">

  // slide
  Focus();

 
// slide show
function Focus(option) {
	function byid(id) {
		return document.getElementById(id);
	}
	function bytag(tag, obj) {
		return (typeof obj == 'object' ? obj: byid(obj)).getElementsByTagName(tag);
	}
	// 添加option参数，可以同时运行多个实例
	var option = option ? option : {};
	opt = {
		oFocus : option.oFocus ? option.oFocus : 'tFocus',
		oPic : option.oPic ? option.oPic : 'tFocus-pic',
		oBtn : option.oBtn ? option.oBtn : 'tFocus-btn',
		tLeft : option.tLeft ? option.tLeft : 'tFocus-leftbtn',
		tRight : option.tRight ? option.tRight : 'tFocus-rightbtn',
		prev : option.prev ? option.prev : 'prev',
		next : option.next ? option.next : 'next'
	}
	var timer = null;
	var oFocus = byid(opt.oFocus);
	var oPic = byid(opt.oPic);
	var oPicLis = bytag('li', oPic);
	var oBtn = byid(opt.oBtn);
	var oBtnLis = bytag('li', oBtn);
	var iActive = 0;
	function inlize() {
		oPicLis[0].style.filter = 'alpha(opacity:100)';
		oPicLis[0].style.opacity = 100;
		oPicLis[0].style.zIndex = 5;
	}; 
	for (var i = 0; i < oPicLis.length; i++) {
		oBtnLis[i].sIndex = i;
		oBtnLis[i].onclick = function() {
			if (this.sIndex == iActive) return;
			iActive = this.sIndex;
			changePic();
		}
	};
	byid(opt.tLeft).onclick = byid(opt.prev).onclick = function() {
		iActive--;
		if (iActive == -1) {
			iActive = oPicLis.length - 1;
		}
		changePic();
	};
	byid(opt.tRight).onclick = byid(opt.next).onclick = function() {
		iActive++;
		if (iActive == oPicLis.length) {
			iActive = 0;
		}
		changePic();
	};
	
	function changePic() {
		for (var i = 0; i < oPicLis.length; i++) {
			doMove(oPicLis[i], 'opacity', 0);
			oPicLis[i].style.zIndex = 0;
			oBtnLis[i].className = '';
		};
		doMove(oPicLis[iActive], 'opacity', 100);
		oPicLis[iActive].style.zIndex = 5;
		oBtnLis[iActive].className = 'active';
		if (iActive == 0) {
			doMove(bytag('ul', oBtn)[0], 'left', 0);
		} else if (iActive >= oPicLis.length - 2) {
			doMove(bytag('ul', oBtn)[0], 'left', -(oPicLis.length - 3) * (oBtnLis[0].offsetWidth + 4));
		} else {
			doMove(bytag('ul', oBtn)[0], 'left', -(iActive - 1) * (oBtnLis[0].offsetWidth + 4));
		}
	};
	function autoplay() {
		if (iActive >= oPicLis.length - 1) {
			iActive = 0;
		} else {
			iActive++;
		}
		changePic();
	};
	aTimer = setInterval(autoplay, 3000);
	inlize();
	function getStyle(obj, attr) {
		if (obj.currentStyle) {
			return obj.currentStyle[attr];
		} else {
			return getComputedStyle(obj, false)[attr];
		}
	};
	function doMove(obj, attr, iTarget) {
		clearInterval(obj.timer);
		obj.timer = setInterval(function() {
			var iCur = 0;
			if (attr == 'opacity') {
				iCur = parseInt(parseFloat(getStyle(obj, attr)) * 100);
			} else {
				iCur = parseInt(getStyle(obj, attr));
			}
			var iSpeed = (iTarget - iCur) / 6;
			iSpeed = iSpeed > 0 ? Math.ceil(iSpeed) : Math.floor(iSpeed);
			if (iCur == iTarget) {
				clearInterval(obj.timer);
			} else {
				if (attr == 'opacity') {
					obj.style.opacity = (iCur + iSpeed) / 100;
				} else {
					obj.style.filter = 'alpha(opacity:' + (iCur + iSpeed) + ')';
				}
			}
		},
		30)
	};
	byid(opt.oFocus).onmouseover = function() {
		clearInterval(aTimer);
	}
	byid(opt.oFocus).onmouseout = function() {
		aTimer = setInterval(autoplay, 3000);
	}
}
    	
		var slideType = $("*[data-mode='lunbo']").find("*[data-type='range']").data("slide");
		var length = $(".shop_banner .bd").find("li").length;
		if(slideType == "roll"){
			slideType = "left";
			$(".shop_banner .bd").find("li").show();
		}
		
		if(length>1){
			$(".shop_banner").slide({titCell:".hd ul",mainCell:".bd ul",effect:slideType,interTime:5000,delayTime:500,autoPlay:true,autoPage:true,trigger:"click",endFun:function(i,c,s){
				$(window).resize(function(){
					var width = $(window).width();
					s.find(".bd li").css("width",width);
				});
			}});
		}else{
			$(".shop_banner .hd").hide();
		}
		
		var adv_slideType = $("*[data-mode='advImg1']").find("*[data-type='range']").data("slide");
		var adv_length = $(".adv_module .bd").find("li").length;
		
		if(adv_slideType == "roll"){
			adv_slideType = "left";
			$(".adv_module .bd").find("li").show();
		}
		
		if(adv_length>1){
			$(".adv_module").slide({titCell:".hd ul",mainCell:".bd ul",effect:adv_slideType,interTime:5000,delayTime:500,autoPlay:true,autoPage:true,trigger:"click"});
		}else{
			$(".adv_module .hd").hide();
		}

        //楼层二级分类商品切换
		$("*[ectype='floorItem']").slide({titCell:".hd-tags li",mainCell:".floor-tabs-content",titOnClassName:"current"});
		
		$("*[ectype='floorItem']").slide({titCell:".floor-nav li",mainCell:".floor-tabs-content",titOnClassName:"current"});
		
		//第五套楼层模板
		$(".floor-fd-slide").slide({mainCell:".bd ul",effect:"left",autoPlay:false,autoPage:true,vis:4,scroll:1,prevCell:".ff-prev",nextCell:".ff-next"});
		
		//第六套楼层模板
		$(".floor-brand").slide({mainCell:".fb-bd ul",effect:"left",pnLoop:true,autoPlay:false,autoPage:true,vis:3,scroll:1,prevCell:".fs_prev",nextCell:".fs_next"});
        
		//楼层轮播图广告
		$("*[data-purebox='homeFloor']").each(function(index, element) {
			var f_slide_length = $(this).find(".floor-left-slide .bd li").length;
			if(f_slide_length > 1){
				$(element).find(".floor-left-slide").slide({titCell:".hd ul",mainCell:".bd ul",effect:"left",interTime:3500,delayTime:500,autoPlay:true,autoPage:true});
			}else{
				$(element).find(".floor-left-slide .hd").hide();
			}
        });
		
        $(document).on("mouseover", ".all_box", function () {
            var all_cats_tcc = $(".all_cats_tcc").html();
            all_cats_tcc = $.trim(all_cats_tcc);

            if(all_cats_tcc == ''){
                var merchant_id = $("#merchantId").val();
//				{if $is_jsonp}
//					$.ajax({
//					   type: "GET",
//					   /*jquery Ajax跨域*/
//					   url: "index.php?con=ajax_content&fun=cat_store_list",
//					   data: "act=cat_store_list&merchant_id=" + merchant_id + "&is_jsonp=" + {$is_jsonp},
//					   dataType:'jsonp',
//					   jsonp:"jsoncallback",
//					   success: function(data){
//						   $(".all_cats_tcc").html(data.content);
//					   }
//					});
//				{else}
					Ajax.call('index.php?con=ajax_content&fun=cat_store_list&act=cat_store_list', 'merchant_id=' + merchant_id, cat_store_listResponse, 'POST', 'JSON');
//				{/if}
            }
        });
		
        function cat_store_listResponse(data){
            $(".all_cats_tcc").html(data.content);
        }
		
        $(function(){
        	//重新加载商品模块
            $("[data-mode='floor']").each(function(){
                var _this = $(this);
                var goods_ids = _this.data("goodsid");
                var warehouse_id = $("input[name='warehouse_id']").val();
                var area_id = $("input[name='area_id']").val();
                if(goods_ids){
//                  {if $is_jsonp}
//					$.ajax({
//					   type: "GET",
//					   /*jquery Ajax跨域*/
//					   url: "{$site_domain}ajax_dialog.php",
//						data: "act=getguessYouLike&goods_ids=" + goods_ids +  "&warehouse_id=" + warehouse_id + "&area_id=" + area_id + "&type=seller" + "&is_jsonp=" + {$is_jsonp},
//					   dataType:'jsonp',
//					   jsonp:"jsoncallback",
//					   success: function(data){
//						   if(data.content){
//								_this.find(".view ul").html(data.content);
//						   }
//					   }
//					});
//                  {else}
					Ajax.call('ajax_dialog.php?act=getguessYouLike', 'goods_ids=' + goods_ids + "&warehouse_id=" + warehouse_id + "&area_id=" + area_id + "&type=seller", function(data){
						 if(data.content){
							 _this.find(".view ul").html(data.content);
						 }
					 } , 'POST', 'JSON');
//                  {/if}
                }
            });
			
			//营业执照
			$(".nyroModal").nyroModal();
				$("li[ectype='floor_cat_content'].current").each(function(){
				get_homefloor_cat_content(this);
			});
			
			$("[ectype='identi_floorgoods'].current").each(function(){
				get_homefloor_cat_content(this);
			});
        });
    </script>
<?php } ?>
