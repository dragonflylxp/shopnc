$(function(){
   // 根据querystring是否带有唯一agencyId/merchantId发起联合注册或登录请求
   var agencyId = getQueryString("agencyId"); 
   var merchantId = getQueryString("merchantId"); 
   if (agencyId != null && agencyId != "" && merchantId != null && merchantId != "") {
       comlog(agencyId, merchantId, initPage);
   }
   else{
       initPage();
   }
});

function initPage() {
    var myScroll;
    $("#header").on('click', '.header-inp', function(){
        location.href = WapSiteUrl + '/tmpl/search.html';
    });
    $.getJSON(ApiUrl+"/index.php?con=goods_class", function(result){
		var data = result.datas;
		data.WapSiteUrl = WapSiteUrl;
		var html = template.render('category-one', data);
		$("#categroy-cnt").html(html);
		myScroll = new IScroll('#categroy-cnt', { mouseWheel: true, click: true });

                //带锚点跳转访问
                var gc_id = getQueryString("gc_id"); 
                if (gc_id != null && gc_id != "") {
                    var item = getElementByAttr('category-item-a category', 'date-id', gc_id);
                    var $item = $(item); 
        	    $('.pre-loading').show();
        	    $item.parent().addClass('selected').siblings().removeClass("selected");
        	    $.getJSON(ApiUrl + '/index.php?con=goods_class&fun=get_child_all', {gc_id:gc_id}, function(result){
        	        var data = result.datas;
                    	data.WapSiteUrl = WapSiteUrl;
                    	var html = template.render('category-two', data);
                    	$("#categroy-rgt").html(html);
                    	$('.pre-loading').hide();
                    	new IScroll('#categroy-rgt', { mouseWheel: true, click: true });
        	    });
                myScroll.scrollToElement(document.querySelector('.categroy-list li:nth-child(' + ($item.parent().index()+1) + ')'), 1000);
                }
	});
	
	get_brand_recommend();
        
	
	$('#categroy-cnt').on('click','.category', function(){
	    $('.pre-loading').show();
	    $(this).parent().addClass('selected').siblings().removeClass("selected");
	    var gc_id = $(this).attr('date-id');
	    $.getJSON(ApiUrl + '/index.php?con=goods_class&fun=get_child_all', {gc_id:gc_id}, function(result){
	        var data = result.datas;
            data.WapSiteUrl = WapSiteUrl;
            var html = template.render('category-two', data);
            $("#categroy-rgt").html(html);
            $('.pre-loading').hide();
            new IScroll('#categroy-rgt', { mouseWheel: true, click: true });
	    });
        myScroll.scrollToElement(document.querySelector('.categroy-list li:nth-child(' + ($(this).parent().index()+1) + ')'), 1000);
	});

    $('#categroy-cnt').on('click','.brand', function(){
        $('.pre-loading').show();
        get_brand_recommend();
    });

    //document.getElementByClassName("category-item-a category").click();
    //alert(document.getElementsByClassName('category-item-a category'));
}

function get_brand_recommend() {
    $('.category-item').removeClass('selected');
    $('.brand').parent().addClass('selected');
    $.getJSON(ApiUrl + '/index.php?con=brand&fun=recommend_list', function(result){
        var data = result.datas;
        data.WapSiteUrl = WapSiteUrl;
        var html = template.render('brand-one', data);
        $("#categroy-rgt").html(html);
        $('.pre-loading').hide();
        new IScroll('#categroy-rgt', { mouseWheel: true, click: true });
    });
}

function getElementByAttr(clsname,attr,value)
{
    var aElements=document.getElementsByClassName('category-item-a category');
    var aEle=[];
    for(var i=0;i<aElements.length;i++)
    {
        if(aElements[i].getAttribute(attr)==value)
            aEle.push( aElements[i] );
    }
    return aEle;
}
