<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_products_list.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">
<style type="text/css">
.nctouch-footer-wrap { margin:0 auto; max-width:640px;}
</style>
</head>
<body>
<header id="header" class="fixed">
  <div class="header-wrap">
    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
    <div class="header-tab"><a href="<?php echo urlMobile('shop');?>">所有店铺</a><a href="<?php echo urlMobile('shop','shopclass');?>"  class="cur">店铺分类</a></div>
    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
  </div>
    <?php include template('layout/toptip');?>
</header>
<div class="nctouch-main-layout fixed-Width">
 <ul class="favorites-store-list" id="categroy-cnt"></ul>
</div>
</body>
<script type="text/html" id="category-one">
	<ul class="categroy-list">
		<% for(var i = 0;i<class_list.length;i++){
		var locUrl = "";
		if(class_list[i]['sc_id'].toString().length >0) {
			locUrl = ApiUrl+"/?index.php&con=shop&sc_id="+class_list[i]['sc_id'].toString();
		}else {
			locUrl = ApiUrl+"/?index.php&con=shop";
		}
		%>
		 <li>
            <a href="<%=locUrl%>">
                <dl class="store-info" style="margin-left:10px;">
                    <dt class="store-name"><%=class_list[i]['sc_name'].toString() %></dt>
                </dl>
            </a>
        </li>
	
		<% } %>
	</ul>
</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>
<script>
	$(function() {
    
      $.ajax({
        type: "post",
        url:ApiUrl+"/index.php?con=shop&fun=get_shopclass",
        dataType: "json",
        success:function(result){
            var data = result.datas;
            data.ApiUrl = ApiUrl;
            var html = template.render('category-one', data);
            $("#categroy-cnt").html(html);
        }
    })

});
</script>