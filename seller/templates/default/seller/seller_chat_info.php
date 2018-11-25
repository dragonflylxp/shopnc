<?php defined('Inshopec') or exit('Access Invalid!');?>



<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_chat.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>消息详情</h1>

    </div>

    <div class="header-r"><a href="javascript:void(0)" class="msg-log" id="chat_msg_log"><i></i>历史</a></div>

  </div>

</header>

<div class="nctouch-chat-layout">

  <div class="nctouch-chat-con">

    <div class="margin-heigh"></div>

    <div id="chat_msg_html"> </div>

    <a href="javascript:void(0);" id="anchor-bottom"></a> </div>

  <div class="nctouch-chat-bottom">

    <div class="chat-input-layout"> <span class="open-smile"><a href="javascript:void(0)" id="open_smile"></a></span>

      <div class="input-box">

        <input type="text" id="msg"/>

        <a href="javascript:void(0)" id="submit" class="submit"></a> </div>

    </div>

    <div class="chat-smile-layout hide" id="chat_smile">

      <ul>

      </ul>

    </div>

  </div>

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/seller_chat_info.js"></script>

