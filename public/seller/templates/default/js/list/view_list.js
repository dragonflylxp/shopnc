var key = getCookie('key');
$(function(){
    
    //渲染list
    var load_class = new ncScrollLoad();
    load_class.loadInit({
        'url':ApiUrl + '/index.php?con=member_goodsbrowse&fun=browse_list',
        'getparam':{'key':key},
        'tmplid':'viewlist_data',
        'containerobj':$("#viewlist"),
        'iIntervalId':true,
        'data':{ApiUrl:ApiUrl}
    });

    $("#clearbtn").click(function(){
        $.ajax({
            type: 'post',
            url: ApiUrl + '/index.php?con=member_goodsbrowse&fun=browse_clearall',
            data: {key: key},
            dataType: 'json',
            async: false,
            success: function(result) {
                if (result.code == 200) {
                    //$.sDialog({skin: "green", content: "清空成功", okBtn: false, cancelBtn: false});
                    location.href =ApiUrl + '/index.php?con=member_goodsbrowse';
                }else{
                   layer.open({
                        content:result.datas.error,
                        time:1.5
                   });
                }
            }
        });
    });
});

