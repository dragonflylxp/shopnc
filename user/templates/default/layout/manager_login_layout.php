<?php defined('Inshopec') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
    <title>区域管理人中心--登录</title>
    <meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
    <meta name="description" content="<?php echo $output['seo_description']; ?>" />

    <style type="text/css">
        body { _behavior: url(<?php echo LOGIN_TEMPLATES_URL;
?>/css/csshover.htc);
        }
    </style>
    <link href="<?php echo LOGIN_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LOGIN_TEMPLATES_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LOGIN_TEMPLATES_URL;?>/css/home_login.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LOGIN_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo LOGIN_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
    <![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo RESOURCE_SITE_URL_HTTPS;?>/js/html5shiv.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL_HTTPS;?>/js/respond.min.js"></script>
    <![endif]-->
    <script>
        var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
    </script>
    <script src="<?php echo RESOURCE_SITE_URL_HTTPS;?>/js/jquery.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL_HTTPS;?>/js/jquery-ui/jquery.ui.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL_HTTPS;?>/js/common.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL_HTTPS;?>/js/jquery.validation.min.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL_HTTPS;?>/js/dialog/dialog.js" id="dialog_js"></script>
    <script src="<?php echo LOGIN_RESOURCE_SITE_URL?>/js/taglibs.js"></script>
    <script src="<?php echo LOGIN_RESOURCE_SITE_URL?>/js/tabulous.js"></script>
</head>
<body>
<!-- PublicHeadLayout End -->
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php require_once($tpl_file);?>
</body>
</html>
