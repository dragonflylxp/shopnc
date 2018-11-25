<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="floor-layout style-<?php echo $output['style_name'];?>">
  <div class="floor-left wrapper">
    <div class="title">
      	<?php if ($output['code_tit']['code_info']['type'] == 'txt') { ?>
      	    <div class="txt-type">
                        <?php if(!empty($output['code_tit']['code_info']['floor'])) { ?><span><?php echo $output['code_tit']['code_info']['floor'];?></span><?php } ?>
                        <h2 title="<?php echo $output['code_tit']['code_info']['title'];?>"><?php echo $output['code_tit']['code_info']['title'];?></h2>
            </div>
      	<?php }else { ?>
      	<div class="pic-type"><img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>"/></div>
      	<?php } ?>
    </div>
    <div class="recommend-classes">
      <ul>
                  <?php if (is_array($output['code_category_list']['code_info']['goods_class']) && !empty($output['code_category_list']['code_info']['goods_class'])) { ?>
		                  <?php foreach ($output['code_category_list']['code_info']['goods_class'] as $k => $v) { ?>
          <li><a href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>" title="<?php echo $v['gc_name'];?>" target="_blank"><?php echo $v['gc_name'];?></a></li>
		                  <?php } ?>
                  <?php } ?>
      </ul>
    </div>
    <div class="right-side-focus">
      <ul>
                  <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
                  <?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
                      <?php if (is_array($val) && !empty($val)) { ?>
                      <li><a href="<?php echo $val['pic_url'];?>" title="<?php echo $val['pic_name'];?>" target="_blank">
                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>" alt="<?php echo $val['pic_name'];?>"/></a>
                      	</li>
                      <?php } ?>
                  <?php } ?>
                  <?php } ?>
      </ul>
    </div>
  </div>
  <div class="floor-right">
    <div class="title"><ul>
                  <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
                    $i = 0;
                    ?>
                  <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
                    $i++;
                    ?>
        <li class="tab-item <?php echo $i==1 ? 'tabs-selected':'';?>"><a href="javascript:;"><?php echo $val['recommend']['name'];?></a></li>
                  <?php } ?>
                  <?php } ?>
    </ul></div>
                  <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
                    $i = 0;
                    ?>
                  <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
                    $i++;
                    ?>
                          <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
                                  <div class="floor-style03 tabs" <?php if ($i > 1) { ?>style="display: none;"<?php } ?>>
                                    <div class="goods"><ul>
                                    <?php foreach($val['goods_list'] as $k => $v){ ?>
                                      <li>
                                        <dl>
                                          <dt class="goods-name"><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>" title="<?php echo $v['goods_name']; ?>">
                                          	<?php echo $v['goods_name']; ?></a></dt>
                                          <dd class="goods-thumb">
                                          	<a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>">
                                          	<img src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" alt="<?php echo $v['goods_name']; ?>" />
                                          	</a></dd>
                                          <dd class="goods-price"><em><?php echo ncPriceFormatForList($v['goods_price']); ?></em>
                                            <span class="original"><?php echo ncPriceFormatForList($v['market_price']); ?></span></dd>
                                        </dl>
                                      </li>
                                    <?php } ?>
                                    </ul></div>
                                  </div>
                          <?php } elseif (!empty($val['pic_list2']) && is_array($val['pic_list2'])) { ?>
                                <div class="floor-style02 tabs" <?php if ($i > 1) { ?>style="display: none;"<?php } ?>>
                                    <div class="img-goods"><ul>
                                    <li class="li01"><a href="<?php echo $val['pic_list2']['11']['pic_url'];?>" title="<?php echo $val['pic_list2']['11']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['11']['pic_img'];?>" alt="<?php echo $val['pic_list2']['11']['pic_name'];?>"></a></li>
                                    <li class="li01"><a href="<?php echo $val['pic_list2']['31']['pic_url'];?>" title="<?php echo $val['pic_list2']['31']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['31']['pic_img'];?>" alt="<?php echo $val['pic_list2']['31']['pic_name'];?>"></a></li>
                                    <li class="li02"><a href="<?php echo $val['pic_list2']['21']['pic_url'];?>" title="<?php echo $val['pic_list2']['21']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['21']['pic_img'];?>" alt="<?php echo $val['pic_list2']['21']['pic_name'];?>"></a></li>
                                    <li class="li01"><a href="<?php echo $val['pic_list2']['12']['pic_url'];?>" title="<?php echo $val['pic_list2']['12']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['12']['pic_img'];?>" alt="<?php echo $val['pic_list2']['12']['pic_name'];?>"></a></li>
                                    <li class="li01"><a href="<?php echo $val['pic_list2']['32']['pic_url'];?>" title="<?php echo $val['pic_list2']['32']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['32']['pic_img'];?>" alt="<?php echo $val['pic_list2']['32']['pic_name'];?>"></a></li>
                                        <?php if (!empty($val['pic_list2']['33']) || !empty($val['pic_list2']['34'])) { ?>
                                    <li class="li01"><a href="<?php echo $val['pic_list2']['33']['pic_url'];?>" title="<?php echo $val['pic_list2']['33']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['33']['pic_img'];?>" alt="<?php echo $val['pic_list2']['33']['pic_name'];?>"></a></li>
                                    <li class="li01"><a href="<?php echo $val['pic_list2']['34']['pic_url'];?>" title="<?php echo $val['pic_list2']['34']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['34']['pic_img'];?>" alt="<?php echo $val['pic_list2']['34']['pic_name'];?>"></a></li>
                                    <li class="li01"><a href="<?php echo $val['pic_list2']['24']['pic_url'];?>" title="<?php echo $val['pic_list2']['24']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['24']['pic_img'];?>" alt="<?php echo $val['pic_list2']['24']['pic_name'];?>"></a></li>
                                        <?php }else { ?>
                                    <li class="li02"><a href="<?php echo $val['pic_list2']['24']['pic_url'];?>" title="<?php echo $val['pic_list2']['24']['pic_name'];?>" target="_blank">
                                        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['24']['pic_img'];?>" alt="<?php echo $val['pic_list2']['24']['pic_name'];?>"></a></li>
                                        <?php } ?>
                                    </ul></div>
                                    <div class="banner-goods">
                                        <div class="right-side-focus">
                                            <ul>
                                            <?php foreach($val['pic_list2'] as $k => $v) { ?>
                                            <?php if (($k>50 && $k<60) && !empty($v['pic_img'])) { ?>
                                            <li><a href="<?php echo $v['pic_url'];?>" title="<?php echo $v['pic_name'];?>" target="_blank">
                                                <img src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>" alt="<?php echo $v['pic_name'];?>"></a></li>
                                            <?php } ?>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                    <?php if (empty($val['pic_list2']['33']) && empty($val['pic_list2']['34'])) { ?>
                                        <div class="right-side-focus">
                                            <ul>
                                            <?php foreach($val['pic_list2'] as $k => $v) { ?>
                                            <?php if (($k>60 && $k<70) && !empty($v['pic_img'])) { ?>
                                            <li><a href="<?php echo $v['pic_url'];?>" title="<?php echo $v['pic_name'];?>" target="_blank">
                                                <img src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>" alt="<?php echo $v['pic_name'];?>"></a></li>
                                            <?php } ?>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    </div>  
                                </div>
                          <?php } ?>
                  <?php } ?>
                  <?php } ?>
  </div>
    <div class="brands">
      <ul>
                  <?php if (!empty($output['code_brand_list']['code_info']) && is_array($output['code_brand_list']['code_info'])) { ?>
                  <?php foreach ($output['code_brand_list']['code_info'] as $key => $val) { ?>
        <li>
          <a href="<?php echo urlShop('brand', 'list', array('brand'=> $val['brand_id'])); ?>" title="<?php echo $val['brand_name']; ?>" target="_blank">
          	<img src="<?php echo UPLOAD_SITE_URL.'/'.$val['brand_pic'];?>" alt="<?php echo $val['brand_name']; ?>"></a>
        </li>
                  <?php } ?>
                  <?php } ?>
      </ul>
    </div>
</div>