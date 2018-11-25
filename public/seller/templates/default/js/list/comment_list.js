var key = getCookie('key');
$(function(){
    
    //渲染list
    var load_class = new ncScrollLoad();
    load_class.loadInit({
        'url':ApiUrl + '/index.php?con=member_evaluate&fun=get_list',
        'getparam':{'key':key},
        'tmplid':'list_data',
        'page':1,
        'containerobj':$("#evaluation-list"),
        'iIntervalId':true,
        'data':{ApiUrl:ApiUrl}
    });
    $("body").on("click", ".photos-thumb li",function(){
            var img = $(this).find('a').attr('big-url');
             var pagei = layer.open({
                type: 1,
                title:'查看晒单',
                content: "<img src="+img+" width='100%'>",
                shadeClose: false,
                style: 'width:' + ($(window).width() * 0.9) + 'px; max-height:' + ($(window).height() * 0.9) + 'px;border-radius:5px; border:none;text-align:center;',
                yes: function(olayer) {
                    var cla = 'getElementsByClassName';
                    olayer[cla]('close')[0].onclick = function() {
                        layer.closeAll();
                }
            
            }
        })
    })

});

