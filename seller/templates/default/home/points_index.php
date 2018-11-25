<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>



<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/points.css">

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>



</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-title">

      <h1>积分中心</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

    <?php include template('layout/toptip');?>

</header>

<?php require_once template('layout/fiexd');?>

<div class="nctouch-main-layout pointsclub">

<?php if($_SESSION['is_login']==1){?>

<div class="pointsclub_avatar border_bottom">

    <a href="<?php echo urlMobile('member');?>" style="display:block;">

    <img src="<?php echo $output['member']['member_avatar']?>" class="left avatar">

    <div class="userinfo">

        <p class="c666 tit"><?php echo $output['member']['member_name']?><span><?php echo $output['member']['level_name']?></span></p>

        <div class="progress-bar"><span title="<?php echo $output['member']['exppoints_rate']?>%"><i style="width:<?php echo $output['member']['exppoints_rate']?>%;"></i></span></div>

        <p id="levelInfo" class="stepinfo c999"><?php echo $output['member']['tipinfo']?></p>

    </div>

    </a>

</div>



<div class="pointsclub_jf">

    <a href="<?php echo urlMobile('member_points');?>"><div class="c666"><?php echo $output['member']['member_points']?></div><div class="c999">我的积分</div></a>

    <a href="<?php echo urlMobile('member_voucher');?>"><div class="c666"><?php echo $output['member']['vouchercount']?></div><div class="c999">我的优惠码</div></a>

     <a href="<?php echo urlMobile('member_pointorder');?>"><div class="c666"><?php echo $output['member']['pointordercount']?></div><div class="c999">已兑换礼品</div></a>

</div>

<div class="mt10"><a href="<?php echo urlMobile('member_signin');?>" id="btnQD" class="btn1 qd_btn">签到领积分</a></div>

<?php }?>



<div class="cut_line <?php if($_SESSION['is_login']!=1){?> mt30  <?php } ?> "><span>特色活动</span></div>

<?php echo loadadv(122);?>

 



<div class="panes vlist clear mt5">

     <dl class="mt5">

        <dt><a href="<?php echo urlMobile('points','gifts');?>">

          <h3><i class="mc-01"></i>热门礼品</h3>

          <h5>更多<i class="arrow-r"></i></h5>

          </a></dt>

       

      </dl>       

            <div style="display:block;">

                <ul id="duihuanList">

                   <?php if(is_array($output['recommend_pointsprod']) && !empty($output['recommend_pointsprod'])){?>

                   <?php foreach($output['recommend_pointsprod'] as $re){?>

                    <li>

                        <a href="<?php echo urlMobile('points','detail',array('pgoods_id'=>$re['pgoods_id']));?>">

                            <img class="list_img" src="<?php echo $re['pgoods_image']?>">

                            <div class="list_info">

                                <div class="tit c666"><?php echo $re['pgoods_name']?></div>

                                <div class="desc c999"><?php echo str_cut($re['pgoods_description'],50)?></div>

                                <div class="price"><span class="c999"><?php echo $re['pgoods_points']?>积分</span> <del class="c999"><?php echo ncPriceFormatForList($re['pgoods_price'])?></del> </div>

                           

                            </div>

                        </a>

                    </li>

                    <?php  }?>

                    <?php }?>

                </ul>

            </div>

        </div>

<div class="vlist">

    <dl class="mt5 border_bottom">

        <dt class="voucher"><a href="<?php echo urlMobile('points','list');?>">

          <h3><i class="mc-01"></i>热门代金券</h3>

          <h5>更多<i class="arrow-r"></i></h5>

          </a></dt>

       

   </dl> 

   <!-- sold-out -->

<?php if(is_array($output['recommend_voucher']) && !empty($output['recommend_voucher'])){?>

<?php foreach($output['recommend_voucher'] as $rv){?>

<div class="coupon-excellent m  <?php if(($rv['voucher_t_total'] - $rv['voucher_t_giveout']) != 0){?>  coupon-jing <?php }else{ ?> sold-out <?php }?>" onclick="ajax_cashing(<?php echo $rv['voucher_t_id'];?>,$(this))">

        <div class="mt">

            <h4><?php echo $rv['voucher_t_title']?></h4>

            <div class="ce-progress ce-progress-baidu">

                <div class="ce-p-bg" style="width:<?php echo $rv['voucher_jd']?>%"></div>

                <p>已领取<span><?php echo $rv['voucher_jd']?></span>%</p>

                

            </div>

        </div>

        <div class="mc">

            <div class="ce-con">

                <div class="ce-con-l">

                    <span><?php echo $rv['voucher_t_storename'];?></span>

                    <p><i>¥</i><?php echo $rv['voucher_t_price'];?></p>



                </div>

                <div style="margin-top: -31px;" class="ce-con-r">

                    <p class="ce-con-txt01" displaylength="36">需<em><?php echo $rv['voucher_t_points'];?></em>积分</p>

                    <span class="ce-con-line"></span>

                    <p class="ce-con-txt02"><?php if ($rv['voucher_t_limit'] > 0){?>购物满<?php echo $rv['voucher_t_limit'];?>元可用<?php } else { ?>无限额代金券<?php } ?></p>

                </div>

            </div> 

            <div class="ce-hr"></div>

            <div class="ce-time">有效期至<?php echo @date('Y-m-d',$rv['voucher_t_end_date']);?></div>

            <span class="sold-out-signet"></span>

            

          </div>

</div>

<?php  }?>

<?php }?>

 </div>     

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>



