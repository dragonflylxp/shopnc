<?php defined('Inshopec') or exit('Access Invalid!');?>



<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

.layermbox1 .layermcont{

	text-align: center;

}

.layermchild h3{

	display: none;

}

</style>

</head>

<body>

<div class="pre-loading">

    <div class="pre-block">

      <div class="spinner"><i></i></div>

     数据读取中... 

    </div>

</div>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-title">

      <h1>我的交易评价/晒单</h1>

    </div>

 	<div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>

</header>

<div class="nctouch-main-layout" style="margin-top: 4rem;">

<div class="ncm-evaluation-list" id="evaluation-list">











</div>

</div>

<div class="fix-block-r">

	<a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>





<script type="text/html" id="list_data">

<% if (goodsevallist.length > 0) {%>



	<% for (var i=0; i<goodsevallist.length; i++) { var evallist = goodsevallist[i];%>

	

	  <div class="ncm-evaluation-timeline">

    <div class="date">

     	<%=evallist.geval_addtime%>

    </div>

    <div class="goods-thumb">

      <a target="_blank" href="<%=evallist.geval_goodsurl%>" >

        <img src="<%=evallist.geval_goodsimage%>">

      </a>

    </div>

    <dl class="detail">

      <dt>

        <a target="_blank" href="<%=evallist.geval_goodsurl%>">

          <%=evallist.geval_goodsname%>

        </a>

        

      </dt>

      <dd>

      	<span class="ml30">

          商品评分：

          <em class="goods-raty"><i class="star<%=evallist.geval_scores%>"></i></em>

        

        </span>

      </dd>

      <dd>

         <%=evallist.geval_content%>

      </dd>

      <% if (evallist.geval_snslist) { var  snslist = evallist.geval_snslist;%>

      <dd>

        <ul class="photos-thumb">

		 <% for (var ii=0; ii<snslist.length; ii++) {%>

          <li>

            <a nctype="nyroModal" href="javascript:void(0);" big-url="<%=snslist[ii].big%>">

              <img src="<%=snslist[ii].small%>">

            </a>

          </li>

          <% } %>

        

        </ul>

      </dd>

      <% } %>

    

      <%  if(evallist.geval_content_again){%>

      <hr>

      <dd>

        [追加评价]&nbsp; <%=evallist.geval_content_again%>

      </dd>

      <% if (evallist.geval_againsnslist) { var  againsnslist = evallist.geval_againsnslist;%>

      <dd>

        <ul class="photos-thumb">

		 <% for (var iis=0; iis<againsnslist.length; iis++) {%>

          <li>

            <a nctype="nyroModal" href="javascript:void(0);" big-url="<%=againsnslist[iis].big%>">

              <img src="<%=againsnslist[iis].small%>">

            </a>

          </li>

          <% } %>

        

        </ul>

      </dd>

      <% } %>

      <dd class="pubdate">

          <%=evallist.again_info%>

      </dd>

      <% } %>

    </dl>

  </div>

<% } %>

 <dd class="loading"><div class="spinner"><i></i></div>浏览记录读取中...</dd>



<% } else {%>

	<div class="nctouch-norecord views">

		<div class="norecord-ico"><i></i></div>

		<dl>

			<dt>暂无您的浏览记录</dt>

			<dd>可以去看看哪些想要买的</dd>

		</dl>

		<a href="<%=ApiUrl%>" class="btn">随便逛逛</a>

	</div>

<% } %>

</script> 





<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/comment_list.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script>

