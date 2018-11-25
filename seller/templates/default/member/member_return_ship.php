<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <span class="header-title">

    <h1>退款详情</h1>

    </span>  

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

        <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

  <div class="special-tips">

    <p>发货<span id="delayDay"></span>天后，当商家选择未收到则要进行延迟时间操作；如果超过<span id="confirmDay"></span>天不处理按弃货处理，直接由管理员确认退款。</p>

  </div>

  <form>

    <div class="nctouch-inp-con">

      <ul class="form-box">

        <li class="form-item">

          <h4>物流公司</h4>

          <div class="input-box">

            <select id="express" class="select" name="express_id ">

            </select>

            <i class="arrow-down"></i> </div>

        </li>

        <li class="form-item">

          <h4>物流单号</h4>

          <div class="input-box">

            <input type="text" class="inp" name="invoice_no" placeholder="请填写物流单号">

          </div>

        </li>

      </ul>

    </div>

    <a class="btn-l mt5 mb5">确认发货</a>

  </form>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_return_ship.js"></script>

