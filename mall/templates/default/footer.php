<?php defined('Inshopec') or exit('Access Invalid!');?>
<?php echo getChat($layout);?>

<div id="cti">
  <div class="wrapper">
    <ul>
      <?php if ($output['contract_list']) {?>
      <?php foreach($output['contract_list'] as $k=>$v){?>
        <?php if($v['cti_descurl']){ ?>
            <li><span class="line"></span><a href="<?php echo $v['cti_descurl'];?>" target="_blank"><span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span></a></li>
        <?php }else{ ?>
            <li><span class="line"></span> <span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span> </li>
        <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</div>
<div id="faq">
  <div class="wrapper">
    <?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?>
    <ul>
      <?php foreach ($output['article_list'] as $k=> $article_class){ ?>
      <?php if(!empty($article_class)){ ?>
      <li>
        <dl class="s<?php echo ''.$k+1;?>">
          <dt>
            <?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?>
          </dt>
          <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
          <?php foreach ($article_class['list'] as $article){ ?>
          <dd><i></i><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlMember('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a></dd>
          <?php }?>
          <?php }?>
        </dl>
      </li>
      <?php }?>
      <?php }?>
    </ul>
    <?php }?>
  </div>
</div>
<div id="footer" class="wrapper">
  <p><a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlMember('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p><?php echo $output['setting_config']['icp_number']; ?><br />
  <?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?> </div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div> <?php print_r(\shopec\Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<!-- 对比 --> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/compare.js"></script> 
<script type="text/javascript">
$(function(){
	// Membership card
	$('[nctype="mcard"]').membershipCard({type:'shop'});
});
</script>