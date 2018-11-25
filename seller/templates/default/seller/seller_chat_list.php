<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_chat.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>消息列表</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more bgc-t"></i><sup></sup></a> </div>

  </div> 

  <?php include template('layout/seller_toptip');?>

</header>

<div class="nctouch-main-layout">

  <ul class="nctouch-message-list" id="messageList">

  </ul>

</div>

<script type="text/html" id="messageListScript">

<% if (!isEmpty(list)) { %>

<% for (var k in list) { %>

    <li> <a href="<%=ApiUrl%>/index.php?con=seller_chat&t_id=<%=k%>&t_name=<%=list[k].u_name%>">

      <div class="avatar">

		<img src="<%=list[k].avatar%>"/>

		<% if (list[k].r_state == 2) {%>

		<sup></sup>

		<%}%>

	</div>

      <dl>

        <dt><%=list[k].u_name%></dt>

        <dd><%=list[k].t_msg%></dd>

      </dl>

      <time><%=list[k].time%></time>

      </a>

	  <a href="javascript:void(0)" t_id="<%=k%>" class="msg-list-del"></a>

	  </li>

<% } %>

<% } else { %>

        <div class="nctouch-norecord talk">

			<div class="norecord-ico"><i></i></div>

				<dl>

                 <dt>您还没有和任何人联系过</dt>

				<dd>对话后可在此找到最近联系的用户</dd>

			</dl>

     	</div>

<% } %>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/seller_chat_list.js"></script>

