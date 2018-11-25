/**
 * all shop 
 */
$(function(){
    $("#area_info").on("click",
        function() {
            
            $.areaSelected({
                success: function(a) {
                    $("#area_info").val(a.area_info).attr({
                        "data-areaid": a.area_id,
                        "data-areaid2": a.area_id_2 == 0 ? a.area_id_1: a.area_id_2
                    })
                }
            })
    });
    var keyword = decodeURIComponent(getQueryString("keyword"));
    if (keyword != "") {
        $("#keyword").val(keyword);
    }
    var area_info = decodeURIComponent(getQueryString("area_info"));
    if (area_info != "") {
        $("#area_info").val(area_info);
    }
    $("input[name=sc_id]").val(getQueryString('sc_id'));
    $(".page-warp").click(function (){
        $(this).find(".pagew-size").toggle();
    });
    if($("input[name=sc_id]").val()!=''){
        $.ajax({
            url:ApiUrl+"/index.php?con=shop&fun=shop_list&key=4&page="+pagesize+"&curpage=1"+'&sc_id='+$("input[name=sc_id]").val(),
            type:'get',
            dataType:'json',
            success:function(result){
                $("input[name=hasmore]").val(result.hasmore);
                if(!result.hasmore){
                    $('.next-page').addClass('disabled');
                }

                var curpage = $("input[name=curpage]").val();//鍒嗛〉
                var page_total = result.page_total;
                var page_html = '';
                for(var i=1;i<=result.page_total;i++){
                    if(i==curpage){
                        page_html+='<option value="'+i+'" selected>'+i+'</option>';
                    }else{
                        page_html+='<option value="'+i+'">'+i+'</option>';
                    }
                }

                $('select[name=page_list]').empty();
                $('select[name=page_list]').append(page_html);

                var html = template.render('category-one', result.datas);
                $("#categroy-cnt").append(html);

                $(window).scrollTop(0);
            }
        });
    }else{
        $.ajax({
            url:ApiUrl+"/index.php?con=shop&fun=shop_list&key=4&page="+pagesize+"&curpage=1"+'&keyword='+$("#keyword").val()+'&area_info='+area_info,
            type:'get',
            dataType:'json',
            success:function(result){
                $("input[name=hasmore]").val(result.hasmore);
                if(!result.hasmore){
                    $('.next-page').addClass('disabled');
                }

                var curpage = $("input[name=curpage]").val();//鍒嗛〉
                var page_total = result.page_total;
                var page_html = '';
                for(var i=1;i<=result.page_total;i++){
                    if(i==curpage){
                        page_html+='<option value="'+i+'" selected>'+i+'</option>';
                    }else{
                        page_html+='<option value="'+i+'">'+i+'</option>';
                    }
                }

                $('select[name=page_list]').empty();
                $('select[name=page_list]').append(page_html);

                var html = template.render('category-one', result.datas);
                $("#categroy-cnt").append(html);

                $(window).scrollTop(0);
            }
        });
    }


    $("select[name=page_list]").change(function(){
        var key = parseInt($("input[name=key]").val());
        var order = parseInt($("input[name=order]").val());
        var page = parseInt($("input[name=page]").val());
        var sc_id = parseInt($("input[name=sc_id]").val());
        var keyword = $("input[name=keyword]").val();
        var hasmore = $("input[name=hasmore]").val();

        var curpage = $('select[name=page_list]').val();

        if(sc_id>0){
            var url = ApiUrl+"/index.php?con=shop&fun=shop_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&sc_id="+sc_id;
        }else{
            var url = ApiUrl+"/index.php?con=shop&fun=shop_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&keyword="+keyword;
        }

        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            success:function(result){
                var html = template.render('category-one', result.datas);
                $("#categroy-cnt").empty();
                $("#categroy-cnt").append(html);

                $(window).scrollTop(0);

                if(curpage>1){
                    $('.pre-page').removeClass('disabled');
                }else{
                    $('.pre-page').addClass('disabled');
                }

                if(curpage<result.page_total){
                    $('.next-page').removeClass('disabled');
                }else{
                    $('.next-page').addClass('disabled');
                }

                $("input[name=curpage]").val(curpage);
            }
        });

    });


    $('.keyorder').click(function(){
        var key = parseInt($("input[name=key]").val());
        var order = parseInt($("input[name=order]").val());
        var page = parseInt($("input[name=page]").val());
        var curpage = eval(parseInt($("input[name=curpage]").val())-1);
        var sc_id = parseInt($("input[name=sc_id]").val());
        var keyword = $("input[name=keyword]").val();
        var hasmore = $("input[name=hasmore]").val();

        var curkey = $(this).attr('key');//1.閿€閲� 2.娴忚閲� 3.浠锋牸 4.鏈€鏂版帓搴�
        if(curkey == key){
            if(order == 1){
                var curorder = 2;
            }else{
                var curorder = 1;
            }
        }else{
            var curorder = 1;
        }

        if (curkey == 3) {
            if (curorder == 1) {
                $(this).find('span').removeClass('desc').addClass('asc');
            } else {
                $(this).find('span').removeClass('asc').addClass('desc');
            }
        }

        $(this).addClass("current").siblings().removeClass("current");

        if(sc_id>0){
            var url = ApiUrl+"/index.php?con=shop&fun=shop_list&key="+curkey+"&order="+curorder+"&page="+page+"&curpage=1&sc_id="+sc_id;
        }else{
            var url = ApiUrl+"/index.php?con=shop&fun=shop_list&key="+curkey+"&order="+curorder+"&page="+page+"&curpage=1&keyword="+keyword;
        }

        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            success:function(result){
                $("input[name=hasmore]").val(result.hasmore);
                var html = template.render('category-one', result.datas);
                $("#categroy-cnt").empty();
                $("#categroy-cnt").append(html);
                $("input[name=key]").val(curkey);
                $("input[name=order]").val(curorder);
            }
        });
    });


  
    $('#serach_store').click(function(){
        var keyword = encodeURIComponent($('#keyword').val());
        var area_info = encodeURIComponent($('#area_info').val());
        location.href = ApiUrl+'/index.php?con=shop&fun=shop_list&keyword='+keyword+'&area_info='+area_info;
    });
});