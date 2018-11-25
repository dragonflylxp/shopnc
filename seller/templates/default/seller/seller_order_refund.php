<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

.nctouch-single-nav ul li{

    width: 50%;

  }

.info-list h4{

  height: 31px;

  line-height: 31px;

}

.info-list h4 span{

  width: 70px;

  height: 31px;

  line-height: 31px;

 

  display: inline-block;

  float: right;

  text-align: center;

}

.info-list h4 span i{

  width: 20px;

  height: 31px;

  line-height: 31px;

  display: inline-block;



  vertical-align: middle;

  margin-right: 4px;

}

.info-list h4 span.li_view,.info-list h4 span.li_chuli{

  width: 85px;

  float: left;



}

.info-list h4 span.stroe_status,.info-list h4 span.terrace_status{

  color: #e44d4d;

}

.info-list h4 span.stroe_status i{

  background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/sj.png) no-repeat center center;

  background-size: 100%;

  opacity: 0.5;

}

.info-list h4 span.terrace_status i{

  background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/pt.png) no-repeat center center;

  background-size: 100%;

  opacity: 0.5;

}

.info-list h4 span.li_view i{

   background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/view.png) no-repeat center center;

   background-size: 100%;

   opacity: 0.5;

}

.info-list h4 span.li_chuli i{

   background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/chuli.png) no-repeat center center;

   background-size: 100%;

   opacity: 0.5;

}



</style>

</head>

<body>

<header id="header" >

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

     <div class="header-title">

      <h1>退款记录</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

      <?php include template('layout/seller_toptip');?>



</header>

<div class="nctouch-main-layout mb20">

  <div id="fixed_nav" class="nctouch-single-nav">

    <ul id="filtrate_ul" class="w20h">

      <li class="selected"><a href="javascript:void(0);" data-state="2">售前退款</a></li>

      <li><a href="javascript:void(0);" data-state="1">售后退款</a></li>

     

    </ul>

  </div>

<!--   <div id="loding"></div>-->  

<div class="nctouch-address-list" >

	<div class ="xiaoxi" style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);" id="loadData">

            

            

     

    </div>



</div>

</div>

<div class="fix-block-r">



    <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>



</div>

<script type="text/html" id="news_list">

  <% var refund_list = datas.refund_list;%>

  <% if(refund_list.length >0){%>

  <%for(i=0;i<refund_list.length;i++){%>

  			<div class="times"><span><%=refund_list[i].add_time;%></span></div>

            <div class="info-list">

              <a href="javascript:void(0);">

                    <h1><%=refund_list[i].goods_name;%></h1>

                    <p>订单编号：<%=refund_list[i].order_sn;%></p>

                    <p>退款编号：<%=refund_list[i].refund_sn;%></p>

                    <p>退款金额：￥<%=refund_list[i].refund_amount;%></p>

                    <p>买家会员名：<%=refund_list[i].buyer_name;%></p>

                    <h4 class="blue border_top">

                   

                      <% if(refund_list[i].seller_state==1){%>

                        <span refund_id="<%=refund_list[i].refund_id;%>" class="li_chuli"><i></i>立即处理</span>

                      

                      <% }else{ %>

                        <span refund_id="<%=refund_list[i].refund_id;%>" class="li_view"><i></i>立即查看</span>

                      <% } %>

                      <% if(refund_list[i].seller_state==2 && refund_list[i].refund_state >=2){%> 
                      <% if(refund_list[i].refund_state==1){%>
                                     <span class="terrace_status"><i></i>处理中</span>
                      <% }else if(refund_list[i].refund_state==2){ %>
                           <span class="terrace_status"><i></i>待处理</span>
                       <% }else if(refund_list[i].refund_state==3){ %>
                          <span class="terrace_status"><i></i>完成</span>
                       <% }else{%>
                         <span class="terrace_status"><i></i>无</span>
                       <% } %>

                                     <% } %>
                         
                          <% if(refund_list[i].seller_state==1){%>
                                  <span class="stroe_status"><i></i>待审核</span>
                      <% }else if(refund_list[i].seller_state==2){ %>
                        <span class="stroe_status"><i></i>同意</span>
                       <% }else if(refund_list[i].seller_state==3){ %>
                       <span class="stroe_status"><i></i>不同意</span>
                       <% }%>

                      

                    </h4>

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

    function list(t){

       

     

		 $("#loadData").html("");

		    var parms = {

		        con: "seller_order_refund",

		        fun:"get_refund",

            lock:t

		    };

    	 PagingData.init(ApiUrl+"/index.php", parms, "loadData", 1, ApiUrl+"/index.php");

    }

    var a = 2;

   

    if (getQueryString("lock") != "") {

      $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("lock") + '"]').addClass("selected").siblings().removeClass("selected");

       a = getQueryString("lock");

    }

    list(a);

    $("#filtrate_ul").find("a").click(function() {

        $("#filtrate_ul").find("li").removeClass("selected");

        $(this).parent().addClass("selected").siblings().removeClass("selected");

        var s = $(this).attr('data-state');

        reset = true;

        window.scrollTo(0, 0);

        list(s)

    });

    $('body').on('click','.li_chuli',function(){



      var e = $(this).attr("refund_id");

        window.location.href = ApiUrl + "/index.php?con=seller_order_refund&fun=edit&refund_id=" + e

    })

        

   

    $('body').on('click','.li_view',function(){

        var e = $(this).attr("refund_id");

        window.location.href = ApiUrl + "/index.php?con=seller_order_refund&fun=view&refund_id=" + e

    })

	})





</script>