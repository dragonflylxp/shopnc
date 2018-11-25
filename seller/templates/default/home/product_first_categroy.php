<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_categroy.css">

</head>

<body>





<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-inp"> <i class="icon"></i> <span class="search-input" id="keyword">请输入关键字</span> </div>

      <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<?php require_once template('layout/fiexd');?>



<div class="nctouch-main-layout">

  <div class="categroy-cnt" id="categroy-cnt"></div>

  <div class="categroy-rgt" id="categroy-rgt"></div>

</div>

<div class="pre-loading">

  <div class="pre-block">

    <div class="spinner"><i></i></div>

    分类数据读取中... </div>

</div>

</body>

<script type="text/html" id="category-one">

	<ul class="categroy-list">

		<li class="category-item selected">

			<a href="javascript:void(0);" class="category-item-a brand">

					<div class="ci-fcategory-ico">

						<img src="<%=ApiUrl%>/templates/default/images/degault.png">

					</div>

				<div class="ci-fcategory-name">品牌推荐</div>

			</a>

		</li>

		<% for(var i = 0;i<class_list.length;i++){ %>

			<li class="category-item">

				<a href="javascript:void(0);" class="category-item-a category" date-id="<%= class_list[i].gc_id %>">

					<div class="ci-fcategory-ico">

						<img src="<% if (class_list[i].image != '') { %><%= class_list[i].image %><% } else {%>../images/degault.png<% } %>">

					</div>

					<div class="ci-fcategory-name"><%= class_list[i].gc_name %></div>

				</a>

			</li>

		<% } %>

	</ul>

</script>

<script type="text/html" id="category-two">

	<dl class="categroy-child-list">

		<% for(var i=0; i<class_list.length; i++) { var col = i % 10;%>

			<dt><a href="<%=ApiUrl%>/index.php?con=goods&fun=list&gc_id=<%= class_list[i].gc_id %>"><i class="col<%= col %>"></i><%= class_list[i].gc_name %><i class="arrow-r"></i></a></dt>

			<% if(class_list[i].child){ %>

				<% for(var j=0; j<class_list[i].child.length; j++) { %>

					<dd><a href="<%=ApiUrl%>/index.php?con=goods&fun=list&gc_id=<%= class_list[i].child[j].gc_id %>"><%= class_list[i].child[j].gc_name %></a></dd>

				<% } %>

			<% } %>

		<% } %>

	</dl>

</script>

<script type="text/html" id="brand-one">

	<dl class="brands-recommend">

		<% for(var i = 0;i<brand_list.length;i++){ %>

			<dd>

				<a href="<%=ApiUrl%>/index.php?con=goods&fun=list&b_id=<%= brand_list[i].brand_id %>">

					<img src="<%= brand_list[i].brand_pic %>">

					<p><%= brand_list[i].brand_name %></p>

				</a>

			</dd>

		<% } %>

	</dl>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/iscroll.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/categroy-frist-list.js"></script>

