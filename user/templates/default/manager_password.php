<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu');?>
    </div>

    <div class="ncm-default-form">
        <form method="post" id="password_form" name="password_form">
            <input type="hidden" name="form_submit" value="ok"  />
            <dl>
                <dt><i class="required">*</i>旧密码：</dt>
                <dd>
                    <input type="password"  maxlength="40" class="password" name="oldpassword" id="oldpassword"/>
                </dd>
            </dl>
            <dl>
                <dt><i class="required">*</i>设置新密码：</dt>
                <dd>
                    <input type="password"  maxlength="40" class="password" name="password" id="password"/>
                    <label for="password" generated="true" class="error"></label>
                    <p class="hint">6-20位字符，可由英文、数字及标点符号组成。</p></dd>
            </dl>
            <dl>
                <dt><i class="required">*</i>确认新密码：</dt>
                <dd>
                    <input type="password" maxlength="40" class="password" name="confirm_password" id="confirm_password" />
                    <label for="confirm_password" generated="true" class="error"></label>
                </dd>
            </dl>
            <dl class="bottom">
                <dt>&nbsp;</dt>
                <dd><label class="submit-border">
                        <input type="button" class="submit" value="确认提交" /></label>
                </dd>
            </dl>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#password_form').validate({
            submitHandler:function(form){
                ajaxpost('password_form', '', '', 'onerror')
            },
            rules : {
                password : {
                    required   : true,
                    minlength  : 6,
                    maxlength  : 20
                },
                confirm_password : {
                    required   : true,
                    equalTo    : '#password'
                }
            },
            messages : {
                password  : {
                    required  : '<i class="icon-exclamation-sign"></i>请正确输入密码',
                    minlength : '<i class="icon-exclamation-sign"></i>密码长度不能小于6位',
                    maxlength : '<i class="icon-exclamation-sign"></i>密码长度不能大于20位'
                },
                confirm_password : {
                    required   : '<i class="icon-exclamation-sign"></i>请正确输入密码',
                    equalTo    : '<i class="icon-exclamation-sign"></i>两次密码输入不一致'
                }
            }
        });
        $(".submit").click(function(){
            var url = "<?php echo MEMBER_SITE_URL;?>/index.php?con=manager_index&fun=modify_pwd";
            var param = $("#password_form").serialize();
                $.post(url,param,function(data){
                    if(data.state){
                        layer.alert(data.info, {
                            skin: '' //样式类名
                            ,closeBtn: 0
                        }, function(){
                            var url = '<?php echo urlMember('manager_index','index');?>'
                            window.location.href = url;
                        });
                    }else{
                        layer.alert(data.info);
                    }
                },'json');
        })
    });
</script>
