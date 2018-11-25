<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>



<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/news.css">

<!-- <link rel="stylesheet" type="text/css" href="../css/swiper.css"> -->



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



<div class='detail-wraper'>

  <div class='main-wrap'>

        <div class="news_details">

         <div class='yh weizhi'>当前位置：<a href='<?php echo urlMobile();?>'>首页</a><em>&gt;</em><a href='<?php echo urlMobile('article');?>'>新闻资讯

          </a><em>&gt;</em>

          <div class='slide-menu'><?php echo $output['article']['class_name']?>

          <span class='menu_zixun'></span>

          <span class='myiconfont icon-appxiugaiicon20'></span>

          <div class='slide-menu-list' id='menu-list'>

              <?php if(is_array($output['class_list']) && !empty($output['class_list'])){?>

              <?php foreach($output['class_list'] as $key=>$cl){?>

                 <a href='javascript:searchData(<?php echo $cl['ac_id']; ?>);'><?php echo $cl['ac_name']; ?></a>

              <?php } ?>

              <?php } ?>

        

            </div>

          </div>

          </div>

        <div class='newsinfo'>

        <div class='news-content'>

         <div class='news-title'><h3 ac_id="<?php echo $output['article']['article_id'];?>"><?php echo $output['article']['article_title']?></h3>

          <div class='btn-grp'><span class='left' style='margin-right: 5px;'><?php echo $output['article']['article_time']?></span></div>

          </div>

         <div class='word'><?php echo $output['article']['article_content']?></div>

        </div>

        </div>

        <div class="zan-comment">

          <div class="news-share">

          <div class="share-title">

          <span></span><span>爱分享</span><span></span>

          </div>

          <div  class="bdsharebuttonbox bdshare-button-style0-32">

          <a href="javascript:;" class="jiathis_button_weixin myiconfont icon-weixin" title="分享到微信"></a>

          <a href="javascript:;" class="jiathis_button_qzone myiconfont icon-qq" data-cmd="qzone" title="分享到QQ空间"></a>

          <a href="javascript:;" class="jiathis_button_tsina myiconfont icon-xinlang" data-cmd="tsina" title="分享到新浪微博"></a>

          <a href="javascript:;" class="bds_more jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" data-cmd="more">更多分享</a>

          </div>

          </div>



          <!-- JiaThis Button BEGIN -->





        <script type="text/javascript" >

        var jiathis_config={

          summary:"",

          shortUrl:false,

          hideMore:false

        }

        </script>

        <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>



        <!-- JiaThis Button END -->



          <div class="com-area" id="PLmao">

          <div class="bottom-comment" id="comment-area">

          <textarea placeholder="在此输入评论" id="content"></textarea>

          <div class="pl-zan">

            <span class="pl">

              <i class="myiconfont icon-pinglun"></i><em><?php echo $output['article']['article_pl']?></em>

            </span>

            <span class="zan">

              <i class="myiconfont icon-dianzan"></i><em><?php echo $output['article']['article_zan']?></em>

            </span>

          </div>

          <div class="btn-group">

          <a href="javascript:;" class="add-comment" onclick="publishComment(this);">评论</a><a href="javascript:;" class="cancel">取消</a>

          </div>

          </div>

          <h3 class="comment-title">热门评论</h3>

          <div class="com-list" id="commentList">



          </div>

          </div>

        </div>

</div>





<script type="text/html" id="news_list">

  <% if(datas){%>

  <% var clists = datas.clists; %>

  <% if(clists.length >0){%>

      <div class="item">

       <%for(i=0;i<clists.length;i++){%>

        <dl>

          <dt><img src="<%=clists[i].member_avatar;%>"></dt>

          <dd><p class="name"><%=clists[i].member_name;%><em><%=clists[i].s_comment_time;%></em></p><p><%=clists[i].s_comment_content;%></p></dd>

        </dl>

        <% } %>

      </div>

  <% } %>

  <% } %>

</script> 





<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/pagingNews.js"></script>



<script>

var cid = $('.news-title h3').attr('ac_id');

 $(function() {

    

    var parms = {

      con: "article",

      fun: "get_hot",

      nid:cid,

    

    };

    PagingData.init(ApiUrl+"/index.php", parms, "commentList", 1, ApiUrl+"/index.php");

    if (getCookie("newszan" + cid)) {

        $(".pl-zan i.icon-dianzan").addClass("curZan");

      }

      var content = $(".newsinfo .news-content .word p");

      content.each(function() {

        if ($(this).children().length == 0) {

          $(this).remove();



        }

      });

      $("body").on("click",".slide-menu",function() {

        var _this = $(this);

        _this.toggleClass("curM").find(".slide-menu-list").toggle();

      });

      $(document).on("tap",function() {

        $(".weizhi .slide-menu .slide-menu-list").hide();

        $(".weizhi .slide-menu").removeClass("curM");

      });

      $(".weizhi .slide-menu").on("tap",

      function(e) {

        e.stopPropagation();

      });

      $(".bottom-comment .pl-zan span.pl").on("click",function() {

        $(".bottom-comment textarea").trigger("focus");

    });

    $(".bottom-comment .cancel").on("click",function() {

      $(this).parent().hide().parent().find("textarea").val("").removeClass("curFocus").next(".pl-zan").show();

    });

    $(".bottom-comment textarea").on("focus",function() {

      if ($(this).hasClass("curFocus")) {

        return;

      }

      $(".bottom-comment .pl-zan").hide(200);

      $(this).addClass("curFocus");

      $(".bottom-comment .btn-group").show(300);

    });

    $(".pl-zan span.zan").on("click",function() {

      var nid = $('.news-title h3').attr('ac_id');

      var cnt = $(".pl-zan i.icon-dianzan").next("em").text();

      zan(nid, parseInt(cnt), $(this));

    });

  });



  



  

 

 

  function zan(newsId, count, obj) {

    var key = 'newszan' + newsId;

    if (getCookie(key) == 1) {

         layer.open({

              content: '亲，您已经赞过了！',

              time: 1.5

          });

    } else {

      $.post(ApiUrl+"/index.php?con=article&fun=ajax_zan", {

        newsId: newsId

      },

      function(res) {

        if (res.status == 1) {

          addCookie(key, 1);

          count = count + 1;

          $(obj).find("em").html(count);

          $(obj).find("i").addClass("curZan");

        }

      },

      "json");

    }

  }

  

  

  var conFlg = false;

  function publishComment(obj) {

    var e = getCookie("key");

   

    var the = $(obj);

    var content = strFilter1($("#content").val());

    var nid = cid;

     if (!e) {

        location.href = "<?php echo urlMobile('login','index'); ?>";

        return false;

    }

    if (content == "" || content == null) {

              layer.open({

                  content: '评论内容不能为空，请填写评论！',

                  time: 1.5

              });

      $('#content').focus();

    } else {

      if (!conFlg) {

        conFlg = true;

        $.post(ApiUrl+"/index.php?con=article&fun=ajax_content", {

          'Pcontent': content,

          'nid': nid,

          'key': e

        },

        function(res) {

          if (res.status == 1) {



            var data = res.data;

            $(".bottom-comment .pl-zan span i.icon-pinglun").next("em").html(res.totalcount);

            $("#content").val("");

            the.parent().hide().parent(".bottom-comment").find("textarea").removeClass("curFocus fixed").next(".pl-zan").show();

            conFlg = false;

            var parms = {

              con: "article",

              fun: "get_hot",

              nid:nid

            };

            PagingData.init(ApiUrl+"/index.php", parms, "commentList", 1, ApiUrl+"/index.php");

          } else if (res.status == 2) {

              layer.open({

                  content: '你已经评论过此文章了，不能重复评论哦！',

                  time: 1.5

              });

          }else if(res.status == 3){

              checkLogin(res.login);

          } else {

             layer.open({

                  content: '出错了，请重试！',

                  time: 1.5

              });

          }

        },

        "json");

      }

    }

  }

  function searchData(kind) {

    window.location.href = "<?php echo urlMobile('article','index')?>&cid="+kind;

  }



 



</script>

