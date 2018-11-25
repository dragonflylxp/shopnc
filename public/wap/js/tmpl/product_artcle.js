var article_id = getQueryString("article_id");
var map_list = [];
var map_index_id = '';
var store_id;
$(function () {
    var key = getCookie('key');
    get_detail();
    function get_detail(goods_id) {
        var dis_id = getQueryString('dis_id');
        
        //渲染页面
        $.ajax({
            url: ApiUrl + "/index.php?con=store&fun=store_detail",
            type: "get",
            data: {article_id: article_id, key: key},
            dataType: "json",
            success: function (result) {
                var data = result.datas;
					document.title= data.seo_title.html_title;
					$("meta[name='keywords']").attr('content',data.seo_title.seo_keywords);
					$("meta[name='description']").attr('content',data.seo_title.seo_description);                
                //渲染模板
                var html = template.render('product_detail', data);
			    
                $("#product_detail_html").html(html);
   
                
                if($('.wenzhang').length && $('.wenzhang').length>0) {
                                $('.wenzhang').html(data.article_detail.article_content);
                }    
            }
        })
	}
})