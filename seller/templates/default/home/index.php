<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>



<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/index.css">

<style type="text/css">

#footer{

	max-width: 640px;

	margin:0 auto;

	margin-bottom: 60px;



}

.fix-block-r{

	bottom: 3rem;

}

.filter-menu{

	display: none;

}

</style>

</head>

<body>



<?php require_once template('layout/fiexd');?>

<?php if($output['zom_list'][0]['site_status']==1){?>

<div class="newuser-divmask" style="display: none;"></div>

<div class="white"  style="display: none;">

<div id="zommbox" class="bannerBox2" >

            <div class="bd">

                <ul>                  

                        <?php foreach($output[ 'zom_list'][0]['code_info'] as $vai){?>

                        <li style="display: table-cell; vertical-align: top; max-width: 640px;">

                            <a href="<?php echo $vai['pic_url'];?>" target="_blank">        

                                <img src="<?php echo UPLOAD_SITE_URL.'/'.$vai['pic_img'];?>" alt="<?php echo $vai['pic_name'];?>"/>

                            </a>

                        </li>

                        <?php } ?>

                                                 

                    </ul>

                </div>



          

            <div class="hd">

                <ul>

      			<?php $cv == 0;foreach($output['zom_list'][0]['code_info'] as $cinfo){$cv++;?>

                    <li class=""><?php echo  $cv;?></li>

                <?php }?>



                </ul>

            </div>

            

            

        </div>

        <i class="close" onclick="closezz();"></i>

    </div>





<?php } ?>

<div class="nctouch-home-top fixed-Width">

  <header id="header" class="transparent">

  	

  		<div class="logo" id="logo">

  			<a href="<?php echo urlMobile('');?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.DS.$output['setting_config']['mobile_logo']; ?>"/></a>
  		</div>
    <div class="header-wrap">

      <a href="<?php echo urlMobile('goods','search');?>" class="header-inp"> <i class="icon"></i> <span class="search-input" id="keyword">快捷搜索</span> </a>

    </div>

    <div class="header-r"><a id="header-nav" href="<?php echo urlMobile('member_chat','chat_list');?>"><i class="message"></i>

      <p>消息</p>

      <sup></sup></a></div>

  </header>

  <div class="cohesive"></div>



  <div class="adv_list" id="main-container1"></div>



</div>



  <script type="text/html" id="nav">

  <div class="nctouch-home-nav fixed-Width">

  <ul>

	<% for (var i in item) { %>

		  <li>

			  <a href="<%= item[i].image_url %>"><span><i style="background:<%= item[i].image_color %> url(<%= item[i].image_name %>) no-repeat 50% 50%;background-size:50% 50%;border-radius: 100%;"></i></span>

		      <p><%= item[i].image_title %></p>

		      </a>

	      </li>

	<% } %>

 </ul>

 </div>



 

</script> 

  <script type="text/html" id="home13">
	<dl class="notice hot-news-roller border_bottom fixed-Width ">
	<dt>
	<a href="<%= url %>">
		<img src="<%= image %>" height="40" width="65">
	</a>
	</dt>
	<dd class="notice-roller">
	<ul style="margin-top: 0px;">
	<% if (title) { %>
	  <li><a  style="color:<%= title20 %>;font-size:15px;" href="<%= title6 %>"><%= title %></a></li>
	  <% } %>
	  <% if (title1) { %>
	  <li><a style="color:<%= title21 %>;font-size:15px;" href="<%= title7 %>"><%= title1 %></a></li>
	  <% } %>
	  <% if (title2) { %>
	  <li><a style="color:<%= title22 %>;font-size:15px;" href="<%= title8 %>"><%= title2 %></a></li>
	  <% } %>
	  <% if (title3) { %>
	  <li><a style="color:<%= title23 %>;font-size:15px;" href="<%= title9 %>"><%= title3 %></a></li>
	  <% } %>
	  <% if (title4) { %>
	  <li><a style="color:<%= title24 %>;font-size:15px;" href="<%= title10 %>"><%= title4 %></a></li>
	  <% } %>
	  	  <% if (title5) { %>
	  <li><a style="color:<%= title25 %>;font-size:15px;" href="<%= title11 %>"><%= title5 %></a></li>
	  <% } %>



	</ul>
	</dd>
	</dl> 
</script>


<div class="nctouch-home-layout" id="main-container2">

	

</div>



<div class="guessulike">

<h3><i style="background: #f36713;"></i>猜你喜欢</h3>

<div class="pj_box">

<ul style="width: 1800px;">

	<?php if(!empty($output['tuijian']) && is_array($output['tuijian'])){?>

	<?php foreach($output['tuijian'] as $tj){?>

	<li>

		<a href="<?php echo urlMobile('goods','detail',array('goods_id'=>$tj['goods_id']));?>">

			<img src="<?php echo thumb($tj, 80);?>" alt="img" height="80" width="80">

			<em><?php echo $tj['goods_name'];?></em><b>￥<?php echo ncPriceFormatForListsmall($tj['goods_price']);?></b>

		</a>

	</li>

	<?php } ?>

	<?php } ?>

</ul>

</div>

</div>





 



<dl class="notice fixed-Width border_bottom">

<dt>

<img src="<?php echo MOBILE_TEMPLATES_URL;?>/images/notice.png" height="20" width="20">公告</dt>

<dd class="notice-roller">

<ul style="margin-top: 0px;">

	<?php if(!empty($output['notice']) && is_array($output['notice'])){?>

	<?php foreach($output['notice'] as $alist){?>

	<li><a href="<?php echo urlMobile('article','show',array('article_id'=>$alist['article_id']));?>"><?php echo $alist['article_title'];?></a></li>

	<?php }?>

	<?php }?>

</ul>

</dd>



<dd style="float: right; width: 30px; margin: -40px 0 0 0;">

<a href="<?php echo urlMobile('article','index',array('cid'=>1));?>">更多</a>

</dd>

</dl>



<div class="foot-nav border_top " style="display: block; transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);">

<a class="cur" href="/"><i class="home"></i><span>首页</span></a>

<a href="<?php echo urlMOBILE('goods_class');?>"><i class="cate"></i><span>商品分类</span></a>

<a href="<?php echo urlMOBILE('cart');?>"><i class="cart"></i><span>购物车</span></a>

<a href="<?php echo urlMOBILE('member');?>"><i class="user"></i><span>我的</span></a>

</div>



<script type="text/html" id="adv_list">

		<div class="swipe-wrap">

		<% for (var i in item) { %>

			<div class="item">

				<a href="<%= item[i].url %>">

					<img src="<%= item[i].image %>" alt="">

				</a>

			</div>

		<% } %>



		</div>

		

</script> 

<script type="text/html" id="home1">
	<div class="nctouch-home-block mt5">
	<% if (title) { %>
		<div class="tit-bar">
			<i style="background:<%=color%>;"></i><%= title %>
			<% if (more_url) { %>
				<a href="<%=more_url%>">更多</a>
			<% } %>
		</div>
	<% } %>
		<div class="item-pic">
			<a href="<%= url %>">
				<img src="<%= image %>" alt="">
			</a>
		</div>
	</div>
</script> 

<script type="text/html" id="home2">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<ul class="item-pic-l1-r2">

			<li>

				<a href="<%= square_url %>"><img src="<%= square_image %>" alt=""></a>

			</li>

			<li>

				<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

			</li>

			<li>

				<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

			</li>

		</ul>

	</div>

</script> 



<script type="text/html" id="home3">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<ul class="item-pic-list">

		<% for (var i in item) { %>

			<li>

				<a href="<%= item[i].url %>"><img src="<%= item[i].image %>" alt=""></a>

			</li>

		<% } %>

		</ul>

	</div>

</script> 



<script type="text/html" id="home4">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<ul class="item-pic-l2-r1">

			<li>

				<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

			</li>

			<li>

				<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

			</li>

			<li>

				<a href="<%= square_url %>"><img src="<%= square_image %>" alt=""></a>

			</li>

		</ul>

	</div>

</script> 

<script type="text/html" id="home5">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<div class="item-pic-list">

			<div class="left_pic_box">

				<a href="<%= square_url %>"><img src="<%= square_image %>" alt=""></a>

			</div>

			<div class="right_pic_box">

				<div class="pic_small_box">

					<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

				</div>

				<div class="pic_small_box">

					<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

				</div>

				<div class="pic_small_box">

					<a href="<%= rectangle3_url %>"><img src="<%= rectangle3_image %>" alt=""></a>

				</div>

				<div class="pic_small_box">

					<a href="<%= rectangle4_url %>"><img src="<%= rectangle4_image %>" alt=""></a>

				</div>

			</div>

			

			

		</div>

	</div>

</script> 

<script type="text/html" id="home6">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<div class="item-pic-list">

			

			<div class="right_pic_box right_pic_box1">

				<div class="pic_small_box">

					<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

				</div>

				<div class="pic_small_box">

					<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

				</div>

				<div class="pic_small_box">

					<a href="<%= rectangle3_url %>"><img src="<%= rectangle3_image %>" alt=""></a>

				</div>

				<div class="pic_small_box">

					<a href="<%= rectangle4_url %>"><img src="<%= rectangle4_image %>" alt=""></a>

				</div>

			</div>

			<div class="left_pic_box left_pic_box1">

				<a href="<%= square_url %>"><img src="<%= square_image %>" alt=""></a>

			</div>

			

		</div>

	</div>

</script> 



<script type="text/html" id="home7">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<div class="item-pic-list">

			

			<div class="pic_box_home">

				<div class="pic_box_small_box">

					<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

				</div>

				<div class="pic_box_small_box">

					<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

				</div>

				<div class="pic_box_small_box">

					<a href="<%= rectangle3_url %>"><img src="<%= rectangle3_image %>" alt=""></a>

				</div>

			</div>

			

			

		</div>

	</div>

</script>





<script type="text/html" id="home8">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<div class="item-pic-list">

			<div class="pic_box_home pic_box_home1">

				<div class="pic_box_one">

					<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle3_url %>"><img src="<%= rectangle3_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle4_url %>"><img src="<%= rectangle4_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle5_url %>"><img src="<%= rectangle5_image %>" alt=""></a>

				</div>

			</div>

		</div>

	</div>

</script>

<script type="text/html" id="home9">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<div class="item-pic-list">

			<div class="pic_box_home pic_box_home2">

				

				<div class="pic_box_two">

					<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

				</div>

				<div class="pic_box_one">

					<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle3_url %>"><img src="<%= rectangle3_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle4_url %>"><img src="<%= rectangle4_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle5_url %>"><img src="<%= rectangle5_image %>" alt=""></a>

				</div>

				<div class="pic_box_two">

					<a href="<%= rectangle6_url %>"><img src="<%= rectangle6_image %>" alt=""></a>

				</div>

				

			</div>

		</div>

	</div>

</script>



<script type="text/html" id="home10">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		

			<div class="item-pic-list">

			

			<div class="pic_box_home_right pic_box_home_right1">

				<div class="pic_box_home_right_one">

					<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

				</div>

				<div class="pic_box_home_right_two">

					<a href="<%= rectangle3_url %>"><img src="<%= rectangle3_image %>" alt=""></a>

				</div>

				<div class="pic_box_home_right_two">

					<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

				</div>

			</div>

			<div class="pic_box_home_left pic_box_home_left1">

				

					<a href="<%= rectangle4_url %>"><img src="<%= rectangle4_image %>" alt=""></a>

				

			</div>

		</div>

	</div>

</script>

<script type="text/html" id="home11">

	<div class="nctouch-home-block mt5">

	<% if (title) { %>

		<div class="tit-bar">

		<i style="background:<%=color%>;"></i>

		<%= title %>

		<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

		<% } %>

		</div>

	<% } %>

		<div class="item-pic-list">

			<div class="pic_box_home_left">

				

					<a href="<%= rectangle4_url %>"><img src="<%= rectangle4_image %>" alt=""></a>

				

			</div>

			<div class="pic_box_home_right">

				<div class="pic_box_home_right_one">

					<a href="<%= rectangle1_url %>"><img src="<%= rectangle1_image %>" alt=""></a>

				</div>

				<div class="pic_box_home_right_two">

					<a href="<%= rectangle3_url %>"><img src="<%= rectangle3_image %>" alt=""></a>

				</div>

				<div class="pic_box_home_right_two">

					<a href="<%= rectangle2_url %>"><img src="<%= rectangle2_image %>" alt=""></a>

				</div>

			</div>

		</div>

	</div>

</script>
<script type="text/html" id="home12">
	<div class="nctouch-home-block mt5">
	<% if (title) { %>
		<div class="tit-bar">
			<i style="background:<%=color%>;"></i><%= title %>
			<% if (more_url) { %>
				<a href="<%=more_url%>">更多</a>
			<% } %>
		</div>
	<% } %>
		<div class="item-pic">
			<a href="<%= url %>">
				<img src="<%= image %>" alt="">
			</a>
		</div>
	</div>
</script>

<script type="text/html" id="home14">
	<div class="nctouch-home-block mt5">
	<% if (title) { %>
		<div class="tit-bar">
			<i style="background:<%=color%>;"></i><%= title %>
			<% if (more_url) { %>
				<a href="<%=more_url%>">更多</a>
			<% } %>
		</div>
	<% } %>
		<div class="item-pic">
			<a href="<%= url %>">
				<img src="<%= image %>" alt="">
			</a>
		</div>
	</div>
</script> 
<script type="text/html" id="home15">
	<div class="nctouch-home-block mt5">
	<% if (title) { %>
		<div class="tit-bar">
		<i style="background:<%=color%>;"></i>
		<%= title %>
		<% if (more_url) { %>
				<a href="<%=more_url%>">更多</a>
		<% } %>
		</div>
	<% } %>
		<ul class="item-pic-l3-r1">
				<% for (var i in item) { %>
				<li>

					<a href="<%= item[i].url %>"><img src="<%= item[i].image %>" alt=""></a>

				</li>

			<% } %>
		</ul>

	</div>

</script> 
<script type="text/html" id="home16">
	<div class="nctouch-home-block mt5">
	<% if (title) { %>
		<div class="tit-bar">
		<i style="background:<%=color%>;"></i>
		<%= title %>
		<% if (more_url) { %>
				<a href="<%=more_url%>">更多</a>
		<% } %>
		</div>
	<% } %>
		<ul class="item-pic-l4-r1">
				<% for (var i in item) { %>
				<li>

					<a href="<%= item[i].url %>"><img src="<%= item[i].image %>" alt=""></a>

				</li>

			<% } %>
		</ul>

	</div>

</script>

<script type="text/html" id="home17">
	<div class="nctouch-home-block mt5">
	<% if (title) { %>
		<div class="tit-bar">
		<i style="background:<%=color%>;"></i>
		<%= title %>
		<% if (more_url) { %>
				<a href="<%=more_url%>">更多</a>
		<% } %>
		</div>
	<% } %>
<div class="main3" >
<div class="main2" >
<div class="demo1"> <% for (var i in item) { %>
		
				<a href="<%= item[i].url %>">
					<img src="<%= item[i].image %>" alt="">
				</a>
		<% } %>
					
</div> 
</div>
</div>
</script>
<script type="text/html" id="home18">
	<div class="nctouch-home-block mt5">
	<% if (title) { %>
		<div class="tit-bar">
		<i style="background:<%=color%>;"></i>
		<%= title %>
		<% if (more_url) { %>
				<a href="<%=more_url%>">更多</a>
		<% } %>
		</div>
	<% } %>
		<ul class="item-pic-l5-r1">
				<% for (var i in item) { %>
				<li>

					<a href="<%= item[i].url %>"><img src="<%= item[i].image %>" alt=""></a>

				</li>

			<% } %>
		</ul>

	</div>

</script> 
<script type="text/html" id="goods">

	<div class="nctouch-home-block item-goods mt5">

	<% if (title) { %>

		<div class="tit-bar">

			<i style="background:<%=color%>;"></i>

			<%= title %>

			<% if (more_url) { %>

				<a href="<%=more_url%>">更多</a>

			<% } %>

		</div>

	<% } %>

		<ul class="goods-list">

		<% for (var i in item) { %>

			<li>

				<a href="<%= item[i].goods_url  %>">

					<div class="goods-pic"><img src="<%= item[i].goods_image %>" alt=""></div>

					<dl class="goods-info">

						<dt class="goods-name"><%= item[i].goods_name %></dt>

						<dd class="goods-price">￥<em><%= item[i].goods_promotion_price %></em></dd>

					</dl>

				</a>

			</li>

		<% } %>

		</ul>

	</div>



</script> 



<script type="text/html" id="goods1">



	<div class="nctouch-home-block item-goods mt5">

	<% if (title) { %>

		<div class="tit-bar"><i style="background:<%=color%>;"></i><%= title %></div>

	<% } %>

		<ul class="goods-list">

		<% for (var i in item) { %>

			<li>

				<a href="<%= item[i].goods_url  %>">

					<div class="goods-pic"><img src="<%= item[i].goods_image %>" alt=""></div>

					<dl class="goods-info">

						<dt class="goods-name"><%= item[i].goods_name %></dt>

						<dd class="goods-price">￥<em><%= item[i].goods_promotion_price %></em></dd>

					</dl>

				</a>

				<i class="zk">限时折扣</i>

			</li>

		<% } %>

		</ul>

	</div>

</script> 



<script type="text/html" id="goods2">

	<div class="nctouch-home-block item-goods mt5">

	<% if (title) { %>

		<div class="tit-bar"><i style="background:<%=color%>;"></i><%= title %></div>

	<% } %>

		<ul class="goods-list">

		<% for (var i in item) { %>

			<li>

				<a href="<%= item[i].goods_url %>">

					<div class="goods-pic"><img src="<%= item[i].goods_image %>" alt=""></div>

					<dl class="goods-info">

						<dt class="goods-name"><%= item[i].goods_name %></dt>

						<dd class="goods-price">￥<em><%= item[i].goods_promotion_price %></em></dd>

					</dl>

				</a>

				<i class="tg">特卖</i>

			</li>

		<% } %>

		</ul>

	</div>



</script>

<script type="text/html" id="goods3">

	<div class="nctouch-home-block item-goods mt5">

	<% if (title) { %>

		<div class="tit-bar"><i style="background:<%=color%>;"></i><%= title %></div>

	<% } %>

		<ul class="goods-list goods-list-four">

		<% for (var i in item) { %>

			<li>

				<a href="<%= item[i].goods_url  %>">

					<div class="goods-pic"><img src="<%= item[i].goods_image %>" alt=""></div>

					<dl class="goods-info">

						<dt class="goods-name"><%= item[i].goods_name %></dt>

						<dd class="goods-price">￥<em><%= item[i].goods_promotion_price %></em></dd>

					</dl>

				</a>

			</li>

		<% } %>

		</ul>

	</div>



</script>

<script type="text/html" id="goods4">

	<div class="nctouch-home-block item-goods mt5">

	<% if (title) { %>

		<div class="tit-bar"><i style="background:<%=color%>;"></i><%= title %></div>

	<% } %>

		<ul class="goods-list goods-list-three">

		<% for (var i in item) { %>

			<li>

				<a href="<%= item[i].goods_url  %>">

					<div class="goods-pic"><img src="<%= item[i].goods_image %>" alt=""></div>

					<dl class="goods-info">

						<dt class="goods-name"><%= item[i].goods_name %></dt>

						<dd class="goods-price">￥<em><%= item[i].goods_promotion_price %></em></dd>

					</dl>

				</a>

			</li>

		<% } %>

		</ul>

	</div>



</script>





<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/swipe.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/index.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/addtohomescreen.js"></script>
<?php if($output['zom_list'][0]['site_status']==1){?>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>
<script type="text/javascript">
$(function(){
	  var zomzc = getCookie('zomzc');
       if(!zomzc){
        $(".white").show();
          $(".newuser-divmask").show();
      }
	    TouchSlide({ 
	    slideCell:"#zommbox",
	    titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
	    mainCell:".bd ul", 
	    effect:"leftLoop", 
	    delayTime:1000,
	    interTime:4000,
	    autoPage:true,//自动分页
	    autoPlay:true //自动播放
		})

})

function closezz(){

   addCookie('zomzc',1);

  $(".white").hide();

  $(".newuser-divmask").hide();

}

</script>

<?php }?>

<script>
	addToHomescreen({
		message:'如要把应用程式加至主屏幕,请点击%icon, 然后<strong>加至主屏幕</strong>'
	});
	$(function(){
		$("#logo").click(function(){
			window.location.href = "<?php echo MOBILE_SITE_URL;?>";
		})
		scrollUp($(".notice-roller"), 40, 3000);
    });
</script>

  

<?php require_once template('footer');?>



