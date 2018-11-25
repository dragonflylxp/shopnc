<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/index.css">

<style type="text/css">

	#header .header-r a{

		position: relative;

		z-index: 1;

		width: 0.95rem;

		height: 0.95rem;

		vertical-align: top;

		padding: 0.5rem 0.5rem;

		display: inline-block;

	}



</style>

</head>

<body>







<header id="header" class="nctouch-store-header ">

    <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>专题</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>

</header>

<div class="pre-loading">

    <div class="pre-block">

      <div class="spinner"><i></i></div>

     数据读取中... 

    </div>

</div>

<div class="nctouch-main-layout nctouch-home-top fixed-Width">

  <div class="adv_list" id="main-container1"></div>

</div>



<div class=" fixed-Width" id="main-container">

	

</div>



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

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/special.js"></script> 





<?php require_once template('footer');?>