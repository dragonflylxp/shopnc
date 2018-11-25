<?php defined('Inshopec') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">

</head>

<body>

<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php if (!empty($output['store_closed'])) { ?>
<div class="store-closed"><i class="icon-warning-sign"></i>
  <dl>
    <dt>您的店铺已被平台关闭</dt>
    <dd>关闭原因：<?php echo $output['store_close_info'];?></dd>
    <dd>在此期间，您的店铺以及商品将无法访问；如果您有异议或申诉请及时联系平台管理。</dd>
  </dl>
</div>
<?php } ?>

</header>

<div class="wrapper">
  <?php require_once($tpl_file); ?>
</div>



</body>
</html>
