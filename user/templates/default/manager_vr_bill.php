<?php defined('Inshopec') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/btn.ui.css"  />
<div class="wrap">
    <!--中心列表小导航-->
    <div class="tabmenu">
        <?php include template('layout/submenu');?>
    </div>
    <!--提示信息-->
    <div class="alert alert-block">
        <h4>结算规则</h4>
        <ul><li>结算时间:每月一号,结算上月账单</li>
    </div>
    <!-- 搜素栏-->
    <form method="get" action="index.php">
        <table class="ncm-search-table">
            <input type="hidden" name="con" value="manager_index" />
            <input type="hidden" name="fun" value="vr_bill" />
            <tr><td class="w10">&nbsp;</td>
                <td class="w300">
                    <input type="button" value="全选" id="selectAll" class="btn"/>&nbsp;&nbsp;
                    <input type="button" value="全不选" id="unSelect" class="btn btn-2"/>&nbsp;&nbsp;
                    <input type="button" value="反选" id="reverse" class="btn"/>&nbsp;&nbsp;
                    <input type="button" value="批量申请" id="allTobalance" />
                </td>
<!--                按时间搜索-->
                <th>起止时间</th>
                <td class="w240"><input type="text" id="stime" name="stime" class="text w70" value="<?php echo $_GET['stime'];?>"><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input type="text" id="etime" name="etime" class="text w70" value="<?php echo $_GET['etime'];?>"><label class="add-on"><i class="icon-calendar"></i></label></td>
<!--                按类别搜索-->
                <th>结算状态</th>
                <td class="w100"><select name="stage">
                        <option value=""  <?php if (!$_GET['stage']){echo 'selected=selected';}?>><?php echo $lang['nc_please_choose'];?></option>
                        <option value="1" <?php if ($_GET['stage'] == 1){echo 'selected=selected';}?>>未申请</option>
                        <option value="2" <?php if ($_GET['stage'] == 2){echo 'selected=selected';}?>>审核中</option>
                        <option value="3" <?php if ($_GET['stage'] == 3){echo 'selected=selected';}?>>打款中</option>
                        <option value="4" <?php if ($_GET['stage'] == 4){echo 'selected=selected';}?>>已打款</option>
                        <option value="5" <?php if ($_GET['stage'] == 5){echo 'selected=selected';}?>>未通过</option>
                    </select></td>
<!--                按照关键字搜索-->
                <th>收益单号</th>
                <td class="w70"><input type="text" class="text w150" id="mb_id" name="mb_id" value="<?php echo $_GET['mb_id'];?>"></td>
                <td class="w60 tc"><label class="submit-border">
                        <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" />
                    </label></td>
            </tr>
        </table>
    </form>
    <!--列表栏-->
    <table class="ncm-default-table">
        <thead>
        <tr>
            <th class="w50"></th>
            <th class="w50">收益单号</th>
            <th class="w100">开始时间</th>
            <th class="w100">结束时间</th>
            <th class="w150">管理区域</th>
            <th class="w100">金额</th>
            <th class="w150">审核备注</th>
            <th class="w100">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if (count($output['list_manager_bill'])>0) { ?>
            <?php foreach($output['list_manager_bill'] as $val) { ?>
                <tr class="bd-line">

                    <?php if ($val['state'] == 1) { ?>
                        <td><input type="checkbox" class="check"  data-value="<?php echo $val['mb_id'];?>"/></td>
                    <?php }else{ ?>
                        <td><input type="checkbox" disabled/></td>
                    <?php } ?>

                    <td class=""><?php echo $val['mb_id'];?></td>
                    <td class="goods-time"><?php echo @date('Y-m-d',$val['start_time']);?></td>
                    <td class="goods-time"><?php echo @date('Y-m-d',$val['end_time']);?></td>
                    <td class="goods-price"><?php echo $output['area']; ?></td>
                    <td class=""><?php echo $val['total'];?></td>
                    <td class=""><?php echo $val['pay_content'];?></td>
                    <?php if ($val['state'] == 1) { ?>
                        <td><span><div class="btn3 apply_change"  data-value="<?php echo $val['mb_id'];?>">申请</div></span></td>
                    <?php }elseif($val['state'] == 2){ ?>
                        <td><span><div class="btn-4" >审核中</div></span></td>
                    <?php }elseif($val['state'] == 3){ ?>
                        <td><span><div class="btn-4" >打款中</div></span></td>
                    <?php }elseif($val['state'] == 4){ ?>
                        <td><span><div class="btn-4">已打款</div></span></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record']; ?></span></div></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php  if (count($output['list_manager_bill'])>0) { ?>
            <tr>
                <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script language="javascript">
    $(function(){
        $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
        $('#etime').datepicker({dateFormat: 'yy-mm-dd'});
        //点击申请提现
        $('.apply_change').on('click',function(){
            var _ob_id = $(this).attr('data-value');
            var _this = $(this);
            $.ajax({
                type:"POST",
                url:'<?php echo urlMember('manager_index','apply_vr');?>',
                async:true,
                dataType:"json",
                data:{id: _ob_id},
                success: function(data){
                    if(data.state == 'true'){
                        _this.html('审核中');
                        _this.css('cursor','');
                        /*取消点击事件的绑定*/
                        _this.off("click");
                        _this.removeClass('btn3');
                        _this.addClass('btn-4');
                        _this.closest('tr').find('.check').attr('disabled','disabled');
                        _this.closest('tr').find('.check').attr('checked',false);
                        _this.closest('tr').find('.check').removeClass('check');
                    }else {
                        layer.alert(data.msg);
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
        //批量转入余额
        $("#allTobalance").click(function () {
            var _a = $(".check:checked").length;
            var arr =[];
            var obj_arr = [];
            for(var i=0;i<_a;i++){
                var val = $($(".check:checked")[i]).attr('data-value');
                /* console.debug($($(".check:checked")[i]).closest('tr').find('.apply_change').text());*/
                arr[i] =val;
                obj_arr[i] = $($(".check:checked")[i]).closest('tr');
            }
            $.ajax({
                type:"POST",
                url:'<?php echo urlMember('manager_index','apply_vr');?>',
                async:true,
                dataType:"json",
                data:{id: arr},
                success: function(data){
                    console.log(data);
                    if(data.state == 'true'){
                        for(var i=0;i<_a;i++){
                            obj_arr[i].find('.apply_change').text('审核中').css('cursor', '').off("click").removeClass('btn3').addClass('btn-4');
                            obj_arr[i].find('.check').prop('checked',false).prop('disabled','disabled').removeClass('check');
                        }
                    }else{
                        layer.alert(data.msg);
                    }
                }
            });
            return false;
        });

    });
</script>