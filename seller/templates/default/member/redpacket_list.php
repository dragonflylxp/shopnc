<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <span class="header-tab"> <a href="javascript:void(0);" class="cur">我的红包</a> <a href="<?php echo urlMobile('member_redpacket','redpacket_pwex');?>">领取红包</a> </span>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

  <div class="nctouch-voucher-list">

    <ul class="nctouch-tickets" id="rplist">

    </ul>

  </div>

</div>

<div class="fix-block-r"> <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a> </div>

<script type="text/html" id="list_model">

    <% if(redpacket_list.length >0){%>

        <% for (var k in redpacket_list) { var v = redpacket_list[k]; %>

        <li class="ticket-item normal">

        <li class="ticket-item <% if (v.rpacket_state_key == 'unused') { %>normal<% }else{ %>invalid<%}%>">

            <a href="/">

            <div class="border-left"></div>

            <div class="block-center">

                <div class="store-info">

                    <div class="store-avatar"><img src="<%=v.rpacket_customimg_url;%>" /></div>

                    <dl>

                        <dt class="store-name"><%=v.rpacket_code;%></dt>

                        <dd>有效期至：<%=v.rpacket_end_date_text;%></dd>

                    </dl>

                </div>

                <div class="ticket-info">

                    <div class="bg-ico rp"></div>

                    <% if (v.rpacket_state_key== 'used') { %>

                    <div class="watermark ysy"></div>

                    <% } %>

                    <% if (v.rpacket_state_key== 'expire') { %>

                    <div class="watermark ysx"></div>

                    <% } %>

                    <dl>

                        <dt>￥<%=v.rpacket_price;%></dt>

                        <dd><% if (v.rpacket_limit) { %>满<%= v.rpacket_limit %>使用<% } %></dd>

                    </dl>

                </div>

            </div>

            <div class="border-right"></div>

        </a>

        </li>

        <%}%>

        <li class="loading"><div class="spinner"><i></i></div>数据读取中</li>

    <%

    } else {

    %>

    <div class="nctouch-norecord redpacket">

		<div class="norecord-ico"><i></i></div>

		<dl>

			<dt>您还没有相关的红包</dt>

			<dd>平台红包可抵扣现金结算</dd>

		</dl>

	</div>

    <% } %>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script> 

<script>

    function showSpacing(){

        $('.spacing-div').remove();

        $('.invalid').first().before('<div class="spacing-div"><span>已失效红包</span></div>');

    }

    $(function(){

        var key = getCookie('key');

        if (!key) {

            window.location.href =  WapUrl + "/tmpl/member/login.html";

            return;

        }



        //渲染list

        var load_class = new ncScrollLoad();

        load_class.loadInit({'url':ApiUrl + '/index.php?con=member_redpacket&fun=redpacket_list','getparam':{'key':key},'tmplid':'list_model','containerobj':$("#rplist"),'iIntervalId':true,'callback':showSpacing});

    });

</script>

