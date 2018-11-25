<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header" >

  <div class="header-wrap">

     <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>消息管理</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

      <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout mb20">

<!--   <div id="loding"></div>-->  

<div class="nctouch-address-list" >

	<div class ="xiaoxi" style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);" id="loadData">

            

            

     

    </div>



</div>

</div>

<script type="text/html" id="news_list">

  <% var nlists = datas.nlists;%>

  <% if(nlists.length >0){%>

  <%for(i=0;i<nlists.length;i++){%>

  			<div class="times"><span><%=nlists[i].message_time;%></span></div>

            <div class="info-list">

                <a href="<%=nlists[i].message_url;%>" kind="<%=nlists[i].message_id;%>">

                    <h1><%=nlists[i].message_title;%></h1>

                    

                    <p><%=nlists[i].message_body;%></p>

                    <h4 class="blue border_top" style="display:"><%=nlists[i].message_url_title;%></h4>

                </a>

            </div>

      

    <% } %>

  <% } %>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/pagingNews.js"></script>

<script>

	$(function(){

		 $("#loadData").html("");

		    var parms = {

		        con: "message",

		        fun:"ajax_message",

		    };

    	 PagingData.init(ApiUrl+"/index.php", parms, "loadData", 1, ApiUrl+"/index.php");

	})

</script>