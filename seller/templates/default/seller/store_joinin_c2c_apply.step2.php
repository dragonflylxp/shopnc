<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/rzxy.css">

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

</style>

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

   <div class="header-title">

      <h1>店铺经营信息</h1>

    </div>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

       <?php include template('layout/seller_toptip');?>





</header>

<div class="nctouch-main-layout fixed-Width">

<div class="alert">

    <h4>注意事项：</h4>

    店铺经营类目为商城商品分类，请根据实际运营情况添加一个或多个经营类目。

</div>

<div class="nctouch-home-block">

  <div class="tit-bar"><i style="background:#EC5464;"></i>店铺经营信息</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>商家账号</dt>

        <dd><input id="seller_name" name="seller_name" value="" type="text"></dd>

    </dl>

     <dl class="border_bottom">

        <dt><i>*</i>店铺名称</dt>

        <dd>

           <input id="store_name" name="store_name" value="" type="text">

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>店铺等级</dt>

        <dd class="stroe_lever stroe_one">





              <?php if(!empty($output['grade_list']) && is_array($output['grade_list'])){ ?>

              <?php foreach($output['grade_list'] as $k => $v){ ?>

              <?php $goods_limit = empty($v['sg_goods_limit'])?'不限':$v['sg_goods_limit'];?>

              <?php $explain = '商品数：'.$goods_limit.' 模板数：'.$v['sg_template_number'].' 收费标准：'.$v['sg_price'].' 元/年 附加功能：'.$v['function_str'];?>

              <span class="btnselect" value="<?php echo $v['sg_id'];?>"><?php echo $v['sg_name'];?> </span>



              <?php } ?>

              <?php } ?>



        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>开店时长</dt>

        <dd class="stroe_time stroe_one">

        <span class="btnselect" value="1">1 年</span>

        <span class="btnselect" value="2">2 年</span>

        

        

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>店铺分类</dt>

        <dd class="stroe_cate stroe_one">





              <?php if(!empty($output['store_class']) && is_array($output['store_class'])){ ?>

              <?php foreach($output['store_class'] as $k => $v){ ?>

              <span class="btnselect" value="<?php echo $v['sc_id'];?>"><?php echo $v['sc_name'];?> (保证金：<?php echo $v['sc_bail'];?> 元)</span>

       

              <?php } ?>

              <?php } ?>



        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>经营类目</dt>

        <dd>

         

            <div id="gcategory" >



                <?php if(!empty($output['gc_list']) && is_array($output['gc_list']) ) {?>

                <?php foreach ($output['gc_list'] as $gc) {?>

                <span class="btnselect btnselect_<?php echo $gc['gc_id'];?>" value="<?php echo $gc['gc_id'];?>" data-explain="<?php echo $gc['commis_rate'];?>" ><?php echo $gc['gc_name'];?></span>

      

                <?php }?>

                <?php }?>

      

          

            </div>

       

            <span></span>

        </dd>

    </dl>

  </div>

</div>



<div class="nctouch-home-block mt5">



  <div class="input_box">

        <table id="table_category" class="type" border="0" cellpadding="0" cellspacing="0">

              <thead>

                <tr>

                  <th class="w120 tc">一级类目</th>

                

                  <th class="w50 tc">操作</th>

                </tr>

              </thead>

            <tbody>

            <tr class="store-class-item store-class-items">

              <td colspan="2">请添加分类</td>

           

            </tr>

            </tbody>

            </table>

      

  </div>

</div>





<a class="btn-l mt5 mb5" id="next_company_info">提交申请</a>



</div>

<div class="fix-block-r">

  <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>







<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 



<script>

  $(function(){

        $(".stroe_one .btnselect").click(function(){

            $(this).addClass('current').siblings().removeClass('current');

        })

       $("#gcategory .btnselect").click(function(){

          var name = $(this).text();

          var bl = $(this).attr('data-explain');

          var ids = $(this).attr('value');

          if($(this).hasClass('current')){

            $(this).removeClass('current');

            $(".list_"+ids).remove();

          }else{

            $(this).addClass('current');

            $('.store-class-items').remove();

            var inhtml= '<tr class="store-class-item list_'+ids+'">';

            inhtml+='<td>'+name+' (分佣比例：'+bl+'%)</td>';

            inhtml+='<td><a nctype="btn_drop_category" href="javascript:;" bid="'+ids+'">删除</a></td>';  

            inhtml+=' <input name="store_class_ids[]" value="'+ids+'" type="hidden">'; 

            inhtml+=' <input name="store_class_names[]" value="'+name+'" type="hidden"></tr>';  

            $('#table_category').append(inhtml);

            

          }

             

        })





      $(document).on("click","a[nctype='btn_drop_category']",function(){

         $(this).parent().parent().remove();

         var bid = $(this).attr('bid');

         $("span.btnselect_"+bid).removeClass('current');

         

      });

      $("#seller_name").keyup(function(){

         $.ajax({  

            type:"GET",  

            url:'<?php echo urlMobile('store_joinin_c2c', 'check_seller_name_exist');?>',  

            async:false,  

            data:{seller_name: $('#seller_name').val()},  

            success: function(data){  

                if(data == 'true') {

                    layer.open({content:'卖家账号已存在',time:1.5});

                    result = false;

                }

            }  

        });  

      })

     $("#store_name").keyup(function(){

         $.ajax({  

            type:"GET",  

            url:'<?php echo urlMobile('store_joinin_c2c', 'checkname');?>',  

            async:false,  

            data:{store_name: $('#store_name').val()},  

            success: function(data){  

                if(data == 'true') {

                    layer.open({content:'店铺名称已经存在',time:1.5});

                    result = false;

                }

            }  

        });  

      })

         $("#next_company_info").click(function(){

        var data={

       'seller_name':$("#seller_name").val(),

       'store_name':$("#store_name").val(),

       'joinin_year':$(".stroe_time").find('span.current').attr('value'),

       'sg_id':$(".stroe_lever").find('span.current').attr('value'),

       'sc_id':$(".stroe_cate").find('span.current').attr('value'),

       'store_class_ids':'',

       'store_class_names':'',

      };

   

        if(data['seller_name']==''){

          layer.open({content:'商家账号不得为空!'});

       }

        if(data['store_name']==''){

          layer.open({content:'店铺名称不得为空!'});

       }

        if(data['joinin_year']==undefined){

          layer.open({content:'请选择入驻时间!'});

       }

      

        if(data['sg_id']==undefined){

          layer.open({content:'请选择店铺等级!'});

       }

      

        if(data['sc_id']==undefined){

          layer.open({content:'请选择店铺分类!'});

       }

        $('#gcategory span').each(function() {

        

            if ($(this).hasClass('current')) {

                data['store_class_ids']+= ','+$(this).attr("value");

                data['store_class_names']+=','+$(this).text();

            }

        });

         if(data['store_class_ids']==''){

          layer.open({content:'请选择你经验的类目!'});

       }

      console.log(data);

       $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=store_joinin_c2c&fun=savestep1",

        data: data,

        dataType: "json",

        async: false,

        success: function(e) {

            if (e.code == 200) {

               setTimeout(function () {

                    window.location.href = e.datas.url;   

                }, 1000); 

            } else {

         

                   layer.open({

                    content:e.datas.error,

                    time:1.5

                 })

              





               

            }

        }

      });

      

    })  

   



        

 

  })

</script>