<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <a class="back" href="index.php?con=manager_rebill_check&fun=get_money_state" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>管理人提现申请详细信息</h3>
                <h5>管理人详细信息以及实物结算详情</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current" href="JavaScript:void(0);">管理人提现申请信息</a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="ncap-form-default">
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">管理人提现信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">管理人账号：</th>
                <td><?php echo $output['bill_info']['manager_account'];?></td>
            </tr>
            <tr>
                <th>公司名称：</th>
                <td><?php echo $output['bill_info']['complete_company_name'];?></td>
            </tr>
            <tr>
                <th>公司电话：</th>
                <td><?php echo $output['bill_info']['company_phone'];?></td>
            </tr>
            <tr>
                <th>营业执照号：</th>
                <td><?php echo _decrypt($output['bill_info']['business_licence_number']);?></td>
            </tr>
            <tr>
                <th>公司法人姓名：</th>
                <td><?php echo $output['bill_info']['legal_person_name'];?></td>
            </tr>
            <tr>
                <th>公司法人身份证号：</th>
                <td><?php echo _decrypt($output['bill_info']['id_number']);?></td>
            </tr>
            <tr>
                <th>邮箱：</th>
                <td><?php echo $output['bill_info']['contacts_email'];?></td>
            </tr>
            <tr>
                <th>管理人等级：</th>
                <td><?php echo $output['bill_info']['grade'];?></td>
            </tr>
            <tr>
                <th>管理区域：</th>
                <td><?php echo $output['bill_info']['area'];?></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">结算账号信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名：</th>
                <td><?php echo $output['bill_info']['settlement_bank_account_name'];?></td>
            </tr>
            <tr>
                <th>公司银行账号：</th>
                <td><?php echo _decrypt($output['bill_info']['settlement_bank_account_number']);?></td>
            </tr>
            <tr>
                <th>开户银行支行名称：</th>
                <td><?php echo $output['bill_info']['settlement_bank_name'];?></td>
            </tr>
            <tr>
                <th>支行联行号：</th>
                <td><?php echo _decrypt($output['bill_info']['settlement_bank_code']);?></td>
            </tr>
            <tr>
                <th>提现金额：</th>
                <td><?php echo $output['bill_info']['total'];?></td>
            </tr>
            <tr>
                <th>开户银行所在地：</th>
                <td><?php echo $output['bill_info']['settlement_bank_address'];?></td>
            </tr>
            </tbody>
        </table>
        <form id="form_store_verify" action="index.php?con=manager_vrbill_check&fun=bill_state_verify" method="post">
            <?php if(in_array(intval($output['bill_info']['state']), array(3,4))) { ?>
                <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
                    <tr>
                        <th>申请时间：</th>
                        <td> <input disabled type="text" id="apply_date" name="apply_date" value="<?php
                            if(!empty($output['bill_info']['apply_date'])) {
                                echo date('Y-m-d', $output['bill_info']['apply_date']);
                            }
                            ;?>"></td>
                    </tr>
                    <tr>
                        <th>付款时间：</th>
                        <td> <input type="text" id="pay_date" <?php
                            if(in_array(intval($output['bill_info']['state']), array(4))) {
                                echo "disabled";
                            } ?> name="pay_date" value="<?php
                            if(!empty($output['bill_info']['pay_date'])){
                                echo date('Y-m-d', $output['bill_info']['pay_date']);
                            }
                            ;?>"></td>
                    </tr>
                    <tr>
                        <th>备注：</th>
                        <td colspan="2"><textarea id="pay_comment" <?php
                            if(in_array(intval($output['bill_info']['state']), array(4))) {
                                echo "disabled";
                            } ?> name="pay_comment"><?php echo $output['bill_info']['pay_comment'];?></textarea></td>
                    </tr>
                </table>
            <?php } ?>
            <input id="verify_type" name="verify_type" type="hidden" />
            <input name="member_id" type="hidden" value="<?php echo $output['bill_info']['member_id'];?>" />
            <?php if(in_array(intval($output['bill_info']['state']), array(3))) { ?>
                <div id="validation_message" style="color:red;display:none;"></div>
                <div class="bottom"><a id="btn_pass" class="ncap-btn-big ncap-btn-green mr10" href="JavaScript:void(0);">已打款</a><a id="btn_fail" class="ncap-btn-big ncap-btn-red" href="index.php?con=manager_vrbill_check&fun=get_money_state">取消</a> </div>
            <?php } ?>
        </form>
    </div>


</div>

<script>


    $('a[data-href]').live('click', function() {
        if ($(this).hasClass('confirm-del-on-click') && !confirm('确定删除?')) {
            return false;
        }

        $.getJSON($(this).attr('data-href'), function(d) {
            if (d && d.result) {
                $("#flexigrid").flexReload();
            } else {
                alert(d && d.message || '操作失败！');
            }
        });
    });
    $(document).ready(function(){
        //付款时间
        $('#pay_date').datepicker({dateFormat:'yy-mm-dd',Date: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
//        $('a[nctype="nyroModal"]').nyroModal();
        //已提现
        $('#btn_pass').on('click', function() {
            if($('#pay_date').val() == '') {
                $('#validation_message').text('请输入付款日期');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认已打款？')) {
                $('#verify_type').val(4);
                $('#form_store_verify').submit();
            }
        });
        //取消提现
        $('#btn_fail').on('click', function() {
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
                if(confirm('确认取消？')) {
                    $('#verify_type').val(3);
                    $('#form_store_verify').submit();
                }
            } else {
                $('#validation_message').show();
            }
        });
    });

</script>

