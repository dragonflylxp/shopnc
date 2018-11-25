<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/news.css">

<!-- <link rel="stylesheet" type="text/css" href="../css/swiper.css"> -->

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/swiper.js"></script>

</head>

<body>

<header id="header" class="nctouch-product-header fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>新闻资讯</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<?php require_once template('layout/fiexd');?>

<div class="nctouch-main-layout mt20 mb20">

</div>

<div class="mwraper">

<div class="news-menu-list">

  <div class="swiper-container" >

     <ul class="swiper-wrapper" id="slide_class">

            <li  class="swiper-slide" id="slde_0" ><a href="javascript:searchData(0,0)" class="curMenu">首页</a></li>



            <?php if(is_array($output['class_list']) && !empty($output['class_list'])){?>

            <?php foreach($output['class_list'] as $key=>$cl){?>

            <li class="swiper-slide " id="slde_<?php echo $cl['ac_id']; ?>"><a href="javascript:searchData(<?php echo $cl['ac_id']; ?>,0)" data-id="<?php echo $cl['ac_id']; ?>"><?php echo $cl['ac_name']; ?></a></li>

            <?php } ?>

            <?php } ?>

     </ul>

  </div>

</div>

<div class="main-wrap">

  <div class="news-list" id="loadData">

  </div>

 </div>

</div>







<script type="text/html" id="news_list">

  <% var nlists = datas.nlists;%>

  <% if(nlists.length >0){%>

  <%for(i=0;i<nlists.length;i++){%>

       <dl>

        <dd>

         <div class="news-detail">

          <a href="index.php?con=article&fun=show&article_id=<%=nlists[i].article_id;%>" class="news-img"><img src="<%=nlists[i].article_img;%>" /><span><%=nlists[i].class_name;%></span></a>

          <h3 class="yh"><a href="index.php?con=article&fun=show&article_id<%=nlists[i].article_id;%>"><%=nlists[i].article_title;%></a></h3>

          <p class="yh"><%=nlists[i].article_summary;%></p>

          <div class="btn-grp">

           <a class="left" href="javascript:;"><i class="myiconfont icon-shiyongshijian"></i><%=nlists[i].article_time;%></a>

           <a href="javascript:;"><i class="myiconfont icon-yueduliang views"></i><%=nlists[i].article_view;%></a> 

           <a href="index.php?con=article&fun=show&article_id=<%=nlists[i].article_id;%>#comment-area"><i class="myiconfont icon-pinglun pinglun"></i><span id="comment<%=nlists[i].article_id;%>"><%=nlists[i].article_pl;%></span></a>

           <a href="javascript:zan(<%=nlists[i].article_id;%>,<%=nlists[i].article_zan;%>);"><i class="myiconfont icon-dianzan"></i><span id="zan<%=nlists[i].article_id;%>"><%=nlists[i].article_zan;%></span></a> 

          </div>

         </div>

        </dd>

       </dl>

    <% } %>

  <% } %>

</script>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/pagingNews.js"></script>

<script type="text/javascript">

var swiper = new Swiper('.swiper-container', {

    slidesPerView: 4,

    paginationClickable: true,

    freeMode: true,

    observer:true,

    observeParents:true,

});

$(".swiper-container ul li a").on("tap", function () {

    var _this = $(this);

    _this.addClass("curMenu").parent().siblings().find("a").removeClass("curMenu");

});

function zan(newsId, count) {

    var key = 'newszan' + newsId;

    if (getCookie(key) == 1) {

        layer.open({

            content: '亲，您已经赞过了！',

            time: 1.5

        });

     }else{

        $.post(ApiUrl+"/index.php?con=article&fun=ajax_zan",{newsId: newsId},

        function(res) {

            if (res.status == 1) {

                addCookie(key, 1);

                count = count + 1;

                $("#zan" + newsId).html(count);

            }

        },

        "json");

    }

}

$(function (){

    var type = getQueryString("cid");



    if(type != null){

        type = parseInt(type);

        searchData(type, '0');

      }else{

     

          searchData('0', '0');

      }

  



});



function searchData(category, idd, obj) {

    if (idd == 4) {

        $(".swiper-wrapper").addClass("moveToLeft");

    }

    $("#slde_"+category).find("a").addClass("curMenu").parent().siblings().find("a").removeClass("curMenu");

    var parms = {

      con:'article',

      fun:'article',

      cid: category,

        

    };

    $("#loadData").html("");

    PagingData.init(ApiUrl+"/index.php", parms, "loadData", 1, ApiUrl+"/index.php");

}







</script>

