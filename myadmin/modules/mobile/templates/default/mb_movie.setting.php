<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>直播管理</h3>
                <h5>手机端所有直播及管理</h5>
            </div>
            <?php echo $output['top_link'];?>
        </div>
    </div>
    <form method="post" name="form_movieverify">
        <input type="hidden" name="form_submit" value="ok" />
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label>直播是否需要审核</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="rewrite_enabled"  class="cb-enable <?php if($output['list_setting']['movie_verify'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
                        <label for="rewrite_disabled" class="cb-disable <?php if($output['list_setting']['movie_verify'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
                        <input id="rewrite_enabled" name="movie_verify" <?php if($output['list_setting']['movie_verify'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
                        <input id="rewrite_disabled" name="movie_verify" <?php if($output['list_setting']['movie_verify'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
                    </div>
                    <p class="notic">直播审核开启,只有通过审核才可通过APP上直播。</p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form_movieverify.submit()"><?php echo $lang['nc_submit'];?></a></div>
        </div>
    </form>
</div>
