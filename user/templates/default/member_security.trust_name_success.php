<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu');?>
    </div>
    <div class="ncm-security-user">
        <h3>您的认证信息</h3>
        <div class="user-avatar"><span><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>"></span></div>
        <div class="user-intro">
            <dl>
                <dt>登录账号：</dt>
                <dd><?php echo $output['member_info']['member_name'];?></dd>
            </dl>
            <dl>
                <dt>真实姓名：</dt>
                <dd><?php echo encryptShow($output['member_info']['member_truename'],1,3);?></dd>
            </dl>
            <dl>
                <dt>身份证号码：</dt>
                <dd><?php echo encryptShow(_decrypt($output['member_info']['id_card']),2,16);?></dd>
            </dl>
            <dl>
                <dt>上次登录：</dt>
                <dd><?php echo date('Y年m月d日 H:i:s',$output['member_info']['member_old_login_time']);?>&#12288;|&#12288;IP地址:<?php echo $output['member_info']['member_old_login_ip'];?>&nbsp;<span>（不是您登录的？请立即<a href="index.php?con=member_security&fun=auth&type=modify_pwd">“更改密码”</a>）。</span></dd>
            </dl>
        </div>
    </div>
</div>
