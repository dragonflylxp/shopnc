<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <a class="back" href="index.php?con=manager_vr_bill&fun=index" title="返回地区管理人列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>管理人结算详细信息</h3>
                <h5>管理人个人信息以及实物结算详情</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current" href="JavaScript:void(0);">管理人个人信息</a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="ncap-form-default">
        <h2>管理人个人信息</h2>
        <dl class="row">
            <dt class="tit">公司名称</dt>
            <dd class="opt"><?php echo $output['bill_info']['complete_company_name'];?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">公司电话</dt>
            <dd class="opt"><?php echo $output['bill_info']['company_phone'];?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">营业执照号</dt>
            <dd class="opt"><?php echo _decrypt($output['bill_info']['business_licence_number']);?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">公司法人姓名</dt>
            <dd class="opt"><?php echo $output['bill_info']['legal_person_name'];?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">公司法人身份证号</dt>
            <dd class="opt"><?php echo _decrypt($output['bill_info']['id_number']);?></dd>
        </dl>

        <dl class="row">
            <dt class="tit">邮箱</dt>
            <dd class="opt"><?php echo $output['bill_info']['contacts_email'];?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">管理区域</dt>
            <dd class="opt"><?php echo $output['bill_info']['area'];?></dd>
        </dl>
    </div>

    <div id="flexigrid"></div>

</div>

<script>

    $(function() {
        var flexUrl = 'index.php?con=manager_vr_bill&fun=index_show_xml&uid=<?php echo $output['bill_info']['uid'];?> ';

        $("#flexigrid").flexigrid({
            url: flexUrl,
            colModel: [
                {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
                {display: '管理人ID', name : 'uid', width : 100, sortable : true, align: 'center'},
                {display: '管理人名称', name : 'manager_name', width: 100, sortable : true, align : 'center'},
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
                        location.href = qs+'&con=manager_vr_bill&fun=export_step2&ids=' + ids.join(',');
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

</script>

