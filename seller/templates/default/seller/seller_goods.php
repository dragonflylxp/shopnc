<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

#goods_list .times_show { background: #f8f8f8;height: 32px;line-height:32px;font-size: 0.65rem; }

#goods_list .times_show .ico_time{width: 0.85rem;height: 0.85rem;background-image:url(<?php echo MOBILE_TEMPLATES_URL;?>/images/times_show.png);background-size:100%;background-position:center center;display:inline-block;vertical-align: middle;margin-left: 0.3rem;margin-right: 0.3rem;opacity: 0.6;}

#goods_list li{position: relative;}

#goods_list li div.liinfo { height: 80px; position: relative; border-bottom: solid 1px #e6e6e6; border-top: solid 1px #e6e6e6; background: #fff;padding: 10px;overflow:hidden;clear: both;}
.list_img { float: left; width: 80px; height: 80px; display: block; }
.list_info{margin-left: 90px;display: block;cursor:pointer;}
.list_info p{font-size: 0.65rem;color: #676b70;height: 0.8rem;line-height: 0.8rem;}
.list_info .tits{color: #2c3e50;height:0.9rem;line-height: 0.9rem;overflow: hidden;}
.list_info .ps{color: #999;height:0.9rem;line-height: 0.9rem;overflow: hidden;}
.input-boxt{width: 2rem;height: 1.3rem;display: inline-block;float: right;}
.input-boxt em { display: inline-block !important; height: 1.3rem !important;line-height: 1.3rem !important; padding: 0 !important; margin: 0!important; border: 0 !important; border-radius: 0 !important; font-size: 0.7rem !important; color: #555 !important;}
.input-boxt em.checked { background-color: transparent !important; padding: 0 !important; color: #555 !important;}
.input-boxt em input[type="checkbox"] { display: none;}
.input-boxt em .power { position: relative;top:-1px; z-index: 1; display: inline-block; vertical-align: middle; width: 1.6rem; height: 0.9rem; margin: 0 0.2rem; border: solid 0.05rem #DDD; border-radius: 0.5rem; }
.input-boxt em .power i { position: absolute; z-index: 1; top: 0rem; left: 0rem; width: 0.9rem; height: 0.9rem; background-color: #FFF; border-radius: 100%; box-shadow: 0.05rem 0.1rem 0.25rem rgba(0,0,0,0.3);}
.input-boxt em.checked .power{ background-color: #48CFAE; border-color: #48CFAE;}
.input-boxt em.checked .power i { left: auto; right: 0; box-shadow: -0.05rem 0.1rem 0.25rem rgba(0,0,0,0.3);}
.wgxj{padding: 0.1rem 0.2rem;background: #e44d4d;color: #fff;font-size: 0.55rem;}
.list_zz{height: 132px;width:100%;filter:progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#E5FFFFFF', endColorstr='#E5FFFFFF');background:rgba(255,255,255,0.9);position: absolute;bottom: 0;z-index: 5;display: none;}
.list_zz div.zz_list{width: 33.3%;height: 132px;float: left;position: relative;cursor: pointer;}
.list_zz div.zz_list h5 {
    display: block;
    height: 1rem;
    text-align: center;
    font-size: 0.6rem;
    line-height: 1rem;
    color: #676b70;
}

.list_zz div.zz_list span {
    position: absolute;
    z-index: 1;
    top: 50%;
    left: 50%;
    display: block;
    width: 3rem;
    height: 3rem;
    margin: -1.5rem 0 0 -1.5rem;
}

.list_zz div.zz_list span i {
    display: block;
    width: 1.9rem;
    height: 1.9rem;
    margin: 0 auto 0.1rem auto;
    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: 65%;
    border: solid 0.075rem #676b70;
    border-radius: 100%;
    opacity: 0.75;

}



.list_zz div.zz_list span i.edit{

  background-image: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/edit.png);

}

.list_zz div.zz_list span i.xq{

  background-image: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/xqck.png);

}

.list_zz div.zz_list span i.del{

  background-image: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/del.png);

}
.btn-add{
  width: 100%;
  height: 1rem;
  display: inline-block;
  margin:  0 auto;
  text-align: center;
  font-size: 0.8rem;
  color: #0094DE;

}
</style>
</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

   <div class="header-title">

      <h1>商品列表</h1>

    </div>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

       <?php include template('layout/seller_toptip');?>





</header>

<div class="nctouch-main-layout">

  <div class="nctouch-order-search">

    <form>

      <span><input type="text" autocomplete="on" maxlength="50" placeholder="关键字进行搜索" name="keyword" id="keyword" oninput="writeClear($(this));" >

      <span class="input-del"></span></span>

      <input type="button" id="search_btn" value="&nbsp;">

    </form>

  </div>
<a class="btn-add mt5 mb5" href="<?php echo urlMobile('seller_goods','add');?>">+添加商品</a>

  <div id="fixed_nav" class="nctouch-single-nav">
    <ul id="filtrate_ul" class="w20h">
      <li class="selected"><a href="javascript:void(0);" data-state="">全部</a></li>
      <li><a href="javascript:void(0);" data-state="online">已上架</a></li>
      <li><a href="javascript:void(0);" data-state="offline">仓库中</a></li>
      <li><a href="javascript:void(0);" data-state="lockup">违规下架</a></li>
    </ul>
  </div>

  <div class="nctouch-order-list">

  	<div id="loding"></div>

    <ul id="goods_list">

  



    </ul>

  </div>



</div>

<div class="fix-block-r">

	<a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

<script type="text/html" id="order-list-tmpl">

<% var goods_list = datas.goods_list; %>

<% if (goods_list.length > 0){%>

	<% for(var i = 0;i < goods_list.length;i++){ var glsit = goods_list[i];%>

	<li goods_id="<%=glsit.goods_commonid%>"  class="goods_id_<%=glsit.goods_commonid%>">

    	<div class="times_show"><i class="ico_time"></i><%=glsit.goods_addtime%>

    	<div class="input-boxt">

    	<% if (glsit.goods_state == 10 && glsit.goods_verify==1){%>	

    		<b class="wgxj">违规</b>

    	<% }else if(glsit.goods_state == 1 &&  glsit.goods_verify==1){ %>

		<em class="checked" style="cursor:pointer;"><span class="power" style="cursor:pointer;"><i></i></span> 

    	</em>

    	<% }else if(glsit.goods_state == 0){ %>

    	  <em style="cursor:pointer;"><span class="power" style="cursor:pointer;"><i></i></span> </em>

    	<% }%>

    	</div>

    	</div>



	    <div class="liinfo">

	        <span class="list_img">

	            <img src="<%=glsit.goods_image%>" height="80" width="80">

	        </span>
	        <span class="list_info">
	            <p class="tits"><%=glsit.goods_name%></p>
              <p class="ps"><%=glsit.goods_jingle%></p>
	            <p>价格:￥<%=glsit.goods_price%></p>
	            <p>库存:<%=glsit.goods_storage_sum%></p>
	        </span>

	    </div>

	    <div class="list_zz" goods_id="<%=glsit.goods_id%>" >

	 

	    <div class="zz_list">

	    	<span class="zz_list_xq">

	    		<i class="xq"></i>

	    		<h5>详情</h5>

	    	</span>

	    </div>
       <div class="zz_list">

        <span class="zz_list_edit">

          <i class="edit"></i>

          <h5>编辑</h5>

        </span>

      </div>
	    <div class="zz_list">

	    	<span class="zz_list_del">

	    		<i class="del"></i>

	    		<h5>删除</h5>

	    	</span>

	    </div>

	</li>

	<%}%>

	<% if (hasmore) {%>

	<li class="loading"><div class="spinner"><i></i></div>商品数据读取中...</li>

	<% } %>

<%}else {%>

	<div class="nctouch-norecord order">

		<div class="norecord-ico"><i></i></div>

		<dl>

			<dt>您还没有相关的商品</dt>

			<dd>可以去看看其他...</dd>

		</dl>

		<a href="<%=ApiUrl%>" class="btn">随便逛逛</a>

	</div>

<%}%>

</script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.waypoints.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/goods_list.js"></script> 

