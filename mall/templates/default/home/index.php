<?php defined('Inshopec') or exit('Access Invalid!'); ?>
<link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL; ?>/js/home_index.js" charset="utf-8"></script>
<style type="text/css">
    .category {
        display: block !important;
    }
</style>
<div class="clear"></div>

<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout"> <?php echo $output['web_html']['index_pic']; ?>
    <div class="right-sidebar">
        <div class="policy">
            <ul>
                <li class="b1">七天包退</li>
                <li class="b2">正品保障</li>
                <li class="b3">闪电发货</li>
            </ul>
        </div>
        <?php if (!empty($output['group_list']) && is_array($output['group_list'])) { ?>

            <div class="groupbuy">
                <div class="title"><i>团</i>近期团购</div>
                <ul>
                    <?php foreach ($output['group_list'] as $val) { ?>
                        <li>
                            <dl style=" background-image:url(<?php echo gthumb($val['groupbuy_image1'], 'small'); ?>)">
                                <dt><?php echo $val['groupbuy_name']; ?></dt>
                                <dd class="price"><span
                                        class="groupbuy-price"><?php echo ncPriceFormatForList($val['groupbuy_price']); ?></span><span
                                        class="buy-button"><a
                                            href="<?php echo urlShop('show_groupbuy', 'groupbuy_detail', array('group_id' => $val['groupbuy_id'])); ?>">立即团</a></span>
                                </dd>
                                <dd class="time"><span
                                        class="sell">已售<em><?php echo $val['buy_quantity'] + $val['virtual_quantity']; ?></em></span>
                                    <span class="time-remain" count_down="<?php echo $val['end_time'] - TIMESTAMP; ?>"> <em
                                            time_id="d">0</em><?php echo $lang['text_tian']; ?><em
                                            time_id="h">0</em><?php echo $lang['text_hour']; ?> <em
                                            time_id="m">0</em><?php echo $lang['text_minute']; ?><em
                                            time_id="s">0</em><?php echo $lang['text_second']; ?> </span></dd>
                            </dl>
                        </li>
                    <?php } ?>
                </ul>
            </div>

        <?php } else { ?>
<!--            <a href="http://android.myapp.com/myapp/detail.htm?apkName=com.xinyuangongxiang.shop" target="_blank" ><img-->
<!--                    src="/shop/templates/default/images/wx_qrcode.jpg" style="width: 212px;height: 240px" " alt=""></a>-->
            <img
                src="/shop/templates/default/images/wx_qrcode.jpg" style="width: 212px;height: 240px" " alt="">
        <?php } ?>
        <div class="proclamation">
            <ul class="tabs-nav">
                <li class="tabs-selected">
                    <h3>招商入驻</h3>
                </li>
                <li>
                    <h3><?php echo $output['show_article']['notice']['ac_name']; ?></h3>
                </li>
            </ul>
            <div class="tabs-panel"><a href="<?php echo urlShop('show_joinin', 'index'); ?>"
                                       title="申请商家入驻；已提交申请，可查看当前审核状态。" class="store-join-btn" target="_blank">&nbsp;</a>
                <a href="<?php echo urlShop('seller_login', 'show_login'); ?>" target="_blank"
                   class="store-join-help"><i class="icon-cog"></i>登录商家管理中心</a></div>
            <div class="tabs-panel tabs-hide">
                <ul class="mall-news">
                    <?php if (!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
                        <?php foreach ($output['show_article']['notice']['list'] as $val) { ?>
                            <li><i></i><a target="_blank"
                                          href="<?php echo empty($val['article_url']) ? urlMember('article', 'show', array('article_id' => $val['article_id'])) : $val['article_url']; ?>"
                                          title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'], 24); ?> </a>
                                <time>(<?php echo date('Y-m-d', $val['article_time']); ?>)</time>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--HomeFocusLayout End-->

<div class="home-sale-layout wrapper">
    <div class="left-layout"><?php echo $output['web_html']['index_sale']; ?></div>
    <?php if (!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
        <div class="right-sidebar">
            <div class="title">
                <h3><?php echo $lang['nc_xianshi']; ?></h3>
                <a href="<?php echo urlShop('promotion', 'index'); ?>" target="_blank">更多促销</a></div>
            <div id="saleDiscount" class="sale-discount">
                <ul>
                    <?php foreach ($output['xianshi_item'] as $val) { ?>
                        <li>
                            <dl>
                                <dt class="goods-name"><?php echo $val['goods_name']; ?></dt>
                                <dd class="goods-thumb"><a
                                        href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>">
                                        <img src="<?php echo thumb($val, 240); ?>"></a></dd>
                                <dd class="goods-price"><?php echo ncPriceFormatForList($val['xianshi_price']); ?> <span
                                        class="original"><?php echo ncPriceFormatForList($val['goods_price']); ?></span>
                                </dd>
                                <dd class="goods-price-discount"><em><?php echo $val['xianshi_discount']; ?></em></dd>
                                <dd class="time-remain" count_down="<?php echo $val['end_time'] - TIMESTAMP; ?>"><i></i><em
                                        time_id="d">0</em><?php echo $lang['text_tian']; ?><em
                                        time_id="h">0</em><?php echo $lang['text_hour']; ?> <em
                                        time_id="m">0</em><?php echo $lang['text_minute']; ?><em
                                        time_id="s">0</em><?php echo $lang['text_second']; ?> </dd>
                                <dd class="goods-buy-btn"></dd>
                            </dl>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <?php } else{?>
<!--        <span style="height: 40px;width: 211px;border-bottom: 2px solid #F0F0F0;display: block;float: right;line-height: 40px;text-align: center;font-size: 12px;font-weight: 600">-->
<!--            点击下方，进入官网。-->
<!--        </span>-->
        <a href="http://www.chinagxjj.com/" target="_blank" style="width: 211px;height: 294px"><img
                src="/shop/templates/default/images/chinagx.jpg" style="width: 210px;height: 294px" " alt=""></a>
    <?php };?>
</div>
<div class="wrapper">
    <div class="mt10">
        <div class="mt10"><a title="物流自提服务站广告" target="_blank" href="<?php echo DELIVERY_SITE_URL; ?>"> <img border="0"
                                                                                                             alt="物流自提服务站广告"
                                                                                                             src="<?php echo UPLOAD_SITE_URL . '/' . ATTACH_DELIVERY; ?>/adv/banner.gif"
                                                                                                             style="width:1200px;height:100px">
            </a></div>
    </div>
</div>
<!--StandardLayout Begin-->
<?php echo $output['web_html']['index']; ?>
<!--StandardLayout End-->
<div class="wrapper">
    <div class="mt10"><?php echo loadadv(9, 'html'); ?></div>
</div>
