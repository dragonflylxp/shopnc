<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>地址管理</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="<?php echo urlMobile('member_address','address_opera');?>"><i class="add"></i></a> </div>

  </div>

</header>

<div class="nctouch-main-layout mb20">

  <div id="loding"></div>

  <div class="nctouch-address-list" id="address_list"></div>

</div>

<script type="text/html" id="saddress_list">

<%if(address_list.length>0){%>

	<ul>

	<%for(var i=0;i<address_list.length;i++){%>

        <li>

            <dl>

                <dt>

					<span class="name"><%=address_list[i].true_name %></span>

					<span class="phone"><%=address_list[i].mob_phone %></span>

				</dt>

                <dd><%=address_list[i].area_info %>&nbsp;<%=address_list[i].address %></dd>

            </dl>

            <div class="handle">

				<% if (address_list[i].is_default == 1) { %>

				默认地址

				<% } %>

           		<span>

					<a href="javascript:;" address_id="<%=address_list[i].address_id %>"  class="address_url"><i class="edit"></i>编辑</a><a href="javascript:;" address_id="<%=address_list[i].address_id %>" class="deladdress"><i class="del"></i>删除</a>

				</span>

            </div>

        </li>

	<%}%>

    </ul>

	<a class="btn-l mt5" href="<?php echo urlMobile('member_address','address_opera');?>">添加新地址</a>

<%}else{%>

    <div class="nctouch-norecord address">

		<div class="norecord-ico"><i></i></div>

			<dl>

				<dt>您还没有过添加收货地址</dt>

				<dd>正确填写常用收货地址方便购物</dd>

			</dl>

			<a href="<?php echo urlMobile('member_address','address_opera');?>" class="btn">添加新地址</a>

		</div>

	</div>

<%}%>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/address_list.js"></script> 

