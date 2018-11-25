<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>管理人实物结算提现状态</h3>
                <h5>管理人实物提现状态显示列表以及实物结算详细信息</h5>
            </div>

            <?php echo $output['top_link'];?>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
        <ul>
            <li>大区管理人佣金计算公式：(佣金金额 - 退还佣金) * 0.5%</li>
            <li>省份管理人佣金计算公式：(佣金金额 - 退还佣金) * 1%</li>
            <li>县、区级管理人佣金计算公式：(佣金金额 - 退还佣金) * 2%</li>
            <li>点击确认提现按钮可以对审核完成并且已打款的管理人进行确认</li>
            <li>点击查看按钮可以查看已提现的管理人申请信息</li>

        </ul>
    </div>

    <div id="flexigrid"></div>

    <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
    <div class="ncap-search-bar">
        <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
        <div class="title">
            <h3>高级搜索</h3>
        </div>
        <form method="get" name="formSearch" id="formSearch">
            <input type="hidden" name="advanced" value="1" />
            <div id="searchCon" class="content">
                <div class="layout-box">
                    <dl>
                        <dt>管理人ID</dt>
                        <dd>
                            <input type="text" name="uid" class="s-input-txt" placeholder="请输入管理人ID关键字" />
                        </dd>
                    </dl>
                    <dl>
                        <dt>公司名称</dt>
                        <dd>
                            <input type="text" name="manager_name" class="s-input-txt" placeholder="请输入公司名称关键字" />
                        </dd>
                    </dl>

                    <dl>
                        <dt>申请时间</dt>
                        <dd>
                            <label>
                                <input type="text" name="sdate" data-dp="1" class="s-input-txt" placeholder="请输入起始时间" />
                            </label>
                            <label>
                                <input type="text" name="edate" data-dp="1" class="s-input-txt" placeholder="请输入终止时间" />
                            </label>
                        </dd>
                    </dl>
                    <dl>
                        <dt>付款时间</dt>
                        <dd>
                            <label>
                                <input type="text" name="sdate2" data-dp="1" class="s-input-txt" placeholder="请输入起始时间" />
                            </label>
                            <label>
                                <input type="text" name="edate2" data-dp="1" class="s-input-txt" placeholder="请输入终止时间" />
                            </label>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="bottom">
                <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
                <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
            </div>
        </form>
    </div>

</div>

<script>

    $(function() {
        var flexUrl = 'index.php?con=manager_rebill_check&fun=get_index_xml';

        $("#flexigrid").flexigrid({
            url: flexUrl,
            colModel: [
                {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
                {display: '管理人ID', name : 'uid', width : 100, sortable : true, align: 'center'},
                {display: '公司名称', name : 'manager_name', width: 100, sortable : true, align : 'center'},
                {display: '管理人等级', name : 'grade', width: 80, sortable : true, align : 'center'},
                {display: '管理区域', name : 'area', width : 250, sortable : true, align: 'center'},
                {display: ' 提现金额', name : 'total', width : 120, sortable : true, align: 'center'},
                {display: '申请时间', name : 'apply_date', width : 120, sortable : true, align: 'center'},
                {display: '付款时间', name : 'pay_date', width : 120, sortable : true, align: 'center'},
                {display: '提现状态', name : 'state', width : 120, sortable : true, align: 'center'}
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
                        location.href = qs+'&con=manager_rebill_check&fun=export_step2&ids=' + ids.join(',');
                    }
                }
            ],
            searchitems: [
                {display: '管理人ID', name: 'uid', isdefault: true},
                {display: '公司名称', name: 'manager_name'}
            ],
            sortname: "grade",
            sortorder: "asc",
            title: '管理人列表'
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

