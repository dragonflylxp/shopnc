<?php defined('Inshopec') or exit('Access Invalid!');?>


<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu'); ?>
    </div>
    <!--<div class="alert"><span class="mr30"></span></div>-->
    <table class="ncm-default-table">
        <thead>
            <tr>
                <th class="w10"></th>
                <th class="tl"><?php echo $lang['member_name']; ?></th>
                <th class="tl"><?php echo $lang['buy_count']; ?></th>
                <th class="tl"><?php echo $lang['refund_amount']; ?></th>
                <th class="tl"><?php echo $lang['signup_time']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($output['invite_list'])>0) { ?>
                <?php foreach($output['invite_list'] as $v) { ?>
                    <tr class="bd-line">
                        <td></td>
                        <td class="tl"><?php echo $v['member_name'] ;?></td>
                        <td class="tl"><?php echo $v['buy_count'] ;?></td>
                        <td class="tl"><?php echo $v['refund_amount'] ;?></td>
                        <td class="tl"><?php echo date('Y-m-d H:i:s', $v['member_time']) ;?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <?php  if (count($output['invite_list'])>0) { ?>
                <tr>
                    <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
                </tr>
            <?php } ?>
        </tfoot>
    </table>
</div>