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

.info-list h4 span.li_view,.info-list h4 span.li_chuli,.info-list h4 span.li_sh{

  width: 60px;

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

.info-list h4 span.li_sh i{

   background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/qrsh.png) no-repeat center center;

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

      <h1>退货记录</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

      <?php include template('layout/seller_toptip');?>



</header>

<div class="nctouch-main-layout mb20">

  <div id="fixed_nav" class="nctouch-single-nav">

    <ul id="filtrate_ul" class="w20h">

      <li class="selected"><a href="javascript:void(0);" data-state="2">售前退货</a></li>

      <li><a href="javascript:void(0);" data-state="1">售后退货</a></li>

     

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

<div class="alert_box_two hide">

  <div class="alert_box">

   <input type="hidden" class="text" id="order_id" name="order_id" value=""/>

  <dl class="order_sn">

      <dt>发货时间:</dt>

      <dd><span class="times"></span></dd>

      <dt>物流信息:</dt>

      <dd><span class="wlxx"></span></dd>

    </dl>

    <dl>

      <dt>收货情况:</dt>

      <dd>

        <ul class="checked">

          <li>

            <input checked="" name="return_type" id="d1" value="4" type="radio">

            <label for="d1">已收到</label>

          </li>

          <li  class="return_type_show hide" >

            <input name="return_type" id="d2" value="3" type="radio" >

            <label for="d2">没收到</label>

          </li>

         

        </ul>

          

      </dd>

    </dl>

    <dl><p class="hint" style="text-align:left;color:#f2661a">如果暂时没收到请联系买家，发货<span class="return_delay"></span>天后可以选择未收到，买家可以延长时间，超过<span class="return_confirm"></span>天不处理按弃货处理。</p></dl>

    

</div>

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

                    <p>退货编号：<%=refund_list[i].refund_sn;%></p>

                    <p>退货金额：￥<%=refund_list[i].refund_amount;%></p>

                    <p>退货数量：<%=refund_list[i].goods_num;%></p>

                    <p>买家会员名：<%=refund_list[i].buyer_name;%></p>

                    <h4 class="blue border_top">

        

      

                      <% if(refund_list[i].seller_state==1){%>

                        <span return_id="<%=refund_list[i].refund_id;%>" class="li_chuli"><i></i>处理</span>

                      <% } else { %>

                        <span return_id="<%=refund_list[i].refund_id;%>" class="li_view"><i></i>查看</span>

                      <% } %>

                      <% if(refund_list[i].seller_state==2 && refund_list[i].return_type==2 && refund_list[i].goods_state==2){%>

                        <span return_id="<%=refund_list[i].refund_id;%>" class="li_sh"><i></i>收货</span>

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

		        con: "seller_order_return",

		        fun:"get_return",

            lock:t

		    };

    	 PagingData.init(ApiUrl+"/index.php", parms, "loadData", 1, ApiUrl+"/index.php");

    }

     var a = 2;

   

    if (getQueryString("lock") != "") {

      $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("lock") + '"]').addClass("selected").siblings().removeClass("selected");

       var a = getQueryString("lock");

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



      var e = $(this).attr("return_id");

        window.location.href = ApiUrl + "/index.php?con=seller_order_return&fun=edit&return_id=" + e

    })

        

   

    $('body').on('click','.li_view',function(){

        var e = $(this).attr("return_id");

        window.location.href = ApiUrl + "/index.php?con=seller_order_return&fun=view&return_id=" + e

    })

    $('body').on('click','.li_sh',function(){

        var et = $(this).attr("return_id");



         var loding = layer.open({type:2,content:"加载中..."});

                  $.ajax({

                    type: "post",

                    url: ApiUrl + "/index.php?con=seller_order_return&fun=get_receive",

                    data: {

                        return_id: et,

                 

                    },

                    dataType: "json",

                    async: false,

                    success: function(e) {

                        if (e.code == 200) {

                          var data= e.datas;

                          layer.close(loding);

                            $('.order_sn .times').text(data.delay_time);

                            $('.order_sn .wlxx').text(data.e_express);

                            $('.return_delay').text(data.return_delay);

                            $('.return_confirm').text(data.return_confirm);

                              if(data.delay_time > 0 ){

                                  $('.return_type_show').show();

                              }

                               layer.open({  

                                  title: '确认收货',

                                  content: $('.alert_box_two').html(),

                                  btn: ['确定'],

                                   yes: function(index){

                                    var return_type = $('.layermcont').find("input[type='radio']:checked").val();

                                  

                                    if(!return_type){

                                       

                                             layer.open({

                                                content:'请选择收货情况'

                                             });

                                       

                                    }

                                    layer.open({type:2,content:"提交中..."});

                                      $.ajax({

                                        type: "post",

                                        url: ApiUrl + "/index.php?con=seller_order_return&fun=receive",

                                        data: {

                                            form_submit: 'ok',

                                            return_id: et,

                                            return_type: return_type

                                        },

                                        dataType: "json",

                                        async: false,

                                        success: function(d) {

                                            if (d.code == 200) {

                                                layer.open({

                                                    content:'收货成功!'

                                                });

                                                 setTimeout(function () {

                                                    location.reload();

                                                    layer.closeAll();

                                                }, 1000);  

                                               

                                            } else {

                                            

                                                layer.open({

                                                    content:d.datas.error,

                                                    time:1.5

                                                 })

                                                layer.closeAll();

                                            }

                                        }

                                    });

                          } 

                    })



       

        }

	   }



  })

 });

});

</script>



