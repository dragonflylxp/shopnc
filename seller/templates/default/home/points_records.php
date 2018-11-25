<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<style type="text/css">

  .recordCon {

  position:relative;

  margin-top:1px;

  height:100%;

  border-left:1px solid #D7D5D5;

  margin-left:30px;

}



.z-minheight {

    min-height: 200px;

}

.recordCon ul {

  border-top:1px solid #efefef;

  padding:15px 0;

  position:relative;

  margin-top:-1px;

}

.recordCon li {

  color:#999;

  line-height:20px;

  font-size:12px;

}

.recordCon li.rBg {

  position:absolute;

  left:-25px;

  z-index:15;

  border-radius: 50%;

  overflow: hidden;

}

.recordCon li.rBg a {

  display:block;

  position:relative;

}

.recordCon li img {

  width:40px;

  height:40px;

  margin:2px 0 0 3px;

}

.recordCon li.rBg a s {

    background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/share-img.png?v=130812);

    background-size: 90px auto;

}

.recordCon li.rBg a s {

  background-position:-44px -99px;

  background-repeat:no-repeat;

  width:46px;

  height:46px;

  position:absolute;

  left:0;

  top:0;

}

.recordCon li.rInfo {

  margin-left:25px;

}

.recordCon a {

  color:#2af;

  font-size:14px;

  margin-right:3px;

}

.recordCon span {

  margin-right:10px;

  font-size:14px;

}

.recordCon em {

  color:#ccc;

}

.recordCon strong {

  word-wrap:break-word;

  display:inline-block;

  font-weight:normal;

}



.recordCon i.rei {

  background:url(<?php echo MOBILE_TEMPLATES_URL;?>/images/r-line.gif) no-repeat;

  height:1px;

  width:230px;

  position:absolute;

  left:0;

  top:-1px;

}

#divRecordList .nctouch-norecord{

  z-index: 1;

}

</style>



</head>

<body>

<header id="header" class="posf">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <ul class="header-nav">

      <li><a href="javascript:void(0);" id="goodsDetail">商品</a></li>

      <li><a href="javascript:void(0);" id="goodsBody">详情</a></li>

      <li class="cur"><a href="javascript:void(0);" id="goodsdh">记录</a></li>

    </ul>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>



</header>

<?php require_once template('layout/fiexd');?>



<div class="nctouch-main-layout" id="fixed-tab-pannel">

  <div class="fixed-tab-pannel">

   <section id="buyRecordPage" class="goodsCon">

       

  

    </section>

  </div>

</div>

<div class="pre-loading">

  <div class="pre-block">

    <div class="spinner"><i></i></div>

    数据读取中... </div>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/html" id="home_body">



        



  <% var orderprod_list = datas.orderprod_list; %>

  <% if(orderprod_list.length >0){%>

   <div id="divRecordList" class="recordCon z-minheight" style="">

  <%for(i=0;i< orderprod_list.length;i++){%>

        <ul>

          <li class="rBg">

          <a href="javascript:void(0)">

            <img src="<%=orderprod_list[i].member_avatar;%>"><s></s>

          </a>

          </li>

          <li class="rInfo">

            <a href="javascript:void(0)"><%=orderprod_list[i].point_buyername;%></a>

            <!-- <strong>(浙江省舟山市 IP:123.157.77.181)</strong><br> -->

            <span>兑换了<b class="orange"><%=orderprod_list[i].point_goodsnum;%></b>件</span>

            <em class="arial"><%=orderprod_list[i].point_shippingtime;%></em>

          </li>

          <i class="rei"></i>

        </ul>



<%}%>

      <% if (hasmore) {%>

      <li class="loading"><div class="spinner"><i></i></div>代金券数据读取中...</li>

      <% }else{ %>

        <li class="loading">没有了...</li>



        

      <% } %>

      </div>

  <%



     }else {

  %>

    <div class="nctouch-norecord search">

      <div class="norecord-ico"><i></i></div>

        <dl style="background:none;box-shadow:none;height:auto">

          <dt >没有找到任何相关信息</dt>

          <dd style="background-color:none;box-shadow:none">选择或搜索其它...</dd>

        </dl>

      <a href="javascript:history.go(-1)" class="btn">重新选择</a>

    </div>



    

  <%

     }

  %>



</script>



<script type="text/javascript">

    var page = pagesize;

    var curpage = 1;

    var hasmore = true;

    var pgoods_id= <?php echo $output['pgoods_id'];?>;

    $(function(){

    

         $("#goodsDetail").click(function() {

             window.location.href = ApiUrl + "/index.php?con=points&fun=detail&pgoods_id=" + pgoods_id;

        });

        $("#goodsBody").click(function() {

           window.location.href = ApiUrl + "/index.php?con=points&fun=goods_body&pgoods_id=" + pgoods_id;

        });



    get_list();

    $(window).scroll(function() {

        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {

            get_list()

        }

    });



    })



    function get_list() {



    if (!hasmore) {

        return false

    }

    hasmore = false;

    param = {};

    param.page = page;

    param.curpage = curpage;

  

    if (pgoods_id != "") {

        param.pgoods_id = pgoods_id

    }

 

    

    $.getJSON(ApiUrl + "/index.php?con=points&fun=get_records", param,

    function(e) {

        if (!e) {

            e = [];

            e.datas = [];

            e.datas.pointprod_list = [];



        }

        curpage++;

        var r = template.render("home_body", e);

         $("#buyRecordPage").html(r);

        $(".pre-loading").hide();



        hasmore = e.hasmore

    })

}



</script>

