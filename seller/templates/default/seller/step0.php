<header id="header" class="fixed">
  <div class="header-wrap">
    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
   <div class="header-title">
      <h1>入驻申请已经提交，请等待管理员审核</h1>
    </div>
   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
   </div>
       <?php include template('layout/seller_toptip');?>


</header>
<div class="nctouch-main-layout fixed-Width">
<div class="alert">
    <h4>注意事项：</h4>
    入驻申请已经提交，请等待管理员审核
</div>
<div class="nctouch-home-block">
  <div class="tit-bar"><i style="background:#EC5464;"></i>付款清单列表</div>
  <div class="input_box">
    <dl class="border_bottom">
        <dt>收费标准</dt>
        <dd><?php echo $output['joinin_detail']['sg_info']['sg_price'];?>元/年 ( <?php echo $output['joinin_detail']['sg_name'];?> )</dd>
    </dl>
     <dl class="border_bottom">
        <dt>开店时长</dt>
        <dd>
           <?php echo $output['joinin_detail']['joinin_year'];?> 年
        </dd>
    </dl>
   <dl class="border_bottom">
        <dt>店铺分类</dt>
        <dd>
          <?php echo $output['joinin_detail']['sc_name'];?>
        </dd>
    </dl>
    <dl class="border_bottom">
        <dt>开店保证金</dt>
        <dd>
           <?php echo $output['joinin_detail']['sc_bail'];?> 元
        </dd>
    </dl>
   <dl class="border_bottom">
        <dt>应付金额</dt>
        <dd>
           <?php echo $output['joinin_detail']['paying_amount'];?> 元
        </dd>
    </dl>
    <div class="input_box">
          <table id="table_category" class="type" border="0" cellpadding="0" cellspacing="0">
                <thead>
                  <tr>
                    <th class="w120 tc">一级类目</th>
                  
                    <th class="w50 tc">分佣比例</th>
                  </tr>
                </thead>
              <tbody>
              <tr class="store-class-item store-class-items">
                    <?php foreach ($output['joinin_detail']['store_class_names'] as $k => $name) {?>
                  <?php $name = explode(',', $name);?>
                  <tr>
                    <td><?php echo $name[0];?></td>
                    
                    <td><?php echo $output['joinin_detail']['store_class_commis_rates'][$k]; ?> %</td>
                  </tr>
                  <?php } ?>
             
              </tr>
              </tbody>
              </table>
        
    </div>
   
  </div>
</div>





</div>

<div class="fix-block-r">
  <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 