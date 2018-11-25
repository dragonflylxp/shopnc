<?php defined('Inshopec') or exit('Access Invalid!');?>
<div class="wrap">

    <div class="alert alert-success">
        <h4>操作提示：</h4>
        <ul>
            <li>1. 查看管理员信息,需要先发送安全验证码</li>
            <li>2. 在两小时内不需要再次验证</li>
            <li>3. 退出后需要再次验证</li>
            <li>4. 请正确输入下方图形验证码，如看不清可点击图片进行更换，输入完成后进行下一步操作。</li>
            <li>5. 收到安全验证码后，请在30分钟内完成验证。</li>
        </ul>
    </div>
    <div class="ncm-default-form">
        <form method="post" id="auth_form" action="index.php?con=manager_index&fun=edit_manager">
            <input type="hidden" name="form_submit" value="ok" />
            <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
            <dl>
                <dt><i class="required">*</i>选择身份认证方式：</dt>
                <dd><p>
                        <select name="auth_type" id="auth_type">
                            <?php if ($output['member_info']['member_mobile']) {?>
                                <option value="mobile">手机 [<?php echo encryptShow($output['member_info']['member_mobile'],4,4);?>]</option>
                            <?php } ?>
                            <?php if ($output['member_info']['member_email']) {?>
                                <option value="email">邮箱 [<?php echo encryptShow($output['member_info']['member_email'],4,4);?>]</option>
                            <?php } ?>
                        </select>
                        <a href="javascript:void(0);" id="send_auth_code" class="ncbtn ml5"><span id="sending" style="display:none">正在</span><span class="send_success_tips"><strong id="show_times" class="red mr5"></strong>秒后再次</span>获取安全验证码</a></p>
                    <p class="send_success_tips hint mt10">“安全验证码”已发出，请注意查收，请在<strong>“30分种”</strong>内完成验证。</p>
                </dd>
            </dl>
            <dl>
                <dt><i class="required">*</i>请输入安全验证码：</dt>
                <dd>
                    <input type="text" class="text"  maxlength="6" value="" name="auth_code" size="10" id="auth_code" autocomplete="off" />
                    <label for="email" generated="true" class="error"></label>
                </dd>
            </dl>
            <dl>
                <dt><i class="required">*</i>图形验证码：</dt>
                <dd>
                    <input type="text" name="captcha" class="text" id="captcha" maxlength="4" size="10" autocomplete="off" />
                    <img src="index.php?con=seccode&fun=makecode&nchash=<?php echo getNchash();?>" name="codeimage" border="0" id="codeimage" class="ml5 vm"><a href="javascript:void(0)" class="ml5 blue" onclick="javascript:document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();">看不清？换张图</a>
                    <label for="captcha" generated="true" class="error"></label>
                </dd>
            </dl>
            <dl class="bottom">
                <dt>&nbsp;</dt>
                <dd>
                    <label class="submit-border">
                        <input type="button" class="submit" value="确认，进入下一步" />
                    </label>
                </dd>
            </dl>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('.send_success_tips').hide();
    var ALLOW_SEND = true;
    $(function(){
        $('.submit').on('click',function(){
            if (!$('#auth_form').valid()){
                document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
            } else {
                var url = "<?php echo MEMBER_SITE_URL;?>/index.php?con=manager_index&fun=edit_manager";
                var param = $("#auth_form").serialize();
                $.post(url,param,function(data){
                    if(data.state){
                        layer.alert(data.info, {
                            skin: '' //样式类名
                            ,closeBtn: 0
                        },function(){
                            var url = '<?php echo urlMember('manager_index','manager_details');?>'
                            window.location.href = url;
                        });
                    }else{
                        layer.alert(data.info);
                    }
                },'json');
            }
        });
        function StepTimes() {
            $num = parseInt($('#show_times').html());
            $num = $num - 1;
            $('#show_times').html($num);
            if ($num <= 0) {
                ALLOW_SEND = !ALLOW_SEND;
                $('.send_success_tips').hide();
            } else {
                setTimeout(StepTimes,1000);
            }
        }
        $('#send_auth_code').on('click',function(){
            if (!ALLOW_SEND) return;
            ALLOW_SEND = !ALLOW_SEND;
            $('#sending').show();
            $.getJSON('index.php?con=manager_index&fun=send_auth_code',{type:$('#auth_type').val()},function(data){
                if (data.state == 'true') {
                    $('#sending').hide();
                    $('#show_times').html(60);
                    $('.send_success_tips').show();
                    setTimeout(StepTimes,1000);
                } else {
                    ALLOW_SEND = !ALLOW_SEND;
                    $('#sending').hide();
                    layer.alert(data.msg);
                }
            });
        });

        $('#auth_form').validate({
            rules : {
                auth_code : {
                    required : true,
                    maxlength : 6,
                    minlength : 6,
                    digits : true
                },
                captcha : {
                    required : true,
                    minlength: 4,
                    remote   : {
                        url : 'index.php?con=seccode&fun=check&nchash=<?php echo getNchash();?>',
                        type: 'get',
                        data:{
                            captcha : function(){
                                return $('#captcha').val();
                            }
                        }
                    }
                }
            },
            messages : {
                auth_code : {
                    required : '<i class="icon-exclamation-sign"></i>请正确输入验证码',
                    maxlength : '<i class="icon-exclamation-sign"></i>请正确输入验证码',
                    minlength : '<i class="icon-exclamation-sign"></i>请正确输入验证码',
                    digits : '<i class="icon-exclamation-sign"></i>请正确输入验证码'
                },
                captcha : {
                    required : '<i class="icon-exclamation-sign"></i>请正确输入图形验证码',
                    minlength: '<i class="icon-exclamation-sign"></i>请正确输入图形验证码',
                    remote	 : '<i class="icon-exclamation-sign"></i>请正确输入图形验证码'
                }
            }
        });
    });
</script> 
