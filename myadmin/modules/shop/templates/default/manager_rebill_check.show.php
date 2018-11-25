<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <a class="back" href="index.php?con=manager_rebill_check&fun=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>管理人提现申请详细信息</h3>
                <h5>管理人详细信息以及实物结算详情</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current" href="JavaScript:void(0);">管理人审核信息</a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="ncap-form-default">
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">管理人个人信息：</th>
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
        <form id="form_store_verify" action="index.php?con=manager_rebill_check&fun=bill_check_verify" method="post">
            <?php if(in_array(intval($output['bill_info']['state']), array(2,5))) { ?>
                <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
                    <tr>
                        <th>申请时间：</th>
                        <td> <input disabled type="text" id="pay_date" name="pay_date" value="<?php
                            if(!empty($output['bill_info']['apply_date'])) {
                                echo date('Y-m-d', $output['bill_info']['apply_date']);
                            }
                            ;?>"></td>
                    </tr>
                    <tr>
                        <th>审核意见：</th>
                        <td colspan="2"><textarea id="pay_content" <?php
                            if(in_array(intval($output['bill_info']['state']), array(5))) {
                                echo "disabled";
                            } ?> name="pay_content"><?php echo $output['bill_info']['pay_content'];?></textarea></td>
                    </tr>
                </table>
            <?php } ?>
            <input id="verify_type" name="verify_type" type="hidden" />
            <input name="member_id" type="hidden" value="<?php echo $output['bill_info']['member_id'];?>" />
            <?php if(in_array(intval($output['bill_info']['state']), array(2))) { ?>
                <div id="validation_message" style="color:red;display:none;"></div>
                <div class="bottom"><a id="btn_pass" class="ncap-btn-big ncap-btn-green mr10" href="JavaScript:void(0);">通过</a><a id="btn_fail" class="ncap-btn-big ncap-btn-red" href="JavaScript:void(0);">拒绝</a> </div>
            <?php } ?>
        </form>
    </div>


<!--    <div id="flexigrid"></div>-->

</div>

<script>

    $(function() {
        var flexUrl = 'index.php?con=manager_real_bill&fun=index_show_xml&uid=<?php echo $output['bill_info']['uid'];?> ';

        $("#flexigrid").flexigrid({
            url: flexUrl,
            colModel: [
                {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
                {display: '管理人ID', name : 'uid', width : 100, sortable : true, align: 'center'},
                {display: '公司名称', name : 'manager_name', width: 100, sortable : true, align : 'center'},
                {display: '管理人等级', name : 'grade', width: 100, sortable : true, align : 'center'},
                {display: '管理区域', name : 'area', width : 250, sortable : true, align: 'center'},
                {display: '本期应结金额', name : 'total', width : 120, sortable : true, align: 'center'},
                {display: '开始结算时间', name : 'start_time', width : 120, sortable : true, align: 'center'},
                {display: '结束结算时间', name : 'end_time', width : 120, sortable : true, align: 'center'}
            ],
            buttons: [
                {
                    display: '<i class="fa fa-file-excel-o"></i>导出数据',
                    name: 'csv',
                    bclass: 'csv',
                    title: '将选定行数据导出Excel文件',
                    onpress: function() {
                        var ids = [];
                        $('.trSelected[data-id]').each(function() {
                            ids.push($(this).attr('data-id'));
                        });
                        if (ids.length == 0 && !confirm('您确定要下载本次搜索的全部数据吗？')) {
                            return false;
                        }
                        var qs = $("#flexigrid").flexSimpleSearchQueryString();
                        location.href = qs+'&con=manager_real_bill&fun=export_step2&ids=' + ids.join(',');
                    }
                }
            ],
            searchitems: [
                {display: '管理人ID', name: 'uid', isdefault: true},
                {display: '公司名称', name: 'manager_name'}
            ],
            sortname: "start_time",
            sortorder: "desc",
            title: '每期结算金额列表'
        });

        // 高级搜索提交
        $('#ncsubmit').click(function(){
            $("#flexigrid").flexOptions({url: flexUrl + '&' + $("#formSearch").serialize(),query:'',qtype:''}).flexReload();
        });

        // 高级搜索重置
        $('#ncreset').click(function(){
            $("#flexigrid").flexOptions({url: flexUrl}).flexReload();
            $("#formSearch")[0].reset();
        });

        $("input[data-dp='1']").datepicker({dateFormat: 'yy-mm-dd'});

    });

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
//        $('a[nctype="nyroModal"]').nyroModal();
        //审核失败
        $('#btn_fail').on('click', function() {
            if($('#pay_content').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认拒绝提现申请？')) {
                $('#verify_type').val(5);
                $('#form_store_verify').submit();
            }
        });
        //审核通过
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
                if(confirm('确认通过提现申请？')) {
                    $('#verify_type').val(3);
                    $('#form_store_verify').submit();
                }
            } else {
                $('#validation_message').show();
            }
        });
    });

</script>

