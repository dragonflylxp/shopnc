<?php defined('Inshopec') or exit('Access Invalid!');?>
<style type="text/css">
    .flexigrid .bDiv tr:nth-last-child(2) span.btn ul { bottom: 0; top: auto}
</style>


<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>视频分类管理</h3>
                <h5>管理数据的新增、编辑、删除</h5>
            </div>
        </div>
    </div>
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
        </div>
        <ul>
            <li>当开启视频后，添加视频时可选择视频分类，用户可根据分类查看视频列表</li>
        </ul>
    </div>
    <form method='post'>
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="submit_type" id="submit_type" value="" />
        <table class="flex-table">
            <thead>
            <tr>
                <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
                <th width="150" class="handle" align="center">操作</th>
                <th width="60" align="center">排序</th>
                <th width="200" align="left">分类名称</th>
                <th width="150" align="left">分类图片</th>
                <th width="300" align="left">分类简介</th>
                <th width="300" align="left">是否推荐</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
                <?php foreach($output['class_list'] as $k => $v){ ?>
                    <tr data-id="<?php echo $v['cate_id'];?>">
                        <td class="sign"><i class="ico-check"></i></td>
                        <td class="handle">
                            <a class="btn red" href="javascript:void(0);" onclick="fg_del(<?php echo $v['cate_id'];?>);"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
                            <a class="btn red" href="index.php?con=mb_video_category&fun=video_category_edit&cate_id=<?php echo $v['cate_id'];?>"><i class="fa fa-pencil-square-o"></i>编辑</a></li>
                        </td>
                        <td><?php echo $v['cate_sort'];?></td>
                        <td><?php echo $v['cate_name'];?></td>
                        <td>
                            <?php if ($v['cate_image'] != '') { ?>
                            <a class="pic-thumb-tip" onmouseover="toolTip('<img src=<?php echo $v['cate_image']; ?>>')" onmouseout="toolTip()" href="javascript:void(0);"> <i class="fa fa-picture-o"></i></a>
                            <?php } ?>
                        </td>
                        <td><?php echo $v['cate_description'];?></td>
                        <td><?php echo $v['is_recommend'];?></td>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr>
                    <td class="no-data" colspan="100"><i class="fa fa-exclamation-circle"></i><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $('.flex-table').flexigrid({
            height:'auto',// 高度自动
            usepager: false,// 不翻页
            striped:false,// 不使用斑马线
            resizable: false,// 不调节大小
            title: '视频分类列表',// 表格标题
            reload: false,// 不使用刷新
            columnControl: false,// 不使用列控制
            buttons : [
                {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation },
                {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation },
            ]
        });

        $('span[nc_type="inline_edit"]').inline_edit({act: 'mb_video_category',op: 'ajax'});
    });

    function fg_operation(name, bDiv) {
        if (name == 'add') {
            window.location.href = 'index.php?con=mb_video_category&fun=video_category_add';
        } else if (name == 'del') {
            if ($('.trSelected', bDiv).length == 0) {
                showError('请选择要操作的数据项！');
            }
            var itemids = new Array();
            $('.trSelected', bDiv).each(function(i){
                itemids[i] = $(this).attr('data-id');
            });
            fg_del(itemids);
        } else if (name = 'csv') {
            window.location.href = 'index.php?con=mb_video_category&fun=video_category_export';
        }
    }
    function fg_del(ids) {
        if (typeof ids == 'number') {
            var ids = new Array(ids.toString());
        };
        id = ids.join(',');
        if(confirm('删除后将不能恢复，确认删除这项吗？')){
            $.getJSON('index.php?con=mb_video_category&fun=video_category_del', {id:id}, function(data){
                if (data.state) {
                    location.reload();
                } else {
                    showError(data.msg)
                }
            });
        }
    }
</script>