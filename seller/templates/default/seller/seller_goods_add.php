<?php defined('Inshopec') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/rzxy.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">


<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/jquery.js"></script>
 <script>jQuery.noConflict()</script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 
<script src="<?php echo MOBILE_TEMPLATES_URL;?>/js/cropper.js" type="text/javascript"></script>
<script src="<?php echo MOBILE_TEMPLATES_URL;?>/js/lrz.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/cropper.css">
<style type="text/css">
.btnselect{
display: inline-block;
height: 0.9rem;
padding: 0.25rem 0.5rem;
font-size: 0.55rem;
color: #888;
line-height: 0.9rem;
background: #FFF;
border: solid 0.05rem #EEE;
border-radius: 0.15rem;
}

.current {
padding: 0.28rem 0.53rem;
color: #FFF;
background: #0094DE;
border: none;
}
.border_bottom textarea{
  width: 100%;
  height: 2rem;
  border:none;
}
.border_bottom dt em{
  color: red;
}

.upload_del{
  width: 20px;
  height: 20px;
  background: red;
  display: inline-block;
  position: absolute;
  right: 0;
  top: -2px;
  font-size: 16px;
  text-align: center;
  line-height: 20px;
  z-index:5;
  border-radius: 50%;
  color: #fff;
  opacity: 0.6;
  cursor: pointer;

}
a.ncbtn { font: normal 12px/20px "microsoft yahei", arial; color: #FFF; background-color: #CCD0D9; text-align: center; vertical-align: middle; display: inline-block; *display: inline; height: 20px; padding: 5px 10px; border-radius: 3px; cursor: pointer; *zoom: 1;}
ul.select_ul li{
  width: 100%;
  text-align: center;
  height: 30px;
  line-height: 30px;
  border-bottom: 1px solid #eee;
  display: inline-block;

}

</style>
</head>
<body>



<header id="header" class="fixed">
  <div class="header-wrap">
    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
   <div class="header-title">
      <h1>商品添加</h1>
    </div>
   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
   </div>
   <?php include template('layout/seller_toptip');?>
</header>
<div class="nctouch-main-layout ">
<div class="alert">
    <h4>注意事项：</h4>
    手机端商家中心只能进行简单的商品信息添加，如需添更多信息,请登录pc端商家中心进行添加...
</div>

<form action="<?php echo urlMobile('seller_goods','goods_add');?>" method="post" id="sub_goods">
   <input type="hidden" name="commonid" value="" />
   <input type="hidden" name="type_id" value="1">
<div class="nctouch-home-block">

  <div class="tit-bar"><i style="background:#EC5464;"></i>商品基本信息</div>



  <div class="input_box">

    <dl class="border_bottom">

        <dt>商品分类</dt>

        <dd id="g_cate">
        请选择分类
       
        </dd>
         <input type="hidden" name="cate_id" value="">
          <input type="hidden" name="cate_name" value="">
    </dl>

     <dl class="border_bottom">

        <dt><em>*</em>商品名称</dt>

        <dd>

             <input id="g_name" name="g_name" type="text" value="" placeholder="商品标题名称长度至少3个字符，最长50个汉字"/>

        </dd>

    </dl>

   <dl class="border_bottom">

        <dt>商品卖点</dt>

        <dd>

            <textarea id="g_jingle" name="g_jingle" placeholder="商品卖点最长不能超过140个汉字"></textarea>

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt>商品价格</dt>

        <dd>

           <input id="g_price" name="g_price" type="text" value="" placeholder="0.01~9999999之间的数字，且不能高于市场价"/>



        </dd>

    </dl>

   <dl class="border_bottom">

        <dt>市场价</dt>

        <dd>

             <input id="g_marketprice" name="g_marketprice" type="text" value="" placeholder="0.01~9999999之间的数字，此价格仅为市场参考售价"/>



        </dd>

    </dl>

    <dl class="border_bottom">

        <dt>成本价</dt>

        <dd>

          <input id="g_costprice" name="g_costprice" type="text" value="" placeholder="0.00~9999999之间的数字，非必填选项"/>

        </dd>

    </dl>
    <dl class="border_bottom">
        <dt>折扣</dt>
        <dd>
           <input id="g_discount" name="g_discount" type="text" value="0" placeholder="根据销售价与市场价比例自动生成，不需要编辑"/>
        </dd>
    </dl>
    <dl class="border_bottom">
        <dt>商品库存</dt>
        <dd>
           <input id="g_storage" name="g_storage" type="text" value="" placeholder="为0~999999999之间的整数"/>
        </dd>
    </dl>
   <dl class="border_bottom">
        <dt>库存预警值</dt>
        <dd>
          <input id="g_alarm" name="g_alarm" type="text" value="" placeholder="请填写0~255的数字，0为不预警"/>
        </dd>
    </dl>
    <dl class="border_bottom">
        <dt>商品货号</dt>
        <dd>
          <input id="g_serial" name="g_serial" type="text" value="" placeholder="最多可输入20个字符，支持输入中文、字母、数字、_、/、-和小数点"/>
        </dd>
    </dl>
    <dl class="border_bottom">
        <dt>商品条形码</dt>
        <dd>
          <input id="g_barcode" name="g_barcode" type="text" value=""/>
        </dd>
    </dl>
    <dl class="border_bottom">
        <dt>运费</dt>
        <dd>
          <input id="freight_0" nctype="freight" name="freight" class="radio" checked="checked" value="0" type="radio">
          <label for="freight_0">固定运费</label> 
          <input id="freight_1" nctype="freight" name="freight" class="radio" value="1" type="radio">
          <label for="freight_1">选择售卖区域</label>
            <div nctype="div_freight">
            <input id="g_freight" name="g_freight" type="text" value="" style="border:1px solid #e44d4d;" />
          </div>
            <div nctype="div_freight" style="display: none;">
                    <input id="transport_id" value="" name="transport_id" type="hidden">
                    <input id="transport_title" value="" name="transport_title" type="hidden">
                    <span id="postageName" class="transport-name"></span>
                    <a  class="ncbtn" id="postageButton"><i class="icon-truck"></i>选择售卖区域</a> 
             </div>
        </dd>
    </dl>

    <dl class="border_bottom">
        <dt>上架</dt>
        <dd>
          <div class="input-box ">
              <label>
                <input type="checkbox" class="checkbox" id="g_state" name="g_state" autocomplete="off" />
                 <span class="power"><i></i></span> </label>
            </div>
        </dd>
    </dl>
  </div>
</div>
  <div class="nctouch-home-block mt5">
  <div class="tit-bar"><i style="background:#EC5464;"></i>商品图片(可上传5张商品图片)</div>
  <div class="evaluation-upload-block">
        <div class="tit"><i></i><p>添&nbsp;加</p></div>
        <div class="nctouch-upload">
          <a href="javascript:void(0);">
            <span><input hidefocus="true" size="1" class="input-file list_1 " name="file" type="file" onchange="upload(this)" capture="camera" list="1"></span>
            
            <p><i class="icon-upload"></i></p>
            </a>
          <input name="img[0]" value="" type="hidden">
        </div>
         <div class="nctouch-upload">
          <a href="javascript:void(0);">
            <span><input hidefocus="true" size="1" class="input-file list_2" name="file" type="file" onchange="upload(this)" capture="camera" list="2" ></span>
          
            <p><i class="icon-upload"></i></p>
            </a>
          <input name="img[1]" value="" type="hidden">
        </div>
         <div class="nctouch-upload">
          <a href="javascript:void(0);">
            <span><input hidefocus="true" size="1" class="input-file list_3" name="file" type="file"  onchange="upload(this)" capture="camera" list="3"></span>
           
            <p><i class="icon-upload"></i></p>
            </a>
          <input name="img[2]" value="" type="hidden">
        </div>
         <div class="nctouch-upload">
          <a href="javascript:void(0);">
            <span><input hidefocus="true" size="1" class="input-file list_4" name="file" type="file" onchange="upload(this)" capture="camera" list="4"></span>
            
            <p><i class="icon-upload"></i></p>
            </a>
          <input name="img[3]" value="" type="hidden">
        </div>
         <div class="nctouch-upload">
          <a href="javascript:void(0);">
            <span><input hidefocus="true" size="1" class="input-file list_5" name="file" type="file" onchange="upload(this)" capture="camera" list="5"></span>
           
            <p><i class="icon-upload"></i></p>
            </a>
          <input name="img[4]" value="" type="hidden">
        </div>
  </div>
</div>

<div class="nctouch-home-block mt5">
<div class="tit-bar"><i style="background:#EC5464;"></i>商品详情</div>
<div class="input_box">
 <dl class="border_bottom">
        <dd style="margin-left:0px;">
            <textarea id="g_body" name="g_body" placeholder="简单商品详情描述" style="height:4rem;"></textarea>
        </dd>
    </dl>
</div>
</div>
<input type="hidden" name="b_id" value="">
<input type="hidden" name="b_name" value="">
<input type="hidden" name="add_album" value="">
<input type="hidden" name="jumpMenu" value="0">
<input type="hidden" name="sup_id" value="0">
<input type="hidden" name="plate_bottom" value="请选择">
<input type="hidden" name="plate_top" value="请选择">
<input type="hidden" name="sgcate_id[0]" value="">
<input type="hidden" name="freight" value="0">
<input type="hidden" name="g_commend" value="1">
<input type="hidden" name="city_id" value="">
<input type="hidden" name="province_id" value="">
<input type="hidden" name="g_vat" value="0">
<input type="hidden" name="m_body" value="">
<input type="hidden" name="goods_image" value="">
<input type="hidden" name="search_brand_keyword" value="">
<input type="hidden" name="region" value="">
<a class="btn-l mt5 mb5" id="edit_goods">添加商品</a>
</form>

<div class="fix-block-r">

  <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

    <div class="constrast-brand hide">
        <ul class="select_ul">
        
        <?php if(is_array($output['list']) && !empty($output['list'])){?>
        <?php foreach($output['list'] as $vt){?>
            <li><a href="javascript:;" onclick="selectul('<?php echo $vt['title'];?>',<?php echo $vt['id'];?>,<?php echo intval($output['extend'][$v['id']]['price']);?>)"><?php echo $vt['title'];?></a></li>
        
        <?php }?>
        <?php }?>     
        </ul>
    </div>

</div>
  <script>
          function selectul(title,id,price){
              $("#transport_id").val(id);
              $("#transport_title").val(title);
              $("#g_freight").val(price);

              $("#postageName").text(title).css({'display':'inline-block'});
              layer.closeAll();

            }
        jQuery(function(){
          jQuery("#header .top_home").on("click", function () { jQuery("#header .home_menu").toggle(); });
              // 运费部分显示隐藏
            jQuery('#freight_0').click(function(){
             
                    jQuery('div[nctype="div_freight"]').eq(0).show();
                    jQuery('div[nctype="div_freight"]').eq(1).hide();
            });
            jQuery('#freight_1').click(function(){
                  
                    jQuery('div[nctype="div_freight"]').eq(1).show();
                    jQuery('div[nctype="div_freight"]').eq(0).hide();
            });
            jQuery("#postageButton").click(function(){
                layer.open({ type: 2, content: "加载中..." });
         
                 
                  layer.closeAll();
                
                      layer.open({
                          type: 1,
                          title: "请选择售卖区域",
                          content: jQuery('.constrast-brand').html(),
                          shadeClose: false
                      });
                  
   
            })

        })
         function lackBack(){
                layer.closeAll();
        }

        //更换头像

        function upload_img() {
            jQuery("#vip-file").trigger("click");
        }


        //图像压缩

        function upload(the) {
            var list_id =jQuery(the).attr('list');
            lrz(the.files[0],{width:1280, quality: 1 }).then(function (rst) {
                    // 把处理的好的图片给用户看看呗
                    var img = new Image();
                    img.src = rst.base64;
                    img.onload = function () {
                        var load = layer.open({
                            type: 1,
                            shadeClose: false,
                             content: '<div class="container" style="width:100%; overflow:hidden"><div class="tx-head"><a href="#" id="img-save" style="float:right; font-size:16px; margin-right:15px; border:1px solid #5f5d5d; line-height:25px; padding:1px 8px; margin-top:8px;  border-radius:3px;">保存</a><a href="javascript:lackBack();"  class="new-a-back" id="backUrl"><span></span></a></div><img id="base64" src="' + rst.base64 + '"></div>',
                            style: 'width:100%; height:' + document.documentElement.clientHeight + 'px; background-color:#F2F2F2; border:none; overflow:hidden'
                        });

                        //裁剪框比例
                        jQuery('#base64').cropper({
                            aspectRatio: 1 / 1,
                            crop: function (data) {
                            },
                            guides: true,  //是否在剪裁框上显示虚线
                            movable: true,  //是否允许移动剪裁框
                            resizable: true,  //是否允许改变剪裁框的大小
                            dragCrop: true,  //是否允许移除当前的剪裁框，并通过拖动来新建一个剪裁框区域
                            minContainerWidth: 300,  //容器的最小宽度
                            minContainerHeight: 300  //容器的最小高度
                        })



                        //保存裁剪图片

                        jQuery("#img-save").click(function () {
                            var touxiang = jQuery('#base64').cropper('getCroppedCanvas', { width: 900, height: 900 }).toDataURL("image/jpeg", 0.9);
                            var loading = layer.open({
                                type: 2,
                                shadeClose:false
                            });
                            console.log(touxiang);
                            // 这里该上传给后端啦
                            $.ajax({
                                url: ApiUrl+"/index.php?con=seller_goods&fun=ajax_update_img",
                                type: "post",
                                data: { img: touxiang,list_id:list_id},//base64数据
                                dataType: "json",
                                success: function (data) {
                                   layer.closeAll();  
                                    if( jQuery('.list_'+data.list_id).parent().siblings('.pic-thumb').length > 0){
                                      jQuery('.list_'+data.list_id).parent().siblings('.pic-thumb').remove();
                                      jQuery('.list_'+data.list_id).parents("a").siblings('i').remove();
                                    }
                                
                                    jQuery('.list_'+data.list_id).parent().parent().after('<i class="upload_del">x</i>');
                                    jQuery('.list_'+data.list_id).parent().after('<div class="pic-thumb"><img src="' + data.url + '"/></div>');
                                    jQuery('.list_'+data.list_id).parents("a").siblings('input').val(data.img);


                                },error:function(data){
                                      layer.open({
                                        content: '图片上传失败！',
                                        time: 1.5
                                    });
                                }
                            });  

                        })

                    };

                })

                .catch(function (err) {
                    // 万一出错了，这里可以捕捉到错误信息
                    // 而且以上的then都不会执行
                    layer.open({
                        content: err,
                        time: 2
                    });
                })


        };



    </script>




<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/goods_add.js"></script> 
  