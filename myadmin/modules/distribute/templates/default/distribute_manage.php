<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>分销设置</h3>
                <h5>分销基础信息设置</h5>
            </div>
        </div>
    </div>
    <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?con=manage&fun=manage_save">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="distribute_isuse">分销开关</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="isuse_1" class="cb-enable <?php if($output['setting']['distribute_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><?php echo $lang['nc_open'];?></label>
                        <label for="isuse_0" class="cb-disable <?php if($output['setting']['distribute_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><?php echo $lang['nc_close'];?></label>
                        <input type="radio" id="isuse_1" name="distribute_isuse" value="1" <?php echo $output['setting']['distribute_isuse']==1?'checked=checked':''; ?>>
                        <input type="radio" id="isuse_0" name="distribute_isuse" value="0" <?php echo $output['setting']['distribute_isuse']==0?'checked=checked':''; ?>>
                    </div>
                    <p class="notic">开启和关闭分销平台，关闭分销后通过原有分销链接进行购买产生的订单按普通订单处理，不计入分销订单也不产生佣金，已生成的分销订单继续结算</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="class_image"><?php echo '分销市场'.'LOGO';?></label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show"><span class="show">
            <?php if(empty($output['setting']['distribute_logo'])) { ?>
                <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_DISTRIBUTE.DS.'default_logo_image.png';?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_DISTRIBUTE.DS.'default_logo_image.png';?>>')" onMouseOut="toolTip()"></i></a>
            <?php } else { ?>
                <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_DISTRIBUTE.DS.$output['setting']['distribute_logo'];?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_DISTRIBUTE.DS.$output['setting']['distribute_logo'];?>>')" onMouseOut="toolTip()"></i> </a>
            <?php } ?>
            </span> <span class="type-file-box">
            <input name="distribute_logo" type="file" class="type-file-file" id="distribute_logo" size="30" hidefocus="true" nc_type="distribute_image">
            </span></div>
                    <span class="err"></span>
                    <p class="notic">分销市场LOGO图片推荐尺寸380px*60px</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="distribute_check">分销认证审核开关</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="check_isuse_1" class="cb-enable <?php if($output['setting']['distribute_check'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><?php echo $lang['nc_open'];?></label>
                        <label for="check_isuse_0" class="cb-disable <?php if($output['setting']['distribute_check'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><?php echo $lang['nc_close'];?></label>
                        <input type="radio" id="check_isuse_1" name="distribute_check" value="1" <?php echo $output['setting']['distribute_check']==1?'checked=checked':''; ?>>
                        <input type="radio" id="check_isuse_0" name="distribute_check" value="0" <?php echo $output['setting']['distribute_check']==0?'checked=checked':''; ?>>
                    </div>
                    <p class="notic">开启和关闭分销员认证审核，关闭审核后通分销员申请后直接成为分销员，开启则需要平台审核通过才能成为分销员</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="distribute_bill_limit">冻结金额</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['distribute_bill_limit'];?>" name="distribute_bill_limit" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">设置后分销员账户必须大于此金额才能进行提现，冻结金额不可提现，分销员退出分销市场才可进行冻结金额提现操作</p>
                </dd>
            </dl>
            <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        //文件上传
        var textButton1="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />";
        $(textButton1).insertBefore("#distribute_logo");
        $("#distribute_logo").change(function(){
            $("#textfield1").val($("#distribute_logo").val());
        });
        $("input[nc_type='distribute_image']").live("change", function(){
            var src = getFullPath($(this)[0]);
            $(this).parent().prev().find('.low_source').attr('src',src);
            $(this).parent().find('input[class="type-file-text"]').val($(this).val());
        });

        $("#submit").click(function(){
            $("#add_form").submit();
        });

    });
</script> 
