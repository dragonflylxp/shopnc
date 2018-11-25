<?php defined('Inshopec') or exit('Access Invalid!');?>
<!--20160906-->
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header" class="transparent">

  <div class="header-wrap">

    <div class="header-l"> <a href="<?php echo urlMobile('member_account');?>"> <i class="set"></i> </a> </div>

    <div class="header-title">

      <h1>我的商城</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div> 

  <?php include template('layout/toptip');?>



</header>

<div class="scroller-body">

  <div class="scroller-box">

    <div class="member-top"></div>

    <div class="member-center">
                  <dl class="mt5">
                <dt>
                    <a href="distribution_qrcode.html">
                        <h3><i class="mc-01"></i>我的分销</h3>
                        
                    </a>
                </dt>
                <dd>
                    <ul id="distribution_ul">
                        <li><a href="<?php echo urlMobile('distribution');?>"><i class="cc-06"></i><p>我的佣金</p></a></li>
                        <li><a href="<?php echo urlMobile('distribution','index',array('data-level'=>'first'));?>"><i class="cc-02"></i><p>一级推广</p></a></li>
                        <li><a href="<?php echo urlMobile('distribution','index',array('data-level'=>'second'));?>"><i class="cc-02"></i><p>二级推广</p></a></li>
                        <li><a href="<?php echo urlMobile('distribution','index',array('data-level'=>'third'));?>">"><i class="cc-02"></i><p>三级推广</p></a></li>
                    </ul>
                </dd>
            </dl>
      <dl class="mt5">

        <dt><a href="<?php echo urlMobile('member_order');?>">

          <h3><i class="mc-01"></i>我的订单</h3>

          <h5>查看全部订单<i class="arrow-r"></i></h5>

          </a></dt>

        <dd>

          <ul id="order_ul">

          </ul>

        </dd>

      </dl>

      <dl class="mt5">

        <dt><a href="<?php echo urlMobile('member_asset');?>">

          <h3><i class="mc-02"></i>我的财产</h3>

          <h5>查看全部财产<i class="arrow-r"></i></h5>

          </a></dt>

        <dd>

          <ul id="asset_ul">

          </ul>

        </dd>

      </dl>



      <dl class="mt5">

        <dt><a href="<?php echo urlMobile('member_address');?>">

          <h3><i class="mc-03"></i>收货地址管理</h3>

          <h5><i class="arrow-r"></i></h5>

          </a></dt>

      </dl>

      <dl style="border-top: solid 0.05rem #EEE;">

        <dt><a href="<?php echo urlMobile('member_evaluate','list');?>">

          <h3><i class="mc-05"></i>交易评价/晒单</h3>

          <h5><i class="arrow-r"></i></h5>

          </a></dt>

      </dl>

      <dl style="border-top: solid 0.05rem #EEE;">

        <dt><a href="<?php echo urlMobile('member_account');?>">

          <h3><i class="mc-04"></i>用户设置</h3>

          <h5><i class="arrow-r"></i></h5>

          </a></dt>

      </dl>

       <dl style="border-top: solid 0.05rem #EEE;">

        <dt><a href="<?php echo urlMobile('message','typelist');?>">

          <h3><i class="mc-07"></i>消息管理</h3>

          <h5><i class="arrow-r"></i></h5>

          </a></dt>

      </dl>

      <dl style="border-top: solid 0.05rem #EEE;">

        <dt><a href="<?php echo urlMobile('member_invite');?>"> <!--//20160906-->

          <h3><i class="mc-09"></i>我的推广连接</h3>

          <h5><i class="arrow-r"></i></h5>

          </a></dt>

      </dl>

      <dl style="border-top: solid 0.05rem #EEE;">

        <dt>

          <a href="<?php echo urlMobile('join');?>">

          <h3><i class="mc-06"></i>商家入驻</h3>

          <h5><i class="arrow-r"></i></h5>

          </a>

        </dt>

      </dl>

    </div>

  </div>

</div>

<script type="text/javascript">

  var ukey = "<?php echo $_SESSION['key'];?>";

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js?201511"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member.js"></script>

<?php require_once template('layout/member_footer');?>

