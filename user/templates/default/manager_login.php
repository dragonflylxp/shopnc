<html lang="en" class="no-js">

<head>

    <meta charset="utf-8">
    <title>Fullscreen Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS -->

    <!--<link rel="stylesheet" href="<?php /*echo LOGIN_TEMPLATES_URL;*/ ?>/login/css/reset.css">-->
    <link rel="stylesheet" href="<?php echo LOGIN_TEMPLATES_URL; ?>/login/css/supersized.css">
    <!--<link rel="stylesheet" href="<?php /*echo LOGIN_TEMPLATES_URL;*/ ?>/login/css/style.css">-->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

<body>
<style>
    .nc-login-layout {
        margin: 300px 20px !important;
        width: 100%;
        text-align: center;
    }

    .nc-login-centers {
        position: relative;
        z-index: 1;
        top: 0;
        margin: 0 auto;
        width: 100%;
    }

    .nc-pad-login {
        margin: 250px auto;
    }

    .nc-login-pad {
        background-color: #FFF;
        width: 440px;
        padding: 19px 19px 29px 19px;
        border: solid 1px #E6E6E6;
        border-radius: 5px;
        position: relative;
        z-index: 1;
    }

    .nc-login-pad .tabs-container {
        margin-top: 30px;
        padding: 0 20px 20px 20px;
    }


</style>

<div class="nc-login-centers">

    <div class="nc-login-pad nc-pad-login">
        <div class="arrow"></div>
        <div class="nc-login-mode">
            <ul class="tabs-nav" style="text-align:center;">
                <li><a href="#">区域管理员登录<i></i></a></li>
            </ul>
            <div id="tabs_container" class="tabs-container">
                <div id="default" class="tabs-content">
                    <form id="login_form" class="nc-login-form" method="post"
                          action="<?php echo urlLogin('manager_login', 'index'); ?>">
                        <?php Security::getToken(); ?>
                        <input type="hidden" name="form_submit" value="ok"/>
                        <input name="nchash" type="hidden" value="<?php echo getNchash(); ?>"/>
                        <dl>
                            <dt>账&nbsp;&nbsp;&nbsp;号：</dt>
                            <dd>
                                <input type="text" class="text" autocomplete="off" name="user_name"
                                       placeholder="可使用区域管理人账号登录" id="user_name">
                            </dd>
                        </dl>
                        <dl>
                            <dt>密&nbsp;&nbsp;&nbsp;码：</dt>
                            <dd>
                                <input type="password" class="text" name="password" autocomplete="off"
                                       placeholder="6-20个大小写英文字母,符号或数字" id="password">
                            </dd>
                        </dl>
                        <?php if (C('captcha_status_login') == '1') { ?>
                            <div class="code-div mt15">
                                <dl>
                                    <dt><?php echo $lang['login_index_checkcode']; ?>：</dt>
                                    <dd>
                                        <input type="text" name="captcha" autocomplete="off" class="text w100"
                                               placeholder="请输入验证码" id="captcha" size="10"/>
                                    </dd>
                                </dl>
                                <span><img
                                        src="index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash(); ?>"
                                        name="codeimage" id="codeimage"> <a class="makecode" href="javascript:void(0)"
                                                                            onclick="javascript:document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash(); ?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode']; ?></a></span>
                            </div>
                        <?php } ?>
                        <div class="submit-div">
                            <!--登录按钮-->
                            <input type="submit" class="submit" value="登录">
                            <!--取得登录成功后返回的地址-->
                            <input type="hidden" value="<?php echo $_GET['ref_url'] ?>" name="ref_url">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<div class="nc-login-layout">-->
<!--    -->
<!--</div>-->


<!-- Javascript -->
<!--<script src="--><?php //echo LOGIN_TEMPLATES_URL; ?><!--/login/js/jquery-1.8.2.min.js"></script>-->

<script>
    $(function () {
        //初始化Input的灰色提示信息
        <!--$('input[tipMsg]').inputTipText({pwd:'password'});-->
        //登录方式切换
        $('.nc-login-mode').tabulous({
            effect: 'flip'//动画反转效果
        });
        var div_form = '#default';
        $(".nc-login-mode .tabs-nav li a").click(function (){
            if ($(this).attr("href") !== div_form) {
                div_form = $(this).attr('href');
                $("" + div_form).find(".makecode").trigger("click");
            }
        });

        $("#login_form").validate({
            errorPlacement: function (error, element) {
                var error_td = element.parent('dd');
                error_td.append(error);
                element.parents('dl:first').addClass('error');
            },
            success: function (label) {
                label.parents('dl:first').removeClass('error').find('label').remove();
            },
            submitHandler: function (form) {
                ajaxpost('login_form', '', '', 'onerror');
            },
            onkeyup: false,
            rules: {
                user_name: "required",
                password: "required"
                <?php if(C('captcha_status_login') == '1') { ?>
                , captcha: {
                    required: true,
                    remote: {
                        url: 'index.php?con=seccode&fun=check&nchash=<?php echo getNchash();?>',
                        type: 'get',
                        data: {
                            captcha: function () {
                                return $('#captcha').val();
                            }
                        },
                        complete: function (data) {
                            if (data.responseText == 'false') {
                                document.getElementById('codeimage').src = 'index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();
                            }
                        }
                    }
                }
                <?php } ?>
            },
            messages: {
                user_name: "<i class='icon-exclamation-sign'></i>请输入已注册的用户名或手机号",
                password: "<i class='icon-exclamation-sign'></i><?php echo $lang['login_index_input_password'];?>"
                <?php if(C('captcha_status_login') == '1') { ?>
                , captcha: {
                    required: '<i class="icon-remove-circle" title="<?php echo $lang['login_index_input_checkcode'];?>"></i>',
                    remote: '<i class="icon-remove-circle" title="<?php echo $lang['login_index_input_checkcode'];?>"></i>'
                }
                <?php } ?>
            }
        });

        // 勾选自动登录显示隐藏文字
        $('input[name="auto_login"]').click(function () {
            if ($(this).attr('checked')) {
                $(this).attr('checked', true).next().show();
            } else {
                $(this).attr('checked', false).next().hide();
            }
        });
    });
</script>

<script src="<?php echo LOGIN_TEMPLATES_URL; ?>/login/js/supersized.3.2.7.min.js"></script>

<script>
    jQuery(function ($) {

        $.supersized({

            // Functionality
            slide_interval: 4000,    // Length between transitions
            transition: 1,    // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
            transition_speed: 1000,    // Speed of transition
            performance: 1,    // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)

            // Size & Position
            min_width: 0,    // Min width allowed (in pixels)
            min_height: 0,    // Min height allowed (in pixels)
            vertical_center: 1,    // Vertically center background
            horizontal_center: 1,    // Horizontally center background
            fit_always: 0,    // Image will never exceed browser width or height (Ignores min. dimensions)
            fit_portrait: 1,    // Portrait images will not exceed browser height
            fit_landscape: 0,    // Landscape images will not exceed browser width

            // Components
            slide_links: 'blank',    // Individual links for each slide (Options: false, 'num', 'name', 'blank')
            slides: [    // Slideshow Images
                {image: '<?php echo LOGIN_TEMPLATES_URL;?>/login/img/backgrounds/1.jpg'},
                {image: '<?php echo LOGIN_TEMPLATES_URL;?>/login/img/backgrounds/2.jpg'},
                {image: '<?php echo LOGIN_TEMPLATES_URL;?>/login/img/backgrounds/3.jpg'}
            ]

        });

    });
</script>
</body>
</html>

