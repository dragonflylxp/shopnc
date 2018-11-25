<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>
    <a class="ncbtn ncbtn-mint" href="index.php?con=cash&fun=apply_cash"><i class="icon-money"></i>申请提现</a>
  </div>
  <div class="alert">
    <span class="mr30">
      <?php echo '可提现佣金金额'.$lang['nc_colon']; ?>
      <strong class="mr5 red" style="font-size: 18px;">
        <?php 
          echo ncPriceFormatForList($output['member_info']['available_distri_trad']);
        ?>
      </strong><?php echo $lang['currency_zh'];?>
    </span>
    <span>
      <?php echo '冻结佣金金额'.$lang['nc_colon']; ?>
      <strong class="mr5 blue" style="font-size: 18px;">
        <?php 
          echo ncPriceFormatForList($output['member_info']['freeze_distri_trad']);
        ?>
      </strong><?php echo $lang['currency_zh'];?>
    </span>
  </div>
  <form method="get" action="index.php">
    <table class="ncm-search-table">
      <input type="hidden" name="con" value="cash" />
      <input type="hidden" name="fun" value="cash_list" />
      <tr>
      <th></th><td></td>
        <th><?php echo '状态'.$lang['nc_colon']; ?></th>
        <td class="w90"><select id="paystate_search" name="paystate_search">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <option <?php if ($_GET['paystate_search'] == '0') echo 'selected';?> value="0">未支付</option>
            <option <?php if ($_GET['paystate_search'] == '1') echo 'selected';?> value="1">已支付</option>
          </select>
       </td>
        <th><?php echo '申请单号';?></th>
        <td class="w160 tc"><input type="text" class="text w150" name="sn_search" value="<?php echo $_GET['sn_search'];?>"/></td>
        <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th style="text-align:left; text-indent:16px"><?php echo '申请单号'; ?></th>
        <th style="text-align:left"><?php echo '申请时间'; ?></th>
        <th><?php echo '提现金额'; ?>(<?php echo $lang['currency_zh']; ?>)</th>
        <th class="w150"><?php echo '状态'; ?></th>
        <th class="w100"><?php echo '操作';?></th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list'])>0) { ?>
      <?php foreach($output['list'] as $val) { ?>
      <tr class="bd-line">
        <td style="text-align:left;text-indent:16px"><?php echo $val['tradc_sn'];?></td>
        <td style="text-align:left"><?php echo @date('Y-m-d H:i:s',$val['tradc_add_time']);?></td>
        <td><?php echo $val['tradc_amount'];?></td>
        <td><?php echo str_replace(array('0','1'),array('未支付','已支付'),$val['tradc_payment_state']);?></td>
        <td><p><a href="index.php?con=cash&fun=cash_info&id=<?php echo $val['tradc_id']; ?>">查看</a></p></td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
