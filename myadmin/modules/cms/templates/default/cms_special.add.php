<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=cms_special&fun=cms_special_list" title="返回专题列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_cms_special_manage'];?> -  新增专题页</h3>
        <h5><?php echo $lang['nc_cms_special_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?con=cms_special&fun=cms_special_save">
    <input name="special_id" type="hidden" value="<?php if(!empty($output['special_detail'])) echo $output['special_detail']['special_id'];?>" />
    <input id="special_html" type="hidden" name="special_html" value="" />
    <input id="special_state" name="special_state" type="hidden" value="" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="special_title"><em>*</em><?php echo $lang['cms_text_title'];?></label>
        </dt>
        <dd class="opt">
          <input id="special_title" name="special_title" class="input-txt" type="text" value="<?php if(!empty($output['special_detail'])) echo $output['special_detail']['special_title'];?>"/>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_title_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="special_title"><em>*</em>专题类型</label>
        </dt>
        <dd class="opt">
          <select name="special_type">
            <?php if(!empty($output['special_type_array']) && is_array($output['special_type_array'])) {?>
            <?php foreach($output['special_type_array'] as $special_type => $special_type_text) {?>
            <option value="<?php echo $special_type;?>" <?php echo $special_type == $output['special_detail']['special_type']?'selected':'';?>><?php echo $special_type_text;?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic">资讯类型将出现在资讯频道内，商城类型将出现在商城内</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['cms_special_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php if(!empty($output['special_detail']['special_image'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_image']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php if(!empty($output['special_detail']['special_image'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_image']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input class="type-file-file" id="special_image" name="special_image" type="file" size="30" hidefocus="true" nctype="cms_image" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input name="old_special_image" type="hidden" value="<?php echo $output['special_detail']['special_image'];?>" />
            </span></div>
          <span class="err"></span>
          <p class="notic"><span class="vatop rowform"><?php echo $lang['cms_special_image_explain'];?></span></p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['cms_special_background'];?></h3>
      </div>
      <dl class="row">
        <dt class="tit">背景颜色</dt>
        <dd class="opt">
          <input class="txt" name="special_background_color" type="color" value="<?php if(!empty($output['special_detail'])) echo $output['special_detail']['special_background_color'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_background_color_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_background_image'];?></dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"> <a href="<?php if(!empty($output['special_detail']['special_background'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_background']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>" nctype="nyroModal"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php if(!empty($output['special_detail']['special_background'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_background']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>>')" onMouseOut="toolTip()"></i> </a> </span> <span class="type-file-box">
            <input name="special_background" type="file" class="type-file-file" id="special_background" size="30" hidefocus="true" nctype="cms_image">
            <input name="old_special_background" type="hidden" value="<?php echo $output['special_detail']['special_background'];?>" />
            </span></div>
          <span class="err"></span>
          <p class="notic"><span class="vatop rowform"><?php echo $lang['cms_special_background_image_explain'];?></span></p>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_background_type'];?></dt>
        <dd class="opt">
          <label class="mr10">
            <input name="special_repeat" type="radio" value="no-repeat" <?php if($output['special_detail']['special_repeat'] == 'no-repeat') echo 'checked';?> />
            <?php echo $lang['cms_special_background_type_norepeat'];?></label>
          <label class="mr10">
            <input name="special_repeat" type="radio" value="repeat" <?php if($output['special_detail']['special_repeat'] == 'repeat') echo 'checked';?>/>
            <?php echo $lang['cms_special_background_type_repeat'];?></label>
          <label class="mr10">
            <input name="special_repeat" type="radio" value="repeat-x" <?php if($output['special_detail']['special_repeat'] == 'repeat-x') echo 'checked';?>/>
            <?php echo $lang['cms_special_background_type_xrepeat'];?></label>
          <label class="mr10">
            <input name="special_repeat" type="radio" value="repeat-y" <?php if($output['special_detail']['special_repeat'] == 'repeat-y') echo 'checked';?>/>
            <?php echo $lang['cms_special_background_type_yrepeat'];?></label>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_background_type_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_content_top_margin'];?></dt>
        <dd class="opt">&nbsp;
          <input class="txt" style=" width: 50px;" name="special_margin_top" type="text" value="<?php echo empty($output['special_detail']['special_margin_top'])?'0':$output['special_detail']['special_margin_top'];?>" />
          像素<span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_content_explain'];?></p>
        </dd>
      </dl>
    </div>
    <div class="ncap-form-all">
      <div class="title">
        <h3><?php echo $lang['cms_special_content'];?></h3>
        <ul class="tab-base nc-row">
          <li> <a id="btn_content_view" class="current" href="javascript:void(0);"><?php echo $lang['cms_text_view'];?></a> </li>
          <li> <a id="btn_content_edit" href="javascript:void(0);"><?php echo $lang['nc_edit'];?></a> </li>
        </ul>
      </div>
      <dl class="row">
        <dd class="opt nopd nobd nobs">
          <div class="tab-content" style="background-color: <?php echo $output['special_detail']['special_background_color'];?>; background-image: url(<?php if(!empty($output['special_detail']['special_background'])){echo getCMSSpecialImageUrl($output['special_detail']['special_background']);}?>); background-repeat: <?php echo $output['special_detail']['special_repeat'];?>; background-position: top center; width: 100%; padding: 0; margin: 0; overflow: hidden;">
            <div id="div_content_view" style=" background-color: transparent; background-image: none; width: 1000px; margin-top: <?php echo $output['special_detail']['special_margin_top']?>px; margin-right: auto; margin-bottom: 0; margin-left: auto; border: 0; overflow: hidden;"></div>
          </div>
          <div id="div_content_edit" class="tab-content" style="display:none;">
            <textarea id="special_content" name="special_content" rows="50" cols="80"><?php echo $output['special_detail']['special_content'];?></textarea>
          </div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_image_and_goods'];?></dt>
        <dd class="opt">
          <div class="ncap-upload-btn">
            <input class="input-file" type="file" name="special_image_upload" id="picture_image_upload" multiple  file_id="0" size="1" hidefocus="true" />
            <input id="submit_button" class="input-button" type="button" value="&nbsp;" onClick="submit_form($(this))" />
            <a href="javascript:void(0);" class="ncap-btn"><i class="fa fa-upload"></i><?php echo $lang['cms_text_image_upload'];?></a> </div>
          <div class="ncap-upload-btn">
            <input class="input-button" id="btn_show_special_insert_goods" type="button" value="" />
            <a href="javascript:void(0);" class="ncap-btn"><i class="fa fa-cubes"></i><?php echo $lang['cms_text_goods_add'];?></a></div>
          <p class="notic"><?php echo $lang['cms_special_image_explain1'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_image_list'];?></dt>
        <dd class="opt">
          <div class="cms-special-uploadpic">
            <ul id="special_image_list" class="ncap-thumb-list">
              <?php if(!empty($output['special_detail']['special_image_all'])) { ?>
                <?php $special_image_all = unserialize($output['special_detail']['special_image_all']);?>
                <?php if(!empty($special_image_all) && is_array($special_image_all)) { ?>
                  <?php foreach ($special_image_all as $value) {?>
                    <?php $image_url = getCMSSpecialImageUrl($value['image_name']);?>
                    <li class="picture">
                      <div class="size-64x64"> <span class="thumb size-64x64"><i></i> <img alt="" src="<?php echo $image_url;?>"> </span></div>
                      <p class="handle "><a image_url="<?php echo $image_url;?>" nctype="btn_show_image_insert_link" class="insert-link " title="<?php echo $lang['cms_special_image_tips1'];?>">&nbsp;</a><a image_name="<?php echo $value['image_name'];?>" image_url="<?php echo $image_url;?>" nctype="btn_show_image_insert_hot_point" class="insert-hotpoint  " title="<?php echo $lang['cms_special_image_tips2'];?>">&nbsp;</a><a image_name="<?php echo $value['image_name'];?>" nctype="btn_drop_special_image" class="delete  " title="<?php echo $lang['cms_special_image_tips3'];?>">&nbsp;</a></span> </p>
                      <input type="hidden" value="<?php echo $value['image_name'];?>" name="special_image_all[]">
                    </li>
                  <?php } ?>
                <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </dd>
      </dl>
    </div>

    <!--block-->

    <link href="<?php echo ADMIN_TEMPLATES_URL?>/css/block.css" rel="stylesheet" type="text/css">
    <div class="clearfixss tools-warp">
      <ul>
        <ol>
          <h2>实用工具</h2>
        </ol>
        <li>
          <div id="shade" style="display: none">
            <a href="javascript:;" id="addline" class="ncap-btn-big ncap-btn-green">添加</a>
            <a href="javascript:;" id="editline" class="ncap-btn-big ncap-btn-blue">编辑</a>
            <a href="javascript:;" id="delline" class="ncap-btn-big ncap-btn-orange">删除</a>
          </div>
          <div id="left_html">
            <?php  echo htmlspecialchars_decode($output['special_detail']['special_html']); ?>
          </div>
        </li>
        <li class="moveDiv">
          <h3>布局：</h3>
          <input type="hidden" id="blockTable_id" value="1">
          <div class="cube2-edit" id="blockTable">

            <table>
              <tbody>
              <tr>
                <td class="empty" data-x="0" data-y="0"></td>
                <td class="empty" data-x="1" data-y="0"></td>
                <td class="empty" data-x="2" data-y="0"></td>
                <td class="empty" data-x="3" data-y="0"></td>
              </tr>
              <tr>
                <td class="empty" data-x="0" data-y="1"></td>
                <td class="empty" data-x="1" data-y="1"></td>
                <td class="empty" data-x="2" data-y="1"></td>
                <td class="empty" data-x="3" data-y="1"></td>
              </tr>
              <tr>
                <td class="empty" data-x="0" data-y="2"></td>
                <td class="empty" data-x="1" data-y="2"></td>
                <td class="empty" data-x="2" data-y="2"></td>
                <td class="empty" data-x="3" data-y="2"></td>
              </tr>
              <tr>
                <td class="empty" data-x="0" data-y="3"></td>
                <td class="empty" data-x="1" data-y="3"></td>
                <td class="empty" data-x="2" data-y="3"></td>
                <td class="empty" data-x="3" data-y="3"></td>
              </tr>
              </tbody>
            </table>
          </div>
        </li>
        <li id="rightBox" style="display: none;" class="moveDiv">
          <div class="tools-warp-edit">
            <div class="control-group clearfixss">
              <label class="control-label clearfixss">
                <div class="fl"><em class="required">*</em>选择图片：</div><p class="help-desc">建议尺寸：160 x 160 像素</p></label>
              <input class="control-action js-trigger-image" type="file" name="special_image_upload" id="picture_image_upload_2" multiple="" file_id="0" size="1" hidefocus="true">
              <input type="hidden" id="select_box_id" value="">
            </div>
            <div class="control-group clearfixss">
              <label class="control-label">链接到：</label>
              <input type="text" name="" id="addLink_address">
              <a class="js-dropdown-toggle dropdown-toggle control-action" href="javascript:void(0);" id="addLink">添加链接地址</a>
            </div>
          </div>
        </li>
      </ul>
    </div>



    <!--block-->
    <div class="ncap-form-default">


      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-blue" id="btn_draft"><?php echo $lang['cms_special_draft'];?></a> <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="btn_publish"><?php echo $lang['cms_special_publish'];?></a></div>
    </div>
  </form>
  <!-- 插入图片链接对话框 -->
  <div id="_dialog_image_insert_link" style="display:none;">
    <div class="upload_adv_dialog dialog-image-insert-link">
      <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_special_image_link_explain1'];?></div>
      <div class="ncap-form-default" id="upload_adv_type">
        <dl class="row">
          <dt class="tit">插入图片预览</dt>
          <dd class="opt">
            <div class="dialog-pic-thumb"><a><img alt="" src=""></a></div>
          </dd>
        </dl>
        <dl class="row" id="upload_adv_type">
          <dt class="tit"><?php echo $lang['cms_special_image_link_url'];?></dt>
          <dd class="opt">
            <input nctype="_image_insert_link" type="text" class="input-txt" placeholder="http://"/>
            <p class="notic"><?php echo $lang['cms_special_image_link_url_explain'];?>如不填加任何链接请保持默认。</p>
          </dd>
        </dl>
        <div class="bot"><a nctype="btn_image_insert_link" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" ><?php echo $lang['cms_text_save'];?></a></div>
      </div>
    </div>
  </div>
  <!-- 插入图片热点对话框 -->
  <div id="_dialog_image_insert_hot_point" style="display:none;">
    <div class="dialog-image-insert-hot-point">
      <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_special_image_link_hot_explain1'];?></div>
      <div class="ncap-form-default" id="upload_adv_type">
        <div ncytpe="div_image_insert_hot_point" class="special-hot-point"><img nctype="img_hot_point" alt="" src="<?php echo $image_url;?>"> </div>
        <dl class="row">
          <dt class="tit"><?php echo $lang['cms_special_image_link_hot_url'];?></dt>
          <dd class="opt">
            <input nctype="x1" type="hidden" />
            <input nctype="y1" type="hidden" />
            <input nctype="x2" type="hidden" />
            <input nctype="y2" type="hidden" />
            <input nctype="w" type="hidden" />
            <input nctype="h" type="hidden" />
            <input nctype="url" type="text" class="input-txt" placeholder="http://" />
            <a class="ncap-btn" nctype="btn_hot_point_commit" href="javascript:void(0);"><i class="fa fa-plus"></i>添加热点</a>
            <p class="notic"><?php echo $lang['cms_special_image_link_url_explain'];?></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">已添加的热点区域</dt>
          <dd class="opt">
            <ul nctype="list" class="hot-point-list">
            </ul>
          </dd>
        </dl>
        <div class="bot"><a nctype="btn_image_insert_hot_point" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" ><?php echo $lang['cms_special_insert_editor'];?></a></div>
      </div>
    </div>
  </div>
  <!-- 插入商品对话框 -->
  <div id="_dialog_special_insert_goods" style="display:none;">
    <div class="upload_adv_dialog dialog-special-insert-goods">
      <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_special_goods_explain1'];?></div>
      <div class="ncap-form-default" id="upload_adv_type">
        <dl class="row">
          <dt class="tit"> <?php echo $lang['cms_special_goods_url'];?></dt>
          <dd class="opt">
            <input nctype="_input_goods_link" type="text" class="input-txt"/>
            <a class="ncap-btn" nctype="btn_special_goods" href="javascript:void(0);"><?php echo $lang['cms_text_save'];?></a>
            <p class="notic"><?php echo $lang['cms_special_goods_explain3'];?></p>
          </dd>
        </dl>
        <div class="dialog-goods">
            <ul nctype="_special_goods_list" class="special-goods-list">
        </ul>
        </div>
        <div class="bot"><a nctype="btn_special_insert_goods" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"><?php echo $lang['cms_special_insert_editor'];?></a></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<link media="all" rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.imgareaselect/imgareaselect-animated.css" type="text/css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.imgareaselect/jquery.imgareaselect.min.js"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/cms/cms_special.js" charset="utf-8"></script>

<!--block-->
<script>
  var colorArr = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16];
  /**
   * 魔方JS实现
   * @author Chenli
   */
  $(document).ready(function() {

    //禁止A标签跳转
    $("a").live("click",function(e){
      // return false;
      e.preventDefault();
    })

    $(".empty").live("click", function () {
      //点击的起点位置
      var this_y = $(this).attr("data-y");
      var this_x = $(this).attr("data-x");
      var blockCount_x = 3-this_x+1 ;//X数量 =UL
      var blockCount_y = 3-this_y+1; // Y数量 = LI

      /*-----------------------------*/
      var d = DialogManager.create('selectBlock');
      d.setTitle('选择区块');
      var html = "";
      html = "<div><div class='modal-body clearfixss layout-table'>";
      //确定UL数量
      for (var ul = 1; ul <= blockCount_x ; ul++) {
        html += "<ul class='layout-cols layout-cols-" + ul + "'>";
        //确定LI数量
        for (var li = 1; li <= blockCount_y ; li++) {
          html += "<li data-cols='" + ul + "' class='blockSelect' data-rows='"+li+"' ></li>";
        }

        html += "</ul>";
      }
      html+= "<input type='hidden' id='point' start-x='"+this_x+"' start-y="+this_y+">";
      html += "</div></div>";
      d.setContents(html);
      d.show('center', 1);
    })

    //弹出窗口选择区块
    $(".layout-cols > li").live("mouseover", function () {
      var this_y = $(this).attr("data-rows");
      var this_x = $(this).attr("data-cols");
      $(".layout-cols > li").each(function () {
        if (this_y < $(this).attr("data-rows") || this_x < $(this).attr("data-cols")) {
          $(this).removeClass("selected");
        } else {
          $(this).addClass("selected");
        }
      })

      //弹出区块点击事件
      $(".blockSelect").live("click", function () {
        DialogManager.create('selectBlock');
        var this_y = $(this).attr("data-rows");
        var this_x = $(this).attr("data-cols");
        html = tableBlock(this_x,this_y);
        $("#blockTable").html(html);
        DialogManager.close("selectBlock");
        $("#rightBox").hide();
      })
    })

    /**
     *处理table
     * col - x
     * row - y
     * return html
     */
    function tableBlock(col, row) {
      var start_x=  $("#point").attr("start-x"); //起始定位-x坐标
      var start_y=  $("#point").attr("start-y"); //起始定位-y坐标
      var start_x_table = parseInt(start_x)+(parseInt(col)-1);
      var start_y_table = parseInt(start_y)+(parseInt(row)-1);
      var tdCount =0;
      var tdArr = new Array();//TD队列
      $("#blockTable").find("td").each(function () {
        //tdArr.push($(this).prop('outerHTML'))
        tdArr.push({0:$(this),"1":$(this).prop('outerHTML')});
      })

      //剔除不符合条件的td标签
      var countNum = 0;
      var removeTdArr = new Array(); //去除的数组
      for(var _y = 0 ;_y<4 ;_y++){
        for (var _x =0 ; _x<4 ; _x++){
          if(start_x <= _x && _x <= start_x_table){ //判断X区间
            if(start_y <= _y && _y <= start_y_table){ //判断Y区间
              //去除当前区间的数组
              removeTdArr.push([_x,_y]);
            }
          }
          countNum++;
        }
      }

      //return false;
      var new_count = 0;
      //剔除不符合条件的TD重组数组
      for(var tdi = 0 ; tdi<tdArr.length ; tdi++){
        for(var r_tdi =0; r_tdi<removeTdArr.length ; r_tdi++){
          if(tdArr[tdi][0].data('x') == removeTdArr[r_tdi][0] && tdArr[tdi][0].data('y') == removeTdArr[r_tdi][1] ){
            //相同去除
            tdArr.splice(new_count,1);
          }
        }
        new_count++;
      }

      //打印TR
      var html = "<table><tbody>";
      // var color = colorArr.shift();
      var i  =0;
      for (var y = 0; y < 4; y++) {

        html += "<tr>";
        /*------td------*/
        for(var x = 0 ; x<4 ;x++){
          //console.log('x :'+x+'| y: '+y);
          if(start_x == x  && start_y == y){
            //找到合并位置
            html += "<td class='not_empty td_color_" + colorArr[i] + "' id='not_empty_"+i+"' data-x='"+x+"'  data-y='"+y+"' rowspan='" + row + "' colspan='" + col + "'/>";
          }else{
            for (var arri = 0 ;arri<tdArr.length;arri++){
              if(tdArr[arri][0].data("x") == x && tdArr[arri][0].data("y") == y){
                html += tdArr[arri][1];
              }
            }
          }
          i++;
        }
      }
      /*------td------*/
      html += "</tr>";
      html += "</tbody></table>";
      return html;
    }

    /**
     * 点击选中框事件
     */
    $("#blockTable").find(".not_empty").live("click",function(){
      var this_id=$(this).attr("id");
      $(".not_empty").each(function(){
        $(this).css("border","");
      })
     // $(this).css("border","");
      $(this).addClass('bor');
      $("#rightBox").css('display',"block");
      $("#addLink").attr("data-id",this_id);
      $("#addLink").attr("data-table-id",$("#blockTable_id").val());
      var href = $(this).children().attr("href");
      //   console.log(href);
      $("#addLink_address").val(href);
      $("#select_box_id").val(this_id);
    })

    /**
     * upload图片上传
     */
    $("#picture_image_upload_2").fileupload({
      dataType: 'json',
      url: "index.php?con=cms_special&fun=special_image_upload",
      done: function (e,data) {
        result = data.result;
        if(result.status == 'success'){
          var TableId = $("#select_box_id").val();
          var html="";
          console.log(TableId);
          //清除原有的img
          $("#blockTable").find("#"+TableId).children("a").remove();
          var imgpath  =result.file_url ;// 图片绝对地址
          html="<a href='' id='href_"+TableId+"'>";
          html +="<img src='"+imgpath+"'>";
          html+="</a>";
          $("#blockTable").find("#"+TableId).append(html);
          changeTalbe();
        }
      }
    });
    //更改左边的浏览效果图
    function changeTalbe() {
      //获取当前中间talbe的id
      var blockTable_id = $("#blockTable_id").val();
      console.log(blockTable_id);
      var html ="";
      //   console.log($("#left_block_id_"+blockTable_id).html());
      if(typeof($("#left_block_id_"+blockTable_id).html()) == "undefined"){
        $("#left_block_id_"+blockTable_id).remove();
        html += "<div id='left_block_id_"+blockTable_id+"' data-id='"+blockTable_id+"' class='left_html_class'>";
        html  += $("#blockTable").html();//方框html
        html += "</div>";
        $("#left_html").append(html);
      }else{
        $("#left_block_id_"+blockTable_id).children().remove();
        html  = $("#blockTable").html();//方框html
        $("#left_block_id_"+blockTable_id).append(html);
      }
    }

    /**
     * 添加链接
     */
    $("#addLink").click(function(){
      var url =$("#addLink_address").val();
      var data_id = $(this).attr("data-id");
      var table_id =$(this).attr("data-table-id");
      var a  = $("#blockTable").find("#"+data_id).html();
      $("#blockTable").find("#href_"+data_id).attr("href",url);

      changeTalbe();
    })

    //显示操作框
    $(".left_html_class").live("mouseover",function(){
      var id =$(this).attr("data-id");
     // var height =  $(this).offset().top;
      var totalHeight =0;
      $(".left_html_class").each(function(){
        if($(this).attr('data-id') <=id ){
          totalHeight += $(this).height();
        }

     })
      console.log(totalHeight);
    /*  var nei_height = $("#left_block_id_"+id).height();
      var box_height = $("#left_html").height();*/

      $("#shade").css("top",parseInt(totalHeight)-50);
      //console.log(height);

      $("#shade").attr("data-id",id);
      $("#shade").show();
      //console.log(height);
    })
    $("#shade").live("mouseover",function(){
      $("#shade").show();
    })

    $(".left_html_class").live("mouseout",function(){
      // $("#shade").removeAttrs("data-id");
      $("#shade").hide();
    })

    //新增一栏
    $("#addline").live("click",function(){
      $("#blockTable_id").val(parseInt($("#blockTable_id").val())+1); //中间标识 +1
      $("#blockTable").children().remove();//删除原有table

      var height =  $(this).offset().top;
     // $(".moveDiv").css("margin-top",height+5);
      //console.log(height);
      //生成新的table
      var html =createTable();
      $("#blockTable").append(html); //追加到新的一栏
    })

    //修改当前栏
    $("#editline").live("click",function(){
      $("#blockTable").children().remove();//删除原有table
      var id =$("#shade").attr("data-id");
      //$("#addLink").attr("data-id",id);
      // console.log(id);
      var height =  $(this).offset().top;
      $(".moveDiv").css("margin-top",0);
      $("#blockTable_id").val(id);
      $("#blockTable").append($("#left_block_id_"+id).html());
      $("#rightBox").hide();
    })

    //删除当前栏
    $("#delline").live("click",function(){
      var id =$("#shade").attr("data-id");
      var html ="";
      var height =  $(this).offset().top;
      $(".moveDiv").css("margin-top",0);
      $("#left_block_id_"+id).remove();
      $("#shade").hide();
      /* if(id<=1){
       html =createTable();
       }else{
       var new_id = parseInt(id)-1;
       $("#shade").attr("data-id",new_id);
       $("#blockTable_id").val(new_id);
       html=$("#left_block_id_"+new_id).html();
       }*/
      if(id == 1){
        html = createTable();
      }
      $("#blockTable").children().remove();//删除原有table
      $("#blockTable").append(html);
    })

    //生成一个新的table标签
    function createTable(){
      var html = "<table><tbody>";
      for (var y = 0; y < 4; y++) {
        html += "<tr>";
        /*------td------*/
        for(var x = 0 ; x<4 ;x++){
          html +="<td class='empty' data-x='"+x+"' data-y='"+y+"'></td>";
        }
      }
      /*------td------*/
      html += "</tr>";
      html += "</tbody></table>";
      return html;
    }

    /**
     * 判断数组是否存在相同值
     */
    function in_array(search, array) {
      for (var i in array) {
        if (array[i] == search) {
          return true;
        }
      }
      return false;
    }

    /**
     *删除数组指定下标或指定对象
     */
    Array.prototype.remove=function(obj){
      for(var i =0;i <this.length;i++){
        var temp = this[i];
        if(!isNaN(obj)){
          temp=i;
        }
        if(temp == obj){
          for(var j = i;j <this.length;j++){
            this[j]=this[j+1];
          }
          this.length = this.length-1;
        }
      }
    }
  })
</script>
<!--block-->
<script type="text/javascript">
    $(document).ready(function(){
    $("#btn_draft").click(function() {
        $("#special_html").val($("#left_html").prop('outerHTML'));
        $("#special_state").val("draft");
        $("#add_form").submit();
    });
    $("#btn_publish").click(function() {
        $("#special_html").val($("#left_html").prop('outerHTML'));
        $("#special_state").val("publish");
        $("#add_form").submit();
    });
    $('#add_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parents("tr").prev().find('td:first'));
        },
        rules : {
            <?php if(empty($output['special_detail'])) {?>
            special_image: {
                required : true
            },
            <?php } ?>
            special_title: {
                required : true,
                maxlength : 24,
                minlength : 4
            }
        },
        messages : {
            <?php if(empty($output['special_detail'])) {?>
            special_image: {
                required : "<?php echo $lang['cms_special_image_error'];?>"
            },
            <?php } ?>
            special_title: {
                required : "<?php echo $lang['cms_title_not_null'];?>",
                maxlength : "<?php echo $lang['cms_title_max'];?>", 
                minlength : "<?php echo $lang['cms_title_min'];?>" 
            }
        }
    });


    });
</script>