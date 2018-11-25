<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-title">

      <h1>预存款充值</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

  <div id="pd_count" class="nctouch-asset-info"></div>

  <div id="fixed_nav" class="nctouch-single-nav">

    <ul id="filtrate_ul" class="w33h">

      <li><a href="<?php echo urlMobile('member_fund','predepositlog_list');?>">账户余额</a></li>

      <li class="selected"><a href="javascript:void(0);">充值明细</a></li>

      <li><a href="<?php echo urlMobile('member_fund','pdcashlist');?>">余额提现</a></li>

    </ul>

  </div>

  <ul id="pdrlist" class="nctouch-log-list">

  </ul>

</div>

<div class="fix-block-r"> <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a> </div>

<footer id="footer" class="bottom"></footer>

<script type="text/html" id="pd_count_model">

	<div class="container pre">

		<i class="icon"></i>

		    <dl>

				<dt>预存款余额</dt>

				<dd>￥<em><%=predepoit;%></em></dd>

			</dl>

		</div>

</script> 

<script type="text/html" id="list_model">

        <% if(list.length >0){%>

        <% for (var k in list) { var v = list[k]; %>

            <li>

				<dl>

					<dt><i></i>

                        <% if(v.pdr_payment_state == 1){%>

                            <%=v.pdr_payment_name %>：

                        <% } %>

                        <%=v.pdr_payment_state_text %></span></dt>

					<dd>充值单号：<%=v.pdr_sn %></dd>

				</dl>

                <time class="date"><%=v.pdr_add_time_text %></time>

                <div class="money add"><%=v.pdr_amount %></div>

            </li>

        <%}%>

        <li class="loading"><div class="spinner"><i></i></div>数据读取中</li>

        <%}else {%>

        <div class="nctouch-norecord pdre">

            <div class="norecord-ico"><i></i></div>

            <dl>

                <dt>您尚未充值过预存款</dt>

				<dd>使用商城预存款结算更方便</dd>

            </dl>

        </div>

        <%}%>

    </script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.waypoints.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script> 

<script>

    $(function(){

        var key = getCookie('key');

        if (!key) {

            window.location.href = WapUrl + "/tmpl/member/login.html";

            return;

        }

        //渲染list

        var load_class = new ncScrollLoad();

        load_class.loadInit({

            'url':ApiUrl + '/index.php?con=member_fund&fun=get_pdrechargelist',

            'getparam':{'key':key},

            'tmplid':'list_model',

            'containerobj':$("#pdrlist"),

            'iIntervalId':true

        });

        //获取预存款余额

        $.getJSON(ApiUrl + '/index.php?con=member_index&fun=my_asset', {'key':key,'fields':'predepoit'}, function(result){

            var html = template.render('pd_count_model', result.datas);

            $("#pd_count").html(html);



            $('#fixed_nav').waypoint(function() {

                $('#fixed_nav').toggleClass('fixed');

            }, {

                offset: '50'

            });

        });

    });

</script>

