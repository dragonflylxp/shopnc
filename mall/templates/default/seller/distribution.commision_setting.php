<?php
/**
 * 佣金设置模板文件 20160906
 *
 * @User      noikiy
 * @File      distribution.commision_setting.php
 * @Link      
 * @Copyright 2015 
 */

defined('Inshopec') or exit('Access Invalid!');

//加载分销语言文件
Language::read ('distribution');

?>

<div class="eject_con">
    <div id="warning" class="alert alert-error"></div>
    <form method="post" action="<?php echo urlShop('distribution', 'commision_setting');?>" id="commision_form">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="goods_commonid" value="<?php echo $output['goods_commonid']; ?>" />
        <dl>
            <dt><?php echo $lang['commision_grade_one_money'];?>：</dt>
            <dd>
                <input type="text" class="text w300" name="fencheng1" id="fencheng1" value="<?php echo !empty($output['goods_common_info']['fencheng1']) ? $output['goods_common_info']['fencheng1'] : ncPriceFormat(0); ?>" />
                <p class="hint"><?php echo $lang['commision_grade_money_tip'];?></p>
            </dd>
        </dl>
        <dl>
            <dt><?php echo $lang['commision_grade_two_money'];?>：</dt>
            <dd>
                <input type="text" class="text w300" name="fencheng2" id="fencheng2" value="<?php echo !empty($output['goods_common_info']['fencheng2']) ? $output['goods_common_info']['fencheng2'] : ncPriceFormat(0); ?>" />
                <p class="hint"><?php echo $lang['commision_grade_money_tip'];?></p>
            </dd>
        </dl>
        <dl>
            <dt><?php echo $lang['commision_grade_three_money'];?>：</dt>
            <dd>
                <input type="text" class="text w300" name="fencheng3" id="fencheng3" value="<?php echo !empty($output['goods_common_info']['fencheng3']) ? $output['goods_common_info']['fencheng3'] : ncPriceFormat(0); ?>" />
                <p class="hint"><?php echo $lang['commision_grade_money_tip'];?></p>
            </dd>
        </dl>
        <div class="bottom">
            <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>"/></label>
        </div>
    </form>
</div>
<script>
$(function(){
    $('#commision_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
            $('#warning').show();
        },
        submitHandler:function(form){
            ajaxpost('commision_form', '', '', 'onerror');
        },
        rules : {
            fencheng1 : {
                require: true
            },
            fencheng2 : {
                require: true
            },
            fencheng3 : {
                require: true
            }
        },
        messages : {
            fencheng1 : {
                require: '<i class="icon-exclamation-sign"></i><?php echo $lang['commision_grade_money_tip'];?>'
            },
            fencheng2 : {
                require: '<i class="icon-exclamation-sign"></i><?php echo $lang['commision_grade_money_tip'];?>'
            },
            fencheng3 : {
                require: '<i class="icon-exclamation-sign"></i><?php echo $lang['commision_grade_money_tip'];?>'
            }
        }
    });
});
</script>