<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-tab"><a href="javascript:void(0);" class="cur">充值卡余额</a> <a href="<?php echo urlMobile('member_fund','rechargecard_add');?>">充值卡充值</a></div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

  <div id="rcb_count" class="nctouch-asset-info"></div>

  <ul id="rcbloglist" class="nctouch-log-list">

  </ul>

</div>

<div class="fix-block-r">

    <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

<script type="text/html" id="rcb_count_model">

	<div class="container rcard">

			<i class="icon"></i>

		    <dl>

				<dt>充值卡余额</dt>

				<dd>￥<em><%=available_rc_balance;%></em></dd>

			</dl>

		</div>

</script> 

<script type="text/html" id="list_model">

        <% if(log_list.length >0){%>

        <% for (var k in log_list) { var v = log_list[k]; %>

            <li><div class="detail"><%=v.description;%></div>

                <time class="date"><%=v.add_time_text;%></time>

                <% if(v.available_amount >0){%>

                <div class="money add">+<%=v.available_amount;%></div>

                <%}else{%>

                <div class="money reduce"><%=v.available_amount;%></div>

                <%}%>

            </li>

        <%}%>

        <li class="loading"><div class="spinner"><i></i></div>数据读取中</li>

        <%}else {%>

        <div class="nctouch-norecord recharge">

            <div class="norecord-ico"><i></i></div>

            <dl>

                <dt>您尚无充值卡使用信息</dt>

				<dd>使用充值卡充值余额结算更方便</dd>

            </dl>

        </div>

        <%}%>

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

        load_class.loadInit({'url':ApiUrl + '/index.php?con=member_fund&fun=rcblog','getparam':{'key':key},'tmplid':'list_model','containerobj':$("#rcbloglist"),'iIntervalId':true});



        //获取预存款余额

        $.getJSON(ApiUrl + '/index.php?con=member_index&fun=my_asset', {'key':key,'fields':'available_rc_balance'}, function(result){

            var html = template.render('rcb_count_model', result.datas);

            $("#rcb_count").html(html);

        });

    });

</script>

