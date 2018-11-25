<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>

    <?php
      $htm = '<a href="javascript:void(0)"  uri="index.php?con=member_trustname&fun=index"  error_msg="'.$lang['member_please_trustname'].'" class="ncbtn ncbtn-bittersweet no-trustname" >'.$lang['member_banks_new_card'].'</a>';
      if($output['member_info']['is_trust_name'] == 1 && !empty($output['member_info']['id_card']) && !empty($output['member_info']['member_truename'])){
          //已实名认证
        $htm = '<a href="javascript:void(0)" class="ncbtn ncbtn-bittersweet" nc_type="dialog" dialog_title="'.$lang['member_banks_new_card'].'" dialog_id="my_banks_edit"  uri="index.php?con=member_banks&fun=banks&type=add" dialog_width="550" title="'.$lang['member_banks_new_card'].'">'.$lang['member_banks_new_card'].'</a>';
      }
      echo $htm;
    ?>
  </div>
  <div class="alert alert-success">
    <h4>操作提示：</h4>
    <ul>
      <li>1、开户人为会员真实姓名。2、只能添加本人银行卡。3、每个会员最多添加10张银行卡。</li>
    </ul>
  </div>
  <table class="ncm-default-table" >
    <thead>
      <tr>
        <th class="w80"><?php echo $lang['member_banks_card'];?></th>
        <th class="w120"><?php echo $lang['member_banks_name'];?></th>
        <th class="w110"><?php echo $lang['member_banks_branch_name'];?></th>
        <th class="w150"><?php echo $lang['member_banks_disc'];?></th>
        <th class="w110"><?php echo $lang['member_banks_is_default'];?></th>
        <th class="w110"><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>
    <?php if(!empty($output['banks_list']) && is_array($output['banks_list'])){?>
    <tbody>
      <?php foreach($output['banks_list'] as $key=>$bank){?>
      <tr class="bd-line">
        <td><?php echo _decrypt($bank['BANK_CARD']);?></td>
        <td><?php echo $bank['BANK_NAME'];?></td>
        <td><?php echo $bank['BRANCH_NAME'];?></td>
        <td><?php echo $bank['PROVINCE']." ".$bank['CITY'];?></td>
        <td><?php if ($bank['USED'] == '1') {?>
          <i class="icon-ok-sign green" style="font-size: 18px;"></i>默认使用
          <?php } ?></td>
        <td class="ncm-table-handle"><span>
          <a href="javascript:void(0);" class="btn-bluejeans" dialog_id="my_banks_edit" dialog_width="550" dialog_title="<?php echo $lang['member_banks_edit_card'];?>" nc_type="dialog" uri="<?php echo MEMBER_SITE_URL;?>/index.php?con=member_banks&fun=banks&type=edit&id=<?php echo $bank['ID'];?>"><i class="icon-edit"></i>
          <p><?php echo $lang['nc_edit'];?></p>
          </a>

          </span> <span><a href="javascript:void(0)" class="btn-grapefruit" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', '<?php echo MEMBER_SITE_URL;?>/index.php?con=member_banks&fun=banks&id=<?php echo $bank['ID'];?>');"><i class="icon-trash"></i>
          <p><?php echo $lang['nc_del'];?></p>
          </a></span></td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
</div>

<script>
  $(function(){
    $(".no-trustname").click(function(){
      url = $(this).attr('uri');
      showDialog($(this).attr('error_msg'), 'error','',go_url(url),'','','','','',2);
    });
  });

  function go_url(url){
    setTimeout("window.location = url",2000);
  }

</script>

