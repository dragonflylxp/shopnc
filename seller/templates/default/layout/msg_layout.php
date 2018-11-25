<?php defined('Inshopec') or exit('Access Invalid!');?>

<!doctype html>

<html>

<head>

<meta charset="UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="apple-mobile-web-app-capable" content="yes" />

<meta name="apple-touch-fullscreen" content="yes" />

<meta name="format-detection" content="telephone=no"/>

<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<meta name="format-detection" content="telephone=no" />

<meta name="msapplication-tap-highlight" content="no" />

<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />

<title><?php echo $output['html_title'];?></title>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/base.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/index.css">

<link rel="apple-touch-icon" href="images/touch-icon-iphone.png"/>

</head>

<body>

<header id="header" class="nctouch-product-header fixed">

  <div class="header-wrap">

    

    <div class="header-title">

      <h1><?php echo $output['html_title'];?></h1>

    </div>

   

  </div>



</header>

<div class="nctouch-main-layout_min mt20 mb20">

<div class="msg">

      <?php if($output['msg_type'] == 'error'){ ?>

      <div class="error_info info"></div>

        <?php }else { ?>

      <div class="succee_info info"></div>

        <?php } ?>

        <p><?php require_once($tpl_file);?></p>

</div>

</div>

<script type="text/javascript">

<?php if (!empty($output['url'])){

?>

  window.setTimeout("javascript:location.href='<?php echo $output['url'];?>'", <?php echo $time;?>);

<?php

}else{

?>

  window.setTimeout("javascript:history.back()", <?php echo $time;?>);

<?php

}?>

</script>



</body>

</html>

