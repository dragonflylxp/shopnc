<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>平台兑换卷</h3>
        <h5>商城兑换卷设置生成及用户充值使用状态</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">列表</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台发布兑换卷，用户可在会员中心通过输入正确兑换卷号进行使用。</li>
      <li>已经被使用的兑换卷不能被删除。</li>
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
              <dt>兑换卷卷号</dt>
              <dd>
                <input type="text" name="sn" class="s-input-txt" placeholder="请输入兑换卷卷号" />
              </dd>
            </dl>
            <dl>
              <dt>批次标识</dt>
              <dd>
                <input type="text" name="batchflag" class="s-input-txt" placeholder="请输入批次标识关键字" />
              </dd>
            </dl>
            <dl>
              <dt>发布管理员</dt>
              <dd>
                <input type="text" name="admin_name" class="s-input-txt" placeholder="请输入发布管理员关键字" />
              </dd>
            </dl>
            <dl>
              <dt>使用人</dt>
              <dd>
                <input type="text" name="member_name" class="s-input-txt" placeholder="请输入使用人" />
              </dd>
            </dl>
            <dl>
              <dt>状态</dt>
              <dd>
                <select name="state" class="s-select">
                    <option value="">全部</option>
                    <option value="1">已使用</option>
                    <option value="0">未使用</option>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>发布时间</dt>
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
              <dt>使用时间</dt>
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
    var flexUrl = 'index.php?con=exchange_card&fun=index_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 60, sortable: false, align: 'center', className: 'handle-s'},
            {display: '兑换卷卷号', name: 'sn', width: 250, sortable: false, align: 'left'},
            {display: '批次标识', name: 'batchflag', width: 80, sortable: false, align: 'left'},
            {display: '面额(元)', name: 'denomination', width: 80, sortable: 1, align: 'left'},
            {display: '发布管理员', name: 'admin_name', width: 80, sortable: false, align: 'left'},
            {display: '发布时间', name: 'tscreated', width: 128, sortable: 1, align: 'left'},
            {display: '使用人', name: 'member_name', width: 90, sortable: false, align: 'left'},
            {display: '使用时间', name: 'tsused', width: 128, sortable: 1, align: 'left'},
            {display: '使用状态', name: 'state', width: 80, sortable: 1, align: 'left'},
            {display: '开始使用时间', name: 'start_time', width: 128, sortable: 1, align: 'left'},
            {display: '结束使用时间', name: 'end_time', width: 128, sortable: 1, align: 'left'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增兑换卷',
                name: 'add',
                bclass: 'add',
                title: '添加新数据到列表',
                onpress: function() {
                    location.href = '<?php echo urlAdminShop('exchange_card', 'add_card'); ?>';
                }
            },
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
                    location.href = qs+'&con=exchange_card&fun=export_step1&ids=' + ids.join(',');
                }
            },
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name: 'del',
                bclass: 'del',
                title: '将选定行数据批量删除',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }

                    var href = '<?php echo urlAdminShop('exchange_card', 'del_card', array(
                        'id' => '__ids__',
                    )); ?>'.replace('__ids__', ids.join(','));

                    $.getJSON(href, function(d) {
                        if (d && d.result) {
                            $("#flexigrid").flexReload();
                        } else {
                            alert(d && d.message || '操作失败！');
                        }
                    });
                }
            }
        ],
        searchitems: [
            {display: '兑换卷卷号', name: 'sn', isdefault: true},
            {display: '批次标识', name: 'batchflag'},
            {display: '发布管理员', name: 'admin_name'},
            {display: '使用人', name: 'member_name'}
        ],
        sortname: "id",
        sortorder: "desc",
        title: '平台兑换卷列表'
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
