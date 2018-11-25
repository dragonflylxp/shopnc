<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=store&fun=store_bind_class_applay_list" title="返回店铺列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>店铺经营类目申请</h3>
        <h5>店铺经营类目申请审核信息</h5>
      </div>
    </div>
  </div>

  <form id="form_store_verify" action="index.php?con=store&fun=store_bind_class_applay_check" method="post">
    <input id="verify" name="verify" type="hidden" />
    <input name="store_id" type="hidden" value="<?php echo $output['storeInfo'][0]['store_id'];?>" />
    <input name="bid" type="hidden" value="<?php echo $output['storeInfo'][0]['bid'];?>" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="20">经营类目申请信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">店主账号：</th>
          <td><?php echo $output['storeInfo'][0]['seller_name'];?></td>
        </tr>
        <tr>
          <th class="w150">店铺名称：</th>
          <td><?php echo $output['storeInfo'][0]['store_name'];?></td>
        </tr>

        <tr>
          <th>经营类目：</th>
          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
              <tr>
                    <?php echo $output['storeInfo'][0]['class'];?>
                  </tr>
            </table></td>
        </tr>
        <tr>
          <th class="w150">佣金比例：</th>
          <td><?php echo $output['storeInfo'][0]['commis_rate'].'%';?></td>
        </tr>


        <tr>
          <th>审核意见：</th>
          <td colspan="2"><textarea id="checkinfo" name="checkinfo"></textarea></td>
        </tr>

      </tbody>
    </table>
    <div id="validation_message" style="color:red;display:none;"></div>
    <div class="bottom"><a id="btn_pass" class="ncap-btn-big ncap-btn-green mr10" href="JavaScript:void(0);">通过</a><a id="btn_fail" class="ncap-btn-big ncap-btn-red" href="JavaScript:void(0);">拒绝</a> </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();

        $('#btn_fail').on('click', function() {
            if($('#checkinfo').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认审核失败？')) {
                $('#verify').val(3);
                $('#form_store_verify').submit();
            }
        });
        $('#btn_pass').on('click', function() {
            var valid = true;
            $('[nctype="commis_rate"]').each(function(commis_rate) {
                rate = $(this).val();
                if(rate == '') {
                    valid = false;
                    return false;
                }

                var rate = Number($(this).val());
                if(isNaN(rate) || rate < 0 || rate >= 100) {
                    valid = false;
                    return false;
                }
            });
            if(valid) {
                $('#validation_message').hide();
                if(confirm('确认通过审核？')) {
                    $('#verify').val(1);
                    $('#form_store_verify').submit();
                }
            }
        });
    });
</script>