<?php
/**
 * 邀请注册模板文件
 *
 * @User      noikiy
 * @File      invite.php
 * @Link     
 * @Copyright 
 */

defined('Inshopec') or exit('Access Invalid!');

?>

<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/invite.css" rel="stylesheet" type="text/css">
<div class="container-bg">
    <div class="container">
        <div id="content" class="span-24">
            <div class="invite-bd">
                <div class="invite-points"><?php echo C('points_invite'); ?></div>
                <div class="invite-form">
                    <div class="invite-text">邀请链接：<span class="invite-help">复制下面的链接，通过QQ，旺旺，微博，论坛发帖等方式发给好友，对方通过该链接注册即可</span></div>
                    <div class="invite-link">
                        <input type="text" class="std-input i-invite-link" value="<?php echo $_SESSION['member_id'] ? urlMember('login', 'register', array('inviterid' => $_SESSION['member_id'])) : '请先登录'; ?>" readonly>
                        <a class="button copy-btn" data-url="<?php echo SHOP_TEMPLATES_URL;?>/images/invite/ZeroClipboard.swf" id="copy-button" href="javascript:;" hidefocus="true">复制</a>
                    </div>
                </div>
                <div class="invite-share-site clearfix">
                    <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more">分享到：</a><a title="分享到QQ空间" href="#" class="bds_qzone" data-cmd="qzone">QQ空间</a><a title="分享到新浪微博" href="#" class="bds_tsina" data-cmd="tsina">新浪微博</a><a title="分享到腾讯微博" href="#" class="bds_tqq" data-cmd="tqq">腾讯微博</a><a title="分享到人人网" href="#" class="bds_renren" data-cmd="renren">人人网</a><a title="分享到微信" href="#" class="bds_weixin" data-cmd="weixin">微信</a></div>
                </div>
                <div class="invite-rebate">
                    <a href="<?php echo urlShop('member_points','index');?>" target="_blank" hidefocus="true"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/invite/income.png"></a>
                </div>
                <div class="invite-rules">
                    <p>1、成功邀请一个好友，可获<?php echo C('points_invite'); ?>积分奖励</p>
                    <p>2、当好友成功购买了商品，可获得<?php echo C('points_rebate'); ?>%返利积分</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"网购这么久，现在才发现【<?php echo C('site_name'); ?>】的东西不仅质量好还便宜，赶紧试试吧，一般人我不告诉他！","bdMini":"2","bdMiniList":false,"bdPic":"<?php echo SHOP_TEMPLATES_URL;?>/images/invite/share.png","bdStyle":"0","bdSize":"16"},"share":{"bdSize":16}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/images/invite/ZeroClipboard.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        initInviteForm();
    });
    function initInviteForm() {
        $(".i-invite-link").click(function(){
            $(this).select();
        });

        $(".invite-form .copy-btn").each(function(){
            ZeroClipboard.setMoviePath($(this).attr("data-url"));
            var clip = new ZeroClipboard.Client(); // 新建一个对象
            clip.setHandCursor(true ); // 设置鼠标为手型
            clip.setText(""); // 设置要复制的文本。
            clip.setHandCursor(true);
            clip.setCSSEffects(true);
            clip.addEventListener('complete', function(client, text) {
                alert("邀请链接复制成功！\n\n马上分享给你的好友吧!" );
            });
            clip.addEventListener('mouseDown', function(client) { 
                clip.setText($(".i-invite-link").val());
            });
            clip.glue("copy-button");
            $(this).click(function(e){
                e.preventDefault();
                alert("啊哦，好像复制失败了……手动复制一下吧！");
            });
        });
    }
</script>