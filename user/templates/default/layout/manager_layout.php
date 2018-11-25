<?php defined('Inshopec') or exit('Access Invalid!');?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
    <title>区域管理人中心</title>
<style type="text/css">
    body {
        _behavior: url(<?php echo MEMBER_TEMPLATES_URL;
?>/css/csshover.htc);
    }

</style>



<!--后台css  管理员资料页面-->
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/manager.css" rel="stylesheet" type="text/css">

<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo MEMBER_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo MEMBER_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">


<style>
.ncm-member-info dd {
    height: 22px;
    }
    .ncm-set-menu{
        width: 719px;
    }
    .ncm-member-info{
    width: 450px;
    }
</style>

<script>
    var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo MEMBER_RESOURCE_SITE_URL;?>/layer/layer.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js"></script>

<script>
    //sidebar-menu
    $(document).ready(function() {
        $.each($(".side-menu > a"), function() {
            $(this).click(function() {
                var ulNode = $(this).next("ul");
                if (ulNode.css('display') == 'block') {
                    $.cookie(COOKIE_PRE+'Mmenu_'+$(this).attr('key'),1);
                } else {
                    $.cookie(COOKIE_PRE+'Mmenu_'+$(this).attr('key'),null);
                }
                ulNode.slideToggle();
                if ($(this).hasClass('shrink')) {
                    $(this).removeClass('shrink');
                } else {
                    $(this).addClass('shrink');
                }
            });
        });
        $.each($(".side-menu-quick > a"), function() {
            $(this).click(function() {
                var ulNode = $(this).next("ul");
                ulNode.slideToggle();
                if ($(this).hasClass('shrink')) {
                    $(this).removeClass('shrink');
                } else {
                    $(this).addClass('shrink');
                }
            });
        });
    });
    $(function() {
        //展开关闭常用菜单设置
        $('.set-btn').bind("click",
            function() {
                $(".set-container-arrow").show("fast");
                $(".set-container").show("fast");
            });
        $('[nctype="closeCommonOperations"]').bind("click",
            function() {
                $(".set-container-arrow").hide("fast");
                $(".set-container").hide("fast");
            });

        $('dl[nctype="checkcCommonOperations"]').find('input').click(function(){
            var _this = $(this);
            var _dd = _this.parents('dd:first');
            var _type = _this.is(':checked') ? 'add' : 'del';
            var _value = _this.attr('name');
            var _operations = $('[nctype="commonOperations"]');

            // 最多添加5个
            if (_operations.find('li').length >= 5 && _type == 'add') {
                showError('最多只能添加5个常用选项。');
                return false;
            }
            $.getJSON('<?php echo urlShop('member', 'common_operations')?>', {type : _type, value : _value}, function(data){
                if (data) {
                    if (_type == 'add') {
                        _dd.addClass('checked');
                        if (_operations.find('li').length == 0) {
                            _operations.fadeIn('slow');
                        }
                        _operations.find('ul').append('<li style="display : none;" nctype="' + _value + '"><a href="' + _this.attr('data-value') + '">' + _this.attr('data-name') + '</a></li>');
                        _operations.find('li[style]').fadeIn('slow');
                    } else {
                        _dd.removeClass('checked');
                        _operations.find('li[nctype="' + _value + '"]').fadeOut('slow', function(){
                            $(this).remove();
                            if (_operations.find('li').length == 0) {
                                _operations.fadeOut('slow');
                            }
                        });
                    }
                }
            });
        });
    });

</script>
<!--顶部导航栏-->
<div class="public-top-layout w">
    <div class="topbar wrapper">
        <div class="user-entry">
            <span>你好,欢迎来到区域管理人中心</span><span><a href="<?php echo urlMember('manager_login', 'logout');?>">&nbsp&nbsp退出</a></span>
        </div>
    </div>
</div>
<div class="ncm-container">
    <div class="ncm-header">
        <div class="ncm-header-top">
            <!--管理员信息-->
            <div class="ncm-member-info">
                <div class="avatar"><img src="<?php echo getMemberAvatar('');?>">
                        <div class="frame"></div>
                </div>
                <dl>
                    <dd>公司名称&nbsp:&nbsp&nbsp<?php echo $output['manager_info']['company_name'];?></dd>
                    <dd>管理等级&nbsp:&nbsp&nbsp<?php echo $output['manager_info']['grade'];?></dd>
                    <dd>管理区域&nbsp:&nbsp&nbsp<?php echo $output['manager_info']['area'];?></dd>
                    <dd>审核状态&nbsp:
                        <?php if ($output['manager_info']['apply_state'] == 10){ ?>
                        <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlMember('manager_index','edit_manager');?>');">资料审核未提交</div>
                        <?php }elseif($output['manager_info']['apply_state'] == 20){ ?>
                        <div class="nc-grade-mini" style="">资料正在审核中</div>
                        <?php }elseif($output['manager_info']['apply_state'] == 30){ ?>
                        <div class="nc-grade-mini" style="background-color: #00d6b2">资料审核已通过</div>
                        <?php }elseif($output['manager_info']['apply_state'] == 40){ ?>
                        <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlMember('manager_index','edit_manager');?>');">资料审核未通过</div>
                        <?php }?>
                    </dd>
                    <dd>上次登录&nbsp:&nbsp&nbsp<?php echo date('Y年m月d日 H:i:s',$output['manager_info']['manager_old_login_time']);?></dd>
                </dl>
            </div>
            <!--头部导航-->
            <div class="ncm-set-menu">
                <dl class="zhaq">
                    <dt>账户资料</dt>
                    <dd>
                        <ul>
                            <li><a href="<?php echo urlMember('manager_index', 'modify_pwd');?>" ><span class="zhaq01"></span><sub></sub>
                                    <h5>密码修改</h5>
                                </a> </li>
                            <li><a href="<?php echo urlMember('manager_index', 'edit_manager');?>"><span class="zhcc04"></span>
                                    <h5>管理人资料</h5>
                                </a></li>
                        </ul>
                    </dd>
                </dl>
                <dl class="zhcc">
                    <dt>账户财产</dt>
                    <dd>
                        <ul>
                            <li><a href="<?php echo urlMember('manager_index', 'index');?>"> <span class="zhcc01"></span>
                                    <h5>实物结算单</h5>
                                </a> </li>
                            <li><a href="<?php echo urlMember('manager_index', 'vr_bill');?>"> <span class="zhcc02"></span>
                                    <h5>虚拟结算单</h5>
                                </a></li>
                        </ul>
                    </dd>
                </dl>
                <dl class="xgsz">
                    <dt>相关设置</dt>
                    <dd>
                        <ul class="trade-function-03">
                            <li><a href="<?php echo urlMember('manager_login', 'logout');?>"><span class="xgsz01"></span>
                                    <h5>切换区域管理员</h5>
                                </a></li>
                            <li><a href="<?php echo urlMember('manager_login', 'logout');?>"><span class="xgsz01"></span>
                                    <h5>退出登录</h5>
                                </a></li>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>
        <!--中部导航-->
        <div class="ncm-header-nav">
            <ul class="nav-menu">
                <li class="shop"><a href="" class="<?php if($_GET['fun'] == 'index' ||$_GET['fun'] == 'vr_bill'){ echo 'current';}; ?>">财产中心<i></i></a>
                    <div class="sub-menu">
                        <dl>
                            <dt><a href="javascript:volid(0);" style="color: #398EE8;">财产管理</a></dt>
                            <dd><a href="<?php echo urlMember('manager_index', 'index');?>">实物结算单</a></dd>
                            <dd><a href="<?php echo urlMember('manager_index', 'vr_bill');?>">虚拟结算单</a></dd>
                        </dl>

                    </div>
                </li>
               <li><a href="" class="<?php if($_GET['fun'] == 'modify_pwd'||$_GET['fun'] == 'edit_manager'||$_GET['fun'] == 'manager_details'){ echo 'current';}; ?>">账户资料</a> <div class="sub-menu">
                        <dl>
                            <dt><a href="javascript:volid(0);" style="color: #398EE8;">资料管理</a></dt>
                            <dd><a href="<?php echo urlMember('manager_index', 'modify_pwd');?>">密码修改</a></dd>
                            <dd><a href="<?php echo urlMember('manager_index', 'edit_manager');?>">管理人资料</a></dd>
                        </dl>
                    </div></li>

            </ul>
            <script>
                $(function() {
                    var _wrap = $('ul.line');
                    var _interval = 2000;
                    var _moving;
                    _wrap.hover(function() {
                            clearInterval(_moving);
                        },
                        function() {
                            _moving = setInterval(function() {
                                    var _field = _wrap.find('li:first');
                                    var _h = _field.height();
                                    _field.animate({
                                            marginTop: -_h + 'px'
                                        },
                                        600,
                                        function() {
                                            _field.css('marginTop', 0).appendTo(_wrap);
                                        })
                                },
                                _interval)
                        }).trigger('mouseleave');
                });
            </script>
        </div>
    </div>
<!--    左侧导航-->
    <div class="left-layout">
        <ul id="sidebarMenu" class="ncm-sidebar">
            <?php if (!empty($output['menu_list'])) {?>
                <?php foreach ($output['menu_list'] as $key => $value) {?>
                    <li class="side-menu"><a href="javascript:void(0)" key="<?php echo $key;?>" <?php if (cookie('Mmenu_'.$key) == 1) echo 'class="shrink"';?>>
                            <h3><?php echo $value['name'];?></h3>
                        </a>
                        <?php if (!empty($value['child'])) {?>
                            <ul <?php if (cookie('Mmenu_'.$key) == 1) echo 'style="display:none"';?>>
                                <?php foreach ($value['child'] as $key => $val) {?>
                                    <li <?php if ($key == $_GET['fun']) {?>class="selected"<?php }?>><a href="<?php echo $val['url'];?>"><?php echo $val['name'];?></a></li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </li>
                <?php }?>
            <?php }?>
        </ul>
    </div>
    <!--中心页面-->
    <div class="right-layout">
        <?php require_once($tpl_file);?>
    </div>
    <div class="clear"></div>
</div>
</body></html>
<?php if($_GET['fun'] == 'manager_details'){ ?>
<script>
    $(function() {

    $("a:contains('管理人信息')").closest('li').addClass("selected");

    });
</script>
<?php  } ?>