var PagingData = {
    /*起始页*/
    pageindex: 2,
    /*路径*/
    url: "",
    /*绑定列表控件名称*/
    controlname: "",
    /*传递参数*/
    param: { con: "",fun:"", t: +new Date() },
    // param: { con: "article",fun:"article" },

    firstInitUrl: "",
    /*初始化*/
    init: function (url, param, controls, ifload, firstInitUrl) {
        
        this.url = url;
        this.param = $.extend({}, this.param, param);
        this.controlname = controls;
        this.ifload = ifload == 1 ? true : false;
        this.firstInitUrl = firstInitUrl;
        if (this.firstInitUrl != "") {
            this.pagingeventFisrt(); //默认读第一页数据 
        }
    },
    /*是否加载页面*/
    ifload: true,
    /*分页事件*/

    pagingevent: function () {
        /*要绑定的控件*/
        var list = $("#" + PagingData.controlname);
        if (PagingData.ifload) {
            /*是否加载列表*/
            PagingData.ifload = false;
            /*添加滚动条*/
            /*list.append("<img id=\"imgLoading\" src=\"/images/loading.gif\" style=\"display:block;margin:10px auto;\"/>");*/
            $('body').append( "<div class=\"pre-loading\"><div class=\"pre-block\"><div class=\"spinner\"><i></i></div>数据读取中... </div></div>");
            /*加载数据*/
            $.get(PagingData.url, ($.extend({}, PagingData.param, { p: PagingData.pageindex })),
                function (data) {
                    $(".pre-loading").remove();
                    if (data.status == 1) {
                          if(data.article_pl){
                            $(".pl em").text(data.article_pl);
                            $(".zan em").text(data.article_zan);
                          }
                           var r = template.render("news_list", data);
                            list.append(r);
                      
                        if (PagingData.pageindex < data.pages) {/*如果当前页小于总页数*/
                            /*当前页*/
                            PagingData.pageindex++;
                            /*是否加载列表*/
                            PagingData.ifload = true;
                            /*绑定数据*/
                        } else {
                            PagingData.ifload = false;
                           
                            list.append("<div id=\"imgLoading\" style=\"font-size:13px; text-align:center; background:#eee; padding:10px; display:block; margin:10px auto;\">加载完毕</div>");
                            setTimeout(function () { $("#imgLoading").remove(); }, 2000);
                        }
                    }else {
                       
                        PagingData.ifload = false;
                        //list.append("<li style='text-align:center;'><br/><img src='/msource/images/nodate.png'><br/><br/></li>");
                    }
                }, "json");
        }
        
    },
    pagingeventFisrt: function () {
        this.pageindex = 1;

        /*要绑定的控件*/
        var list = $("#" + PagingData.controlname);
        if (PagingData.ifload) {
            /*是否加载列表*/
            PagingData.ifload = false;
            /*添加滚动条*/
            /*list.append("<img id=\"imgLoading\" src=\"/images/loading.gif\" style=\"display:block;margin:10px auto;\"/>");*/
            $('body').append( "<div class=\"pre-loading\"><div class=\"pre-block\"><div class=\"spinner\"><i></i></div>数据读取中... </div></div>");
            /*加载数据*/
            $.get(PagingData.firstInitUrl, ($.extend({}, PagingData.param, { p: PagingData.pageindex })),
               function (data) {             
                   $(".pre-loading").remove();
                   if (data.status == 1) {
                       
                       if (PagingData.pageindex == 1) {
                           list.empty();
                       }
                       var r = template.render("news_list",data);
                       list.append(r);
                       if (PagingData.pageindex < data.pages) {/*如果当前页小于总页数*/
                           /*当前页*/
                           PagingData.pageindex++;
                           /*是否加载列表*/
                           PagingData.ifload = true;
                           /*绑定数据*/
                       } else {
                           PagingData.ifload = false;

                           list.append("<div id=\"imgLoading\" style=\"font-size:13px; text-align:center; background:#eee; padding:10px; display:block; margin:10px auto;\">加载完毕</div>");
                           setTimeout(function () { $("#imgLoading").remove(); }, 2000);
                       }


                   } else {
                       PagingData.ifload = false;
                      
                       list.append("<li style='text-align:center;'><br/><img src='/wap/templates/default/images/nodate.png'><br/><br/></li>");
                   }
               }, "json");
        }
    },
};

$(window).scroll(function () {
    var docS = $(document.documentElement).scrollTop() || $(document.body).scrollTop();
    if ($(document).height() - docS < 800) {
        PagingData.pagingevent();
    }
});
