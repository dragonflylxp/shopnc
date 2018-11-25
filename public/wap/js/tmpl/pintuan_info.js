$(function() {
    var pintuan_id = getQueryString("pintuan_id");
    var buyer_id = getQueryString("buyer_id");
    var key = getCookie('key');
    $.ajax({
        url: ApiUrl + "/index.php?con=pintuan&fun=info",
        data: {key: key, pintuan_id: pintuan_id,buyer_id: buyer_id},
        type: "get",
        dataType: "json",
        success: function(result) {
            var html = template.render('product_detail', result.datas.pintuan_info);
            $("#product_detail_html").html(html);
            html = template.render('puzzle-btn', result.datas.pintuan_info);
            $("#btn_html").html(html);
            takeCount();
        }
    });
});
	function takeCount() {
	    setTimeout("takeCount()", 1000);
	    $(".puzzle-Countdown").each(function(){
	        var obj = $(this);
	        var tms = obj.attr("count_down");
	        if (tms>0) {
	            tms = parseInt(tms)-1;
                var hours = Math.floor(tms / (1 * 60 * 60)) % 24;
                var minutes = Math.floor(tms / (1 * 60)) % 60;
                var seconds = Math.floor(tms / 1) % 60;

                if (hours < 0) hours = 0;
                if (minutes < 0) minutes = 0;
                if (seconds < 0) seconds = 0;
                obj.find("[time_id='h']").html(hours);
                obj.find("[time_id='m']").html(minutes);
                obj.find("[time_id='s']").html(seconds);
                obj.attr("count_down",tms);
	        }
	    });
	}