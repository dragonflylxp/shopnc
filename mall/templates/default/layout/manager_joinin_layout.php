<?php defined('Inshopec') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
    <title>区域管理人--申请</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
    <meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
    <meta name="description" content="<?php echo $output['seo_description']; ?>" />
    <meta name="author" content="shopec">
    <meta name="copyright" content="shopec Inc. All Rights Reserved">
    <link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SHOP_TEMPLATES_URL;?>/css/store_joinin_new.css" rel="stylesheet" type="text/css">

    <style type="text/css">
        body { _behavior: url(<?php echo SHOP_TEMPLATES_URL;?>/css/csshover.htc);}</style>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
    <![endif]-->
    <script>
        var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
    </script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
    <script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/fastpay/js/layer.js"></script>
</head>
<body>
<div class="header">
    <h2 class="header_logo"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></h2>
    <ul class="header_menu">
        <li><a href="#" class="faq" onclick="layer.alert('请联系客服')"><i></i>管理员帮助指南</a></li>
        <li><a href="<?php echo urlMember('manager_login', 'logout');?>" class="login"><i></i>退出</a></li>
    </ul>
</div>
<div class="header_line"><span></span></div>
<?php require_once($tpl_file);?>
</body>
</html>