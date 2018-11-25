//20160906
var page = '10';
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = '';
    
$(function(){
    if (getQueryString("key") != "") {

        var a = getQueryString("key");

        addCookie("key", a);

    } else {

        var a = getCookie("key");

        

    }

    if (getQueryString('data-level') != '') {
        $('#filtrate_ul').find('li').has('a[data-level="' + getQueryString('data-level')  + '"]').addClass('selected').siblings().removeClass("selected");
    }

    $('#fixed_nav').waypoint(function() {
        $('#fixed_nav').toggleClass('fixed');
    }, {
        offset: '50'
    });

    function initPage(){
        if (reset) {
            curpage = 1;
            hasMore = true;
        }
        $('.loading').remove();
        if (!hasMore) {
            return false;
        }
        hasMore = false;
        var level = $('#filtrate_ul').find('.selected').find('a').attr('data-level');
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?con=distribution&fun=get_list&page="+page+"&curpage="+curpage,
            data:{key:key, level:level},
            dataType:'json',
            success:function(result){
                //检测是否登录
                checkLogin(result.login);
                curpage++;

                if (result.datas.invite_list.length <= 0) {
                    $('#footer').addClass('posa');
                } else {
                    $('#footer').removeClass('posa');
                }
                var data = result;
                data.WapSiteUrl = WapSiteUrl;
                data.ApiUrl = ApiUrl;
                data.key = getCookie('key');
                template.helper('$getLocalTime', function (nS) {
                    var d = new Date(parseInt(nS) * 1000);
                    var s = '';
                    s += d.getFullYear() + '年';
                    s += (d.getMonth() + 1) + '月';
                    s += d.getDate() + '日 ';
                    s += d.getHours() + ':';
                    s += d.getMinutes();
                    return s;
                });
                template.helper('p2f', function(s) {
                    return (parseFloat(s) || 0).toFixed(2);
                });
                template.helper('parseInt', function(s) {
                    return parseInt(s);
                });
                $(".distribution_level").html(result.datas.title);
                var html = template.render('distribution-list-tmpl', data);
                if (reset) {
                    reset = false;
                    $("#distribution_list").html(html);
                } else {
                    $("#distribution_list").append(html);
                }
            }
        });
    }
    
    $('#filtrate_ul').find('a').click(function(){
        $('#filtrate_ul').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0,0);
        initPage();
    });

    //初始化页面
    initPage();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            initPage();
        }
    });
});
