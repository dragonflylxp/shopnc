<link href="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<link href="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/css/home_login.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<style type="text/css">
.category { display: block !important; }
</style>
<div class="home-focus-layout">
    <?php echo $output['web_html']['index'];?>
    <script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/taglibs.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/tabulous.js" charset="utf-8"></script>
	<div class="nc-login-layout">
  <div class="nc-login">
    <?php if($_SESSION['member_id'] > 0){ ?>
        <div class="nc-login-enter">
        	<div class="nc-login-enter-info"><span class="fl">已登录账户：</span><a href="<?php echo urlLogin('login','logout');?>" class="fr">[退出]</a></div>
            <div class="clear"></div>
            <div class="nc-login-enter-img"><img src="<?php echo getMemberAvatarForID($output['member_info']['member_id']);?>" alt=""/></div>
            <h1 class="nc-login-enter-name"><?php echo $output['member_info']['member_name']?></h1>
            <a href="<?php echo urlDistribute('distri_center','home');?>" target="_blank" class="nc-login-enter-btn">进入分销中心</a>
            <div class="nc-login-enter-link">
            	<a href="<?php echo urlDistribute('distri_goods','index');?>">分销商品</a>
                <a href="<?php echo urlDistribute('distri_order','index');?>">分销订单</a>
                <a href="<?php echo urlDistribute('distri_bill','index');?>">结算管理</a>
            </div>
            <div class="clear"></div>
            <?php echo $output['web_html']['index_right_pic']; ?>
        </div>
    <?php }else{?>    
    <div class="nc-login-mode">
      <ul class="tabs-nav">
        <li><a href="#default">用户登录<i></i></a></li>
        <?php if (C('sms_login') == 1){?>
        <li><a href="#mobile">手机动态码登录<i></i></a></li>
        <?php }?>
      </ul>
      <div id="tabs_container" class="tabs-container">
        <div id="default" class="tabs-content">
          <form id="login_form" class="nc-login-form" method="post" action="<?php echo urlLogin('login','index')?>">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
            <input name="is_distri_login" type="hidden" value="yes"/>
            <dl>
              <dt>账&nbsp;&nbsp;&nbsp;号：</dt>
              <dd>
                <input type="text" class="text" autocomplete="off"  name="user_name" tipMsg="可使用已注册的用户名或手机号登录" id="user_name"  >
              </dd>
            </dl>
            <dl>
              <dt>密&nbsp;&nbsp;&nbsp;码：</dt>
              <dd>
                <input type="password" class="text" name="password" autocomplete="off" tipMsg="6-20个大小写英文字母、符号或数字" id="password">
              </dd>
            </dl>
                        <div class="code-div mt15">
              <dl>
                <dt style="font-size:13px; width:33%">验证码：</dt>
                <dd>
                  <input type="text" name="captcha" autocomplete="off" class="text w100" tipMsg="输入验证码" id="captcha" size="10" />                  
                </dd>
              </dl>
              <span><img src="index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>" name="codeimage" id="codeimage"> <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();">看不清，换一张</a></span></div>
                        <div class="handle-div">
            <span class="auto"><input type="checkbox" class="checkbox" name="auto_login" value="1">七天自动登录<em style="display: none;">请勿在公用电脑上使用</em></span>
            <a class="forget" href="<?php echo urlLogin('login', 'forget_password');?>">忘记密码？</a></div>
            <div class="submit-div">
              <input type="submit" class="submit" value="登&nbsp;&nbsp;&nbsp;录">
              <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
            </div>
          </form>
        </div>
        <?php if (C('sms_login') == 1){?>
        <div id="mobile" class="tabs-content">
          <form id="post_form" method="post" class="nc-login-form" action="<?php echo urlLogin('connect_sms', 'login');?>">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
            <input name="is_distri_login" type="hidden" value="yes"/>
            <dl>
              <dt>手机号：</dt>
              <dd>
                <input name="phone" type="text" class="text" id="phone" tipMsg="可填写已注册的手机号接收短信" autocomplete="off" value="" >              
              </dd>
            </dl>
            <div class="code-div">
              <dl>
                <dt style="font-size:13px; width:33%">验证码：</dt>
                <dd>
                  <input type="text" name="captcha" class="text w100" tipMsg="输入验证码" id="image_captcha" size="10" />
                 
                </dd>
              </dl>
              <span><img src="index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>" title="看不清，换一张" name="codeimage" id="sms_codeimage"> <a class="makecode" href="javascript:void(0);" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();">看不清，换一张</a></span> </div>
            
            <div class="tiptext" id="sms_text">正确输入上方验证码后，点击<span> <a href="javascript:void(0);" onclick="get_sms_captcha('2')"><i class="icon-mobile-phone"></i>发送手机动态码</a></span>，查收短信将系统发送的“6位手机动态码”输入到下方验证后登录。</div>
            <dl>
              <dt>动态码：</dt>
              <dd>
                <input type="text" name="sms_captcha" autocomplete="off" class="text" tipMsg="输入6位手机动态码" id="sms_captcha" size="15" />
                
              </dd>
            </dl>
            <div class="submit-div">
                <input type="submit" id="submit" class="submit" value="登&nbsp;&nbsp;&nbsp;录">
                <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
              </div>
          </form>
        </div>
        <?php }?>
       </div>
    </div>
    <?php }?>
  </div>
  <div class="clear"></div>
</div>
</div>
<div class="clear"></div>
<?php echo $output['web_html']['index_pic'];?>
<script>
$(function(){
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password'});
	//登录方式切换
	$('.nc-login-mode').tabulous({
		 effect: 'flip'//动画反转效果
	});	
	var div_form = '#default';
	$(".nc-login-mode .tabs-nav li a").click(function(){
        if($(this).attr("href") !== div_form){
            div_form = $(this).attr('href');
            $(""+div_form).find(".makecode").trigger("click");
    	}
	});
	
	$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('login_form', '', '', 'onerror');
    	},
        onkeyup: false,
		rules: {
			user_name: "required",
			password: "required"
			            ,captcha : {
                required : true,
                remote   : {
                    url : 'index.php?con=seccode&fun=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();
                        }
                    }
                }
            }
					},
		messages: {
			user_name: "<i class='icon-exclamation-sign'></i>请输入已注册的用户名或手机号",
			password: "<i class='icon-exclamation-sign'></i>密码不能为空"
			            ,captcha : {
                required : '<i class="icon-remove-circle" title="验证码不能为空"></i>',
				remote	 : '<i class="icon-remove-circle" title="验证码不能为空"></i>'
            }
					}
	});

    // 勾选自动登录显示隐藏文字
    $('input[name="auto_login"]').click(function(){
        if ($(this).attr('checked')){
            $(this).attr('checked', true).next().show();
        } else {
            $(this).attr('checked', false).next().hide();
        }
    });
});
</script>
<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/connect_sms.js" charset="utf-8"></script>
<script>
$(function(){
	$("#post_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('post_form', '', '', 'onerror');
    	},
        onkeyup: false,
		rules: {
			phone: {
                required : true,
                mobile : true
            },
			captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?con=seccode&fun=check&nchash=1d55dd06',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#image_captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('sms_codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=1d55dd06&t=' + Math.random();
                        }
                    }
                }
            },
			sms_captcha: {
                required : function(element) {
                    return $("#image_captcha").val().length == 4;
                },
                minlength: 6
            }
		},
		messages: {
			phone: {
                required : '<i class="icon-exclamation-sign"></i>请输入正确的手机号',
                mobile : '<i class="icon-exclamation-sign"></i>请输入正确的手机号'
            },
			captcha : {
                required : '<i class="icon-remove-circle" title="请输入正确的验证码"></i>',
                minlength: '<i class="icon-remove-circle" title="请输入正确的验证码"></i>',
				remote	 : '<i class="icon-remove-circle" title="请输入正确的验证码"></i>'
            },
			sms_captcha: {
                required : '<i class="icon-exclamation-sign"></i>请输入六位短信动态码',
                minlength: '<i class="icon-exclamation-sign"></i>请输入六位短信动态码'
            }
		}
	});
});
</script>