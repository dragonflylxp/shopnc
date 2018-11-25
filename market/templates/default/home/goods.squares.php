<link href="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<?php defined('Inshopec') or exit('Access Invalid!');?>
<style type="text/css">
#box { background: #FFF; width: 238px; height: 410px; margin: -390px 0 0 0; display: block; border: solid 4px #D93600; position: absolute; z-index: 999; opacity: .5 }
.shopMenu { position: fixed; z-index: 1; right: 25%; top: 0; }
.d_index{ margin-top:10px}
.copy-tips{position:fixed;z-index:999;bottom:50%;left:50%;margin:0 0 -20px -80px;background-color:rgba(0, 0, 0, 0.2);filter:progid:DXImageTransform.Microsoft.Gradient(startColorstr=#30000000, endColorstr=#30000000);padding:6px;}
.copy-tips-wrap{padding:10px 20px;text-align:center;border:1px solid #F4D9A6;background-color:#FFFDEE;font-size:14px;}
</style>
<div class="clear"></div>
<div class="d_index">
<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
	<ul class="d_list">
    <?php foreach($output['goods_list'] as $value){?>
    	<li>
        	<a href="<?php echo urlShop('distri_goods','goods_detail',array('goods_id'=> $value['goods_commonid']));?>">
   	    	<img src="<?php echo cthumb($value['goods_image'], 240,$value['store_id']);?>" alt="<?php echo $value['goods_name'];?>"/>
            <div class="d_list_goods_name">
            	<span><?php echo $value['goods_name'];?></span>
                <div><span class="fl"><b><?php echo ncPriceFormatForList($value['goods_price']);?></b></span><span class="fr d_index_gray9">销量：<i class="d_index_gray6"><?php echo $value['sale_count'];?></i></span></div>
                <div><span class="fl d_index_orange">佣金比例：<b><?php echo $value['dis_commis_rate'];?>%</b></span><span class="fr d_index_gray9">佣金：<i class="d_index_gray6"><?php echo ncPriceFormatForList($value['goods_price']*$value['dis_commis_rate']*1.0/100);?></i></span></div>
            </div>
            </a>
            <div class="d_list_shop_name">
            	<a href="index.php?con=store&store_id=<?php echo $value['store_id'];?>"><img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_list_shop_name.png" class="fl" alt="<?php echo $value['store_name'];?>"/><span class="fl"><?php echo $value['store_name'];?></span></a>
            </div>
            <div class="add-cart checkbox">
              <a href="javascript:void(0);" nctype="" data-gid="<?php echo $value['goods_commonid']?>" class="cd-popup-trigger1"><i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;立即推广</a>
              <a href="javascript:void(0);" nctype="" data-gid="<?php echo $value['goods_commonid']?>" class="cd-popup-trigger2"><i class="fa fa-qrcode" aria-hidden="true"></i>&nbsp;获取二维码</a>
            </div>
        </li>
        <?php }?>      
    </ul>
<?php }else{?>
  <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record'];?></div>
  <?php }?>    
</div>
<div class="clear"></div>

<!-- 立即推广-->
<div class="cd-popup1 cd-popup-box">
	<div class="cd-popup-container1">
        <div id="distri_info"></div>
        <div class="clear"></div>
        <div class="share">
            <div class="sharebord">
                <div class="item">
                    <input type="text" id="link1" value=""><button class="clip_button">复制链接</button>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="bdsharebuttonbox"> 
        	<a href="javascript:void(0)">分享至：</a>
            <a href="javascript:void(0)" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
            <a href="javascript:void(0)" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
            <a href="javascript:void(0)" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
            <a href="javascript:void(0)" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a>
        </div>
        <a href="#0" class="cd-popup-close"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;关闭</a>
    </div>
</div>

<!-- 获取二维码-->
<div class="cd-popup2 cd-popup-box">
   <div class="cd-popup-container2">
       <img src="<?php echo DISTRIBUTE_TEMPLATES_URL?>/images/share-qrcode.jpg" class="share-qrcode"/>
       <div id="distri_info2"></div>
        <div class="clear"></div>
        <div class="qrcodeTable">
        <div id="qrcodeTable" class="fl"></div> 
        <h4 class="fl w-45">打开微信扫描二维码后可将商品分享至微信好友及朋友圈</h4>
        </div>
        <a href="#0" class="cd-popup-close"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;关闭</a>
    </div>
</div>

<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/jquery.qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/zclip/ZeroClipboard.min.js" charset="utf-8"></script>

<!--弹出js-->
<script type="text/javascript">
    $(function(){
        //打开窗口
        $('.cd-popup-trigger1').on('click', function(event){
            var g_id = $(this).attr('data-gid');
            $.ajax({
                type:'get',
                url:'index.php?con=search&fun=distri_add&id='+g_id,
                dataType:"json",
                success:function(res){
                    if(res.stat == 'succ'){
                        var datas = res.data;
                        var html = '<img src="'+datas.goods_image+'" class="fl"/>';
                        html += '<h4>'+datas.goods_name+'<b>&yen;'+datas.goods_price+'</b></h4>';
                        $('#distri_info').html(html);
                        $('#link1').val("<?php echo urlShop('distri_goods','index')?>&goods_id="+datas.distri_id);
                        window._bd_share_config={
                            "common":{
                                "bdText":datas.goods_name,
                                "bdPic":datas.goods_image,
                                "bdUrl":"<?php echo urlShop('distri_goods','index')?>&goods_id="+datas.distri_id
                            },
                            "share":{
                                "bdSize":"24"
                            },
                            "selectShare":{
                                "bdSelectMiniList":["tsina","qzone","weixin","tqq","sqq"]
                            }
                        };
                        with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
                        event.preventDefault();
                        $('.cd-popup1').addClass('is-visible1');
                    }else{
                        //alert(res.msg);
                        showDialog(res.msg, 'alert', '', function(){ 
                            if(res.url != ''){
                                window.location = res.url;
                            }                            
                        });
                    }
                },
                error:function(error){
                    showError('操作失败');
                }
            });
        });
        //关闭窗口
        $('.cd-popup1').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup1') ) {
                event.preventDefault();
                $(this).removeClass('is-visible1');
            }
        });
        //ESC关闭
        $(document).keyup(function(event){
            if(event.which=='27'){
                $('.cd-popup1').removeClass('is-visible1');
            }
        });
        
        //打开窗口
        $('.cd-popup-trigger2').on('click', function(event){
            var g_id = $(this).attr('data-gid');
            $.ajax({
                type:'get',
                url:'index.php?con=search&fun=distri_add&id='+g_id,
                dataType:"json",
                success:function(res){
                    if(res.stat == 'succ'){
                        var datas = res.data;
                        var html = '<img src="'+datas.goods_image+'" class="fl"/>';
                        html += '<h4 class="w-55">'+datas.goods_name+'<b>&yen;'+datas.goods_price+'</b></h4>';
                        $('#distri_info2').html(html);
                        $('#qrcodeTable').html('');
                        var dis_uri = "<?php echo urlShop('distri_goods','index')?>&goods_id="+datas.distri_id;
                        jQuery('#qrcodeTable').qrcode({render: "canvas",text:dis_uri,width:"124",height:"124"});
                        event.preventDefault();
                        $('.cd-popup2').addClass('is-visible2');
                    }else{
                        //alert(res.msg);
                        showDialog(res.msg, 'alert', '', function(){ 
                            if(res.url != ''){
                                window.location = res.url;
                            }                            
                        });
                    }
                },
                error:function(error){
                    showError('操作失败');
                }
            }); 
        });
        //关闭窗口
        $('.cd-popup2').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup2') ) {
                event.preventDefault();
                $(this).removeClass('is-visible2');
            }
        });
        //ESC关闭
        $(document).keyup(function(event){
            if(event.which=='27'){
                $('.cd-popup2').removeClass('is-visible2');
            }
        });
        //复制
        var client = new ZeroClipboard($('.clip_button'));
        client.on( 'ready', function(event) {

            client.on('copy', function(event) {
              event.clipboardData.setData('text/plain',$('#link1').val());
            });

            client.on('aftercopy', function(event) {
              alert('你已经成功复制本地址，请直接粘贴推荐给你的朋友!');
            });
        });
    });
</script>
<!--弹出js结束-->

