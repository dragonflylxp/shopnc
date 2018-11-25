<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

     <span class="header-tab"><a href="javascript:void(0);" class="cur">积分明细</a><a href="<?php echo urlMobile('member_pointorder');?>" >兑换记录</a></span>

  

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  

  </div>

<?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

  <div id="pointscount" class="nctouch-asset-info"></div>

  <ul id="pointsloglist" class="nctouch-log-list">

  </ul>

</div>

<script type="text/html" id="pointscount_model">

	<div class="container point">

		<i class="icon"></i>

			<dl>

				<dt>我的积分</dt>

				<dd><em><%=point;%></em></dd>

			</dl>

	</div>

</script> 

<script type="text/html" id="list_model">

        <% if(log_list.length >0){%>

        <% for (var k in log_list) { var v = log_list[k]; %>

            <li><dl><dt><%=v.stagetext;%></dt>

                <dd><%=v.pl_desc;%></dd>

				</dl>

                <% if(v.pl_points >0){%>

                <div class="money add">+<%=v.pl_points;%></div>

                <%}else{%>

                <div class="money reduce"><%=v.pl_points;%></div>

                <%}%>

                <time class="date"><%=v.addtimetext;%></time>

            </li>

        <%}%>

        <li class="loading"><div class="spinner"><i></i></div>数据读取中</li>

        <%

        }else {

        %>

       <div class="nctouch-norecord signin" style="top: 70%;">

        <div class="norecord-ico"><i></i></div>

        <dl>

            <dt>您还没有任何积分记录</dt>

			<dd>每日签到或购买商品可获取积分</dd>

        </dl>

    	</div>

        <%

        }

        %>

    </script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script> 

<script>

    $(function(){

        var key = getCookie('key');

        if (!key) {

            window.location.href =  WapUrl + "/tmpl/member/login.html";

            return;

        }

		

        //渲染list

        var load_class = new ncScrollLoad();

        load_class.loadInit({'url':ApiUrl + '/index.php?con=member_points&fun=pointslog','getparam':{'key':key},'tmplid':'list_model','containerobj':$("#pointsloglist"),'iIntervalId':true});



        //获取我的积分

        $.getJSON(ApiUrl + '/index.php?con=member_index&fun=my_asset', {'key':key,'fields':'point'}, function(result){

            var html = template.render('pointscount_model', result.datas);

            $("#pointscount").html(html);

        });

    });

</script>

