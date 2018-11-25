<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/zhibo.css" rel="stylesheet" type="text/css">
<style>
body{ background:url(<?php echo UPLOAD_SITE_URL.'/shop/store/zhibo_bg/'.$output['zb_config']['bgid'].'.jpg'; ?>) no-repeat center fixed;}
</style>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/player/js/swfobject.js"></script>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/player/js/web_socket.js"></script>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/player/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/player/js/rooms.js"></script>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/player/js/gift.js"></script>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/player/js/face.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.carouFredSel.js"></script> 
<script type="text/javascript">
var roomid=<?php echo $_GET['store_id']?>;
<?php if ($_SESSION['is_login']) {?>
var u_id='<?php echo $_SESSION['member_id'];?>';
var u_leave='<?php if($_SESSION['member_id']==$output['zb_config']['uid']){ echo "主播";}else{ echo $output['member_info']['level_name'];}?>';
var u_name='<?php echo $_SESSION['member_name'];?>';
var u_img='<?php echo getMemberAvatar($output['member']['member_avatar']);?>';
<?php } else {?>
var u_id=0;
var u_leave='V0';
var u_name='游客';
var u_img='http://118.31.17.175/data/upload/shop/common/default_user_portrait.gif';
<?php }?>
</script>
<div id="zhibo">
<div class='zb_main'>
<div class="zb_left">
<div class="store">
	<div class="store-logo">
    <a title="<?php echo $output['store_info']['store_name'];?>" target="_blank" href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" ><img src="<?php echo getStoreLogo($output['store_info']['store_label'],'store_logo');?>" alt="<?php echo $output['store_info']['store_name'];?>"></a>
    <a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>" title="<?php echo $output['setting_config']['site_name']; ?>" class="store_name"><?php echo $output['store_info']['store_name']; ?></a>
    </div>
    
     <div class="pj_info">
        	<?php  foreach ($output['store_info']['store_credit'] as $value) {?>
            <div class="shopdsr_item">
                <div class="shopdsr_title"><?php echo $value['text'];?></div>
                <div class="shopdsr_score"><?php echo $value['credit'];?></div>
            </div>
            <?php } ?>
    	</div>
    <div class="btns">
    <a href="javascript:collect_store('<?php echo $output['store_info']['store_id'];?>','count','store_collect')" >收藏店铺<span>(<em nctype="store_collect"><?php echo $output['store_info']['store_collect']?></em>)</span></a>
  
     </div>
     <div class="oserver">
      <?php if(!empty($output['store_info']['store_qq'])){?>
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:8" style=" vertical-align: middle;"/></a>
        <?php }?>
        <?php if(!empty($output['store_info']['store_ww'])){?>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>" style=" vertical-align: text-bottom;"/> 旺旺</a>
        <?php }?>
       </div>
	
</div>
	<div class="users" id="userlist">
    	<h4>在线用户(<span id="online">0</span>)</h4>
        <ul id="VA"></ul>
    	<ul id="V10"></ul>
    	<ul id="V9"></ul>
        <ul id="V8"></ul>
        <ul id="V7"></ul>
        <ul id="V6"></ul>
        <ul id="V5"></ul>
        <ul id="V4"></ul>
        <ul id="V3"></ul>
        <ul id="V2"></ul>
        <ul id="V1"></ul>
        <ul id="V0"></ul>
    </div>
</div>
<div class="zb_center">
<div id="jw_player"> 
<?php if($_SESSION['member_id']==$output['zb_config']['uid']){?>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="640" height="480" id="publish" align="middle">
				<param name="movie" value="<?php echo SHOP_SITE_URL;?>/player/streamer.swf<?php echo $output['as'];?>" />
				<param name="quality" value="high" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="<?php echo SHOP_SITE_URL;?>/player/streamer.swf<?php echo $output['as'];?>" width="640" height="480">
					<param name="movie" value="<?php echo SHOP_SITE_URL;?>/player/streamer.swf<?php echo $output['as'];?>" />
					<param name="quality" value="high" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
				<!--<![endif]-->
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
<?php }else{?>
    	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="640" height="480" id="living" align="middle">
				<param name="movie" value="<?php echo SHOP_SITE_URL;?>/player/player.swf<?php echo $output['as'];?>" />
				<param name="quality" value="high" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="<?php echo SHOP_SITE_URL;?>/player/player.swf<?php echo $output['as'];?>" width="640" height="480">
					<param name="movie" value="<?php echo SHOP_SITE_URL;?>/player/player.swf<?php echo $output['as'];?>" />
					<param name="quality" value="high" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
				<!--<![endif]-->
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object> 
 <?php }?>
     </div>
 <div class="zb_gift">
    <?php include template('store/zhibo_gift');?>
  <div class="info">
<label>数量:</label><input id="sendGiftNum" value="1" onkeyup="value=this.value.replace(/\D/g,'');closeAnimate.options.showCloseAnimate()">
<em onclick="showGiftNumSelWin(this,event)" class="select_icon"></em>
<?php if($_SESSION['is_login']){?>
<div class="yue">账户：<span class="jifen" id="userjifen"><?php if($output['member']['member_points']){echo $output['member']['member_points'];}else{echo "0";}?></span>积分</div>
<input value="<?php echo $output['zb_config']['uid'];?>" id="receiverId" type="hidden">
<a href="javascript:void(0);" id="zslw_btn" class="zs_btn">赠送</a>
<a href="javascript:void(0);" id="duihuan" class="cz_btn">兑换积分</a>
<?php }else{?>
<div class="yue">需要登录后才可赠送主播礼物！</div>
<a href="/member/index.php?act=login&op=index" class="zs_btn">登录</a>
<a href="/member/index.php?act=login&op=register" target="_blank" class="cz_btn">注册</a>
<?php }?>
</div>
<div class="monday_qiang" style="display: none; position: absolute; z-index: 88; top: 105px; left: 290px; height: 22px; width: 120px;" id="needCoinNum">价值：000积分</div>
</div>

      <div class="content ncs-goods-list recommended_goods_list">
        <?php if(!empty($output['recommended_goods_list']) && is_array($output['recommended_goods_list'])){?>
        <ul>
          <?php foreach($output['recommended_goods_list'] as $value){?>
          <li>
            <dl>
              <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$value['goods_id']));?>" class="goods-thumb" target="_blank"><img src="<?php echo thumb($value, 240);?>" alt="<?php echo $value['goods_name'];?>"/></a>
                <ul class="goods-thumb-scroll-show">
                <?php if (is_array($value['image'])) { array_splice($value['image'], 5);?>
                  <?php $i=0;foreach ($value['image'] as $val) {$i++?>
                  <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo thumb($val, 60);?>"/></a></li>
                  <?php }?>
                <?php } else {?>
                  <li class="selected"><a href="javascript:void(0)"><img src="<?php echo thumb($value, 60);?>"></a></li>
                <?php }?>
                </ul>
              </dt>
              <dd class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$value['goods_id']));?>" title="<?php echo $value['goods_name'];?>" target="_blank"><?php echo $value['goods_name']?></a></dd>
              <dd class="goods-info"><span class="price"><i><?php echo $lang['currency'];?></i> <?php echo ncPriceFormat($value['goods_promotion_price']);?> </span> <span class="goods-sold"><?php echo $lang['show_store_index_be_sold'];?><strong><?php echo $value['goods_salenum'];?></strong> <?php echo $lang['nc_jian'];?></span></dd>
              <?php if (C('groupbuy_allow') && $value['goods_promotion_type'] == 1) {?>
              <dd class="goods-promotion"><span>抢购商品</span></dd>
              <?php } elseif (C('promotion_allow') && $value['goods_promotion_type'] == 2)  {?>
              <dd class="goods-promotion"><span>限时折扣</span></dd>
              <?php }?>
              </dl>
          </li>
          <?php }?>
        </ul>
        <?php }else{?>
        <div class="nothing">
          <p><?php echo $lang['show_store_index_no_record'];?></p>
        </div>
        <?php }?>
      </div>
</div>

<div class="zb_right">
	<!--<div class="toutiao">
    	<div class="announcements clearfix">
    <div class="scrollBoxRich" id="scrollBoxRich">
      <ul id="richScroll" style="margin-top: 0px;"></ul>
    </div>
    <div class="scrollBox" id="scrollBox">
      <ul id="scroll1" style="margin-top: 0px;">      		
        </ul>
    </div>
  </div>
    </div>-->
	<div class="zb_title">
    	<label class="msgNow" id="label_1" type="1" style="position: relative; width: 52px;">全部</label>
        <label class="" id="label_2" type="1" style="position: relative; width: 52px;">聊天</label>
        <label class="" id="label_3" type="1" style="position: relative; width: 52px;">礼物</label>
        <label class="" id="label_4" type="1" style="position: relative; width: 52px;">游戏</label>
    </div>
    <div id="msg1"  class="chat lable">
    <div class="gonggao"><i>房间公告</i><?php echo $output['zb_config']['gonggao']; ?></div>
	<div class="room" id="dialog"><div id="chatmsg"></div></div>
    <div class="enter none" id="enter"></div>
    <div class="sroom" id="sroom"></div>
    </div>
    <div id="msg2"  class="chat lable none">
    </div>
    <div id="msg3" class="rgift lable none">
    </div>
    <div id="msg4" class="game lable none">
    </div>
    <div class="send">
        <form onSubmit="onSubmit(); return false;">
            <select style="margin-bottom:8px" id="client_list">
                <option value="all">所有人</option>
            </select>
            <div class="input">
            <a id='face'></a>
            <div class="face none"><ul class="facenavlisttop2" id="facelistData1">
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0很好');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e5be88e5a5bd.gif" title="很好" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0好的');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e5a5bde79a84.gif" title="好的" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0白眼');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e799bde79cbc.gif" title="白眼" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0拜托');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e68b9ce68998.gif" title="拜托" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0扮鬼脸');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e689aee9acbce884b8.gif" title="扮鬼脸" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0抱抱');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e68ab1e68ab1.gif" title="抱抱" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0鄙视');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e98499e8a786.gif" title="鄙视" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0闭嘴');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e997ade598b4.gif" title="闭嘴" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0抽');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e68abd.gif" title="抽" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0吹口哨');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e590b9e58fa3e593a8.gif" title="吹口哨" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0呲牙');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e591b2e78999.gif" title="呲牙" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0调皮');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e8b083e79aae.gif" title="调皮" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0发怒');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e58f91e68092.gif" title="发怒" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0飞吻');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e9a39ee590bb.gif" title="飞吻" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0尴尬');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e5b0b4e5b0ac.gif" title="尴尬" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0勾引');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e58bbee5bc95.gif" title="勾引" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0鼓掌');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e9bc93e68e8c.gif" title="鼓掌" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0红脸');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e7baa2e884b8.gif" title="红脸" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0哼');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e593bc.gif" title="哼" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0坏笑');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e59d8fe7ac91.gif" title="坏笑" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0惊讶');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e6838ae8aeb6.gif" title="惊讶" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0囧');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e59ba7.gif" title="囧" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0可怜');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e58fafe6809c.gif" title="可怜" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0狂汗');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e78b82e6b197.gif" title="狂汗" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0狂亲');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e78b82e4bab2.gif" title="狂亲" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0狂笑');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e78b82e7ac91.gif" title="狂笑" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0泪');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e6b3aa.gif" title="泪" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0美女');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e7be8ee5a5b3.gif" title="美女" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0媚眼');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e5aa9ae79cbc.gif" title="媚眼" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0迷茫');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e8bfb7e88cab.gif" title="迷茫" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0钱');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e992b1.gif" title="钱" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0亲亲');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e4bab2e4bab2.gif" title="亲亲" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0色');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e889b2.gif" title="色" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0伤不起');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e4bca4e4b88de8b5b7.gif" title="伤不起" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0伤透了');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e4bca4e9808fe4ba86.gif" title="伤透了" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0石化');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e79fb3e58c96.gif" title="石化" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0帅哥');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e5b885e593a5.gif" title="帅哥" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0挑逗');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e68c91e98097.gif" title="挑逗" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0偷笑');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e581b7e7ac91.gif" title="偷笑" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0吐血');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e59090e8a180.gif" title="吐血" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0挖鼻');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e68c96e9bcbb.gif" title="挖鼻" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0委屈');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e5a794e5b188.gif" title="委屈" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0嘘');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e59898.gif" title="嘘" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0耶');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e880b6.gif" title="耶" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0阴险');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e998b4e999a9.gif" title="阴险" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0晕');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e69995.gif" title="晕" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0再见');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e5868de8a781.gif" title="再见" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0咒骂');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e59292e9aa82.gif" title="咒骂" style="border:0 none;vertical-align:middle"></span></li>
<li style="text-align:center;line-height:36px"><span style="cursor:pointer;" onclick="clickFace('0抓狂');"><img src="<?php echo SHOP_SITE_URL;?>/player/face/e68a93e78b82.gif" title="抓狂" style="border:0 none;vertical-align:middle"></span></li>
</ul></div>
            <input type="text" class="textarea thumbnail" id="textarea"></input>
            </div>
            <input type="submit" class="btn btn-default" value="发表" />
         </form>
    </div>
    
    
          <div class="content ncs-goods-list new_goods_list">
        <?php if(!empty($output['new_goods_list']) && is_array($output['new_goods_list'])){?>
        <ul>
          <?php foreach($output['new_goods_list'] as $value){?>
          <li>
            <dl>
              <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$value['goods_id']));?>" class="goods-thumb" target="_blank"><img src="<?php echo thumb($value, 240)?>" alt="<?php echo $value['goods_name'];?>"/></a>
                <ul class="goods-thumb-scroll-show">
                <?php if (is_array($value['image'])) { array_splice($value['image'], 5);?>
                  <?php $i=0;foreach ($value['image'] as $val) {$i++?>
                  <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo thumb($val, 60);?>"/></a></li>
                  <?php }?>
                <?php } else {?>
                  <li class="selected"><a href="javascript:void(0)"><img src="<?php echo thumb($value, 60);?>"></a></li>
                <?php }?>
                </ul>
              </dt>
              <dd class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$value['goods_id']));?>" title="<?php echo $value['goods_name'];?>" target="_blank"><?php echo $value['goods_name'];?></a></dd>
              <dd class="goods-info"><span class="price"><i><?php echo $lang['currency'];?></i> <?php echo ncPriceFormat($value['goods_promotion_price']);?> </span> <span class="goods-sold"><?php echo $lang['show_store_index_be_sold'];?><strong><?php echo $value['goods_salenum'];?></strong> <?php echo $lang['nc_jian'];?></span></dd>
              <?php if (C('groupbuy_allow') && $value['goods_promotion_type'] == 1) {?>
              <dd class="goods-promotion"><span>抢购商品</span></dd>
              <?php } elseif (C('promotion_allow') && $value['goods_promotion_type'] == 2)  {?>
              <dd class="goods-promotion"><span>限时折扣</span></dd>
              <?php }?>
              </dl>
          </li>
          <?php }?>
        </ul>
        <?php }else{?>
        <div class="nothing">
          <p><?php echo $lang['show_store_index_no_record'];?></p>
        </div>
        <?php }?>
      </div>
</div>

<div id="duihuanjifen" class="duihuan none">
<p class="title">兑换积分<span class="close"></span></p>
<p class="info">账户余额：￥<span id="yuan"><?php echo $output['member']['available_predeposit']; ?></span>元（兑换说明：1元=100积分）</p>
<p class="input">您要兑换<input type="text" name="lebi" id="lebi" value="100"/>积分（本次将从账户扣除￥<span class="money" id="moneykou">1.00</span>元）</p>
<p class="bottom"><a href="javascript:void(0);" id="sureduihuan" class="bduihuan">兑换</a><a href="./../member/index.php?con=predeposit&fun=recharge_add" class="pay">在线充值</a></p>
<script type="text/javascript">
	//public-nav-layout ncs-header-container
	$(".public-nav-layout").remove();$(".ncs-header-container").remove();
	$(".ncs-nav").css("background-color","#333");$(".ncsl-nav").css("width","100%");$(".ncs-nav").css("width","100%");$(".ncs-nav ul").css("margin","0 auto");
	$(function() {
		$('.ncs-goods-list ul').carouFredSel({
			prev: '#prev',
			next: '#next',
			pagination: "#pager",
			scroll: 1000
		});	
	});
</script>
</div>
</div>
</div>
