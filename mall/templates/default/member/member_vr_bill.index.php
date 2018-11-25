<?php defined('Inshopec') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/btn.ui.css"  />
<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu');?>
    </div>
    <form method="get" action="index.php" target="_self">
        <table class="ncm-search-table">
            <input type="hidden" name="con" value="member_vr_bill" />
            <input type="hidden" name= "recycle" value="<?php echo $_GET['recycle'];?>" />
            <tr>
                <td>
                    <input type="button" value="全选" id="selectAll"  class="btn"/>&nbsp;&nbsp;
                    <input type="button" value="全不选" id="unSelect" class="btn btn-2"/>&nbsp;&nbsp;
                    <input type="button" value="反选" id="reverse" class="btn"/>&nbsp;&nbsp;
                    <input type="button" value="批量转入余额" id="allTobalance"/>
                </td>
                <th><?php echo $lang['member_apply_state'];?></th>
                <td class="w100"><select name="state_type">
                        <option value="" <?php echo $_GET['state_type']==''?'selected':''; ?>><?php echo $lang['member_apply_all'];?></option>
                        <option value="state_new" <?php echo $_GET['state_type']=='state_new'?'selected':''; ?>>未转入</option>
                        <option value="state_payout" <?php echo $_GET['state_type']=='state_payout'?'selected':''; ?>>已转入</option>
                    </select></td>
                <th><?php echo $lang['member_apply_time'];?></th>
                <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input type="text" class="text w70" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/><label class="add-on"><i class="icon-calendar"></i></label></td>
                <td class="w240 tr"><input type="text" class="text w200" placeholder="请输入店铺名称" name="keyword" value="<?php echo $_GET['keyword']; ?>"></td>
                <td class="w70 tc"><label class="submit-border">
                        <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>"/>
                    </label></td>
            </tr>
        </table>
    </form>
    <table class="ncm-default-table order">
        <thead>
        <tr>
            <th class="w10"></th>
            <th class="w30">单号</th>
            <th class="w120">收益店铺名称</th>
            <th class="w60">开始日期</th>
            <th class="w60">结束日期</th>
            <th class="w100">收益金额</th>
            <th class="w50">转入余额</th>
        </tr>
        </thead>
        <?php if ($output['member_vr_bill']) { ?>
            <tbody>
            <?php foreach ($output['member_vr_bill'] as  $bill_info) { ?>
                <tr>
                    <?php if ($bill_info['member_ob_state'] == 1) { ?>
                        <td><input type="checkbox" class="check"  data-value="<?php echo $bill_info['member_ob_id'];?>"/></td>
                    <?php }else{ ?>
                        <td><input type="checkbox" disabled/></td>
                    <?php } ?>
                    <td><?php echo $bill_info['member_ob_id'];?></td>
                    <td><?php echo $bill_info['ob_store_name'];?></td>
                    <td><?php echo date('Y-m-d',$bill_info['member_ob_start_date']);?></td>
                    <td><?php echo date('Y-m-d',$bill_info['member_ob_end_date']);?></td>
                    <td>￥<?php echo $bill_info['member_ob_result_totals'];?></td>
                    <!--店铺申请状态-->
                    <?php if ($bill_info['member_ob_state'] == 1) { ?>
                        <td>
                            <span>
                                <div class="btn3 apply_change" data-value="<?php echo $bill_info['member_ob_id'];?>">转入余额</div>
                            </span>
                        </td>
                    <?php }elseif($bill_info['member_ob_state'] != 1){ ?>
                        <td>
                            <span>
                                <div class="btn-4">已转入</div>
                            </span>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        <?php } ?>
        <tfoot>
        <tr>
            <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
        </tr>
        </tfoot>
    </table>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script>
<script type="text/javascript">
    $(function(){
        $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
        //点击转入余额
        $('.apply_change').on('click',function(){
            var _ob_id = $(this).attr('data-value');
            var _this = $(this);
            $.ajax({
                type:"POST",
                url:'<?php echo urlShop('member_vr_bill','chageState');?>',
                async:true,
                dataType:"json",
                data:{id: _ob_id},
                success: function(data){
                    if(data){
                        _this.html('已转入');
                        _this.css('cursor','');
                        /*取消点击事件的绑定*/
                        _this.off("click");
                        _this.removeClass('btn3');
                        _this.addClass('btn-4');
                        _this.closest('tr').find('.check').attr('disabled','disabled');
                        _this.closest('tr').find('.check').attr('checked',false);
                        _this.closest('tr').find('.check').removeClass('check');
                    }else {
                    }
                }
            });
            return false;
        });
        //全选
        $("#selectAll").click(function () {
            $(".check").attr("checked", true);
        });
        //全不选
        $("#unSelect").click(function () {
            $(".check").attr("checked", false);
        });
        //反选
        $("#reverse").click(function () {
            $(".check").each(function () {
                $(this).attr("checked", !$(this).attr("checked"));
            });
        });

        $("#allTobalance").click(function () {

            var _a = $(".check:checked").length;
            var arr =[];
            var obj_arr = [];
            for(var i=0;i<_a;i++){
                var val = $($(".check:checked")[i]).attr('data-value');
                arr[i] =val;
                obj_arr[i] = $($(".check:checked")[i]).closest('tr');
            }
            $.ajax({
                type:"POST",
                url:'<?php echo urlShop('member_vr_bill','chageState');?>',
                async:true,
                dataType:"json",
                data:{id: arr},
                success: function(data){
                    if(data){
                        for(var i=0;i<_a;i++){
                            obj_arr[i].find('.apply_change').text('已转入').css('cursor', '').off("click").removeClass('btn3').addClass('btn-4');
                            obj_arr[i].find('.check').prop('checked',false).prop('disabled','disabled').removeClass('check');
                        }
                    }else {

                    }
                }
            });
            return false;
        });
    });
</script>
