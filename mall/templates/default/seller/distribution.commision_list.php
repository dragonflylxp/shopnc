<?php defined('Inshopec') or exit('Access Invalid!');?>

<table class="search-form">
    <form method="post">
        <input type="hidden" name="act" value="distribution" />
        <input type="hidden" name="op" value="commision_list" />
        <tr>
            <td>&nbsp;</td>
            <th>返佣类型</th>
            <td class="w100"> 
                <select name="commision_level" class="w90">
                    <option value="">全部</option>
                    <option value="1" <?php echo ($output['commision_level'] == 1) ? 'selected' : ''; ?>>1级返佣</option>
                    <option value="2" <?php echo ($output['commision_level'] == 2) ? 'selected' : ''; ?>>2级返佣</option>
                    <option value="3" <?php echo ($output['commision_level'] == 3) ? 'selected' : ''; ?>>3级返佣</option>
                </select>
            </td> 
            <th>订单号</th>
            <td class="w160"><input class="text" type="text" name="order_sn" value="<?php echo $output['order_sn'];?>"/></td>
            <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
        </tr>
    </form>
</table>
<table class="ncsc-default-table">
    <thead>
        <tr> 
            <th class="w130">订单号</th>
            <th class="w130">物品名称</th>
            <th class="w130">物品数量</th>
            <th class="w130">返佣金额</th>
            <th class="w130">返佣等级</th>
            <th class="w90">返佣时间</th>
            <th class="w110">获得者</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($output['commision_list']) && is_array($output['commision_list'])){?>
        <?php foreach($output['commision_list'] as $key=>$commision){?>
        <tr class="bd-line">
            <td><?php echo $commision['order_sn'];?></td>
            <td><?php echo $commision['goods_name'];?></td>
            <td><?php echo $commision['goods_num'];?></td>
            <td>￥<?php echo $commision['je'];?>元</td>
            <td><?php echo $commision['commision_level'];?>级返佣</td>
            <td><?php echo date('Y-m-d H:m:s',$commision['addtime']);?></td>
            <td><?php echo $commision['username'];?></td>
        </tr>
        <?php }?>
        <?php }else{?>
        <tr>
            <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
        </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
        </tr>
    </tfoot>
</table>