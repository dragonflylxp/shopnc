<!--添加的店铺信息部分-->
<style type="text/css">
.copy-tips{position:fixed;z-index:999;bottom:50%;left:50%;margin:0 0 -20px -80px;background-color:rgba(0, 0, 0, 0.2);filter:progid:DXImageTransform.Microsoft.Gradient(startColorstr=#30000000, endColorstr=#30000000);padding:6px;}
.copy-tips-wrap{padding:10px 20px;text-align:center;border:1px solid #F4D9A6;background-color:#FFFDEE;font-size:14px;}
</style>
<div class="d_index">
    <div class="detail-shop-info">
        <img src="<?php echo getStoreLogo($output['store_info']['store_label'],'store_logo');?>" class="fl"/>
        <ul class="fl">
            <li>
                <span>公司名称：<?php echo $output['store_info']['store_company_name'];?></span> <span>地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;址：<?php echo $output['store_info']['area_info'].' '.$output['store_info']['store_address'];?></span>
                <span>电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话：0571-85801222</span>
            </li>
            <li>
                <span>店铺名称：<b><?php echo $output['store_info']['store_name'];?></b></span> <span>店铺主营：<?php echo $output['store_info']['store_zy'];?></span>
            </li>
            <li>
                <span>宝贝与描述相符：&nbsp;<b><?php echo $output['store_info']['store_credit']['store_desccredit']['credit'];?></b>&nbsp;分</span> <span>卖家的服务态度：&nbsp;<b><?php echo $output['store_info']['store_credit']['store_servicecredit']['credit'];?></b>&nbsp;分</span> <span>物流服务的质量：&nbsp;<b><?php echo $output['store_info']['store_credit']['store_deliverycredit']['credit'];?></b>&nbsp;分</span>
            </li>
        </ul>
        <span class="fl link_im" member_id="<?php echo $output['store_info']['member_id'];?>">联系IM客服</span>
        <div class="clear"></div>
    </div>
    <div class="detail-shop-data">
        <div class="fl">
            <h1>近30天佣金历史趋势</h1>
            <div class="data-number">
                <span>30天推广量</span> <span><b class="d_index_orange" id="myTargetElement-promote"><?php echo $output['distri_count']?></b>&nbsp;件</span>
            </div>
            <div class="data-number">
                <span>30天支付佣金</span> <span><b id="myTargetElement-commission"><?php echo $output['bill_count']?></b>&nbsp;元</span>
            </div>

        </div>
        <div class="chat fr">
            <div class="chat-nav">
                <a href="javascript:void(0);" class="commission-btn" title="支付佣金"><i class="fa fa-cny" aria-hidden="true"></i>&nbsp;支付佣金</a>
                <a href="javascript:void(0);" class="promote-btn" title="推广量"><i class="fa fa-line-chart" aria-hidden="true"></i>&nbsp;推广量</a>
            </div>
            <canvas class="commission" id="commission" width="930" height="230"></canvas>
            <canvas class="promote" id="promote" width="930" height="230"></canvas>
        </div>

    </div>
</div><!--结束-->
<div class="clear"></div>
<div class="d_index">
    <h1 class="p-goods">
        <img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_index_05.png" alt="" class="fl" height="19" width="19"><span class="fl">&nbsp;&nbsp;推广商品</span>
    </h1>
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
    <ul class="d_list">
    <?php foreach($output['goods_list'] as $value){?>
        <li>
            <a href="<?php echo urlShop('distri_goods','goods_detail',array('goods_id'=> $value['goods_commonid']));?>">
                <img src="<?php echo cthumb($value['goods_image'], 240,$value['store_id']);?>" alt="<?php echo $value['goods_name'];?>" class="d_list_goods_img"/>
                <div class="d_list_goods_name">
                    <span><?php echo $value['goods_name'];?></span>
                    <div>
                        <span class="fl"><b><?php echo ncPriceFormatForList($value['goods_price']);?></b></span><span class="fr d_index_gray9">销量：<i class="d_index_gray6"><?php echo $value['sale_count'];?></i></span>
                    </div>
                    <div>
                        <span class="fl d_index_orange">佣金比例：<b><?php echo $value['dis_commis_rate'];?>%</b></span><span class="fr d_index_gray9">佣金：<i class="d_index_gray6"><?php echo ncPriceFormatForList($value['goods_price']*$value['dis_commis_rate']*1.0/100);?></i></span>
                    </div>
                </div>
            </a>
            <div class="add-cart checkbox">
                <a href="javascript:void(0);" nctype="" data-gid="<?php echo $value['goods_commonid']?>" class="cd-popup-trigger1"><i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;立即推广</a>
                <a href="javascript:void(0);" nctype="" data-gid="<?php echo $value['goods_commonid']?>" class="cd-popup-trigger2"><i class="fa fa-qrcode" aria-hidden="true"></i>&nbsp;获取二维码</a>
            </div>
        </li>
    <?php }?>    
    </ul>
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
                    <input type="text" id="link1" value="">
                    <button class="clip_button">复制链接</button>
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


<div class="tc mt20 mb20">
    <div class="pagination"> <?php echo $output['show_page']; ?> </div>
</div>

<script src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL; ?>/js/countUp.min.js"></script>
<script src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL; ?>/js/Chart.min.js"></script>

<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL; ?>/js/jquery.qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL; ?>/js/qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo DISTRIBUTE_RESOURCE_SITE_URL;?>/js/zclip/ZeroClipboard.min.js" charset="utf-8"></script>
<script type="text/javascript">
    /*弹框JS内容*/
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
<script type="text/javascript">
    var options = {
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.'
    };
    var demo_promote = new CountUp("myTargetElement-promote", 0, <?php echo $output['distri_count']?>, 0, 2, options);
    var demo_commission = new CountUp("myTargetElement-commission", 0, <?php echo $output['bill_count']?>, 0, 2.5, options);
    $(window).load(function () {
        demo_promote.start();
        demo_commission.start();
    })
</script>
<script>
    $(function () {
        //推广量统计
        var commission_data = <?php echo $output['distri_info']?>;
        var commission_opt ={
            scaleOverride : true,
            scaleSteps : 5,
            scaleStepWidth : <?php echo $output['distri_opt']?>,
            scaleStartValue : 0
          };
        new Chart(document.getElementById("commission").getContext("2d")).Line(commission_data,commission_opt);

        //佣金支付统计
        var promote_data = <?php echo $output['bill_info']?>;
        var promote_opt ={
            scaleOverride : true,
            scaleSteps : 5,
            scaleStepWidth : <?php echo $output['bill_opt']?>,
            scaleStartValue : 0
          };
        new Chart(document.getElementById("promote").getContext("2d")).Line(promote_data,promote_opt);

        
        $(".promote-btn").click(function () {
            $(".promote").fadeOut(400);
            $(".commission").fadeIn(400);
        })
        $(".commission-btn").click(function () {
            $(".commission").fadeOut(400);
            $(".promote").fadeIn(400);
        })
    })
</script>

        