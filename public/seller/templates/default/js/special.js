$(function(){
    var special_id = getQueryString('special_id');
    loadSpecial(special_id);
})

function loadSpecial(special_id){
    $.ajax({
        url: ApiUrl + "/index.php?con=index&fun=get_special&special_id=" + special_id,
        type: 'get',
        dataType: 'json',
        success: function(result) {
            $('h1').html(result.datas.special_desc);
            var data = result.datas.list;
            var html = '';

            $.each(data, function(k, v) {

                $.each(v, function(kk, vv) {

                    switch (kk) {

                        case 'adv_list':

                        case 'home3':
                        case 'home15':
                        case 'home16':
                        case 'home17':
                        case 'home18':
                            $.each(vv.item, function(k3, v3) {

                                vv.item[k3].url = buildUrl(v3.type, v3.data);

                            });

                            break;
                        case 'home1':
                        case 'home12':
                        case 'home13':
                        case 'home10':

                            vv.url = buildUrl(vv.type, vv.data);
                            break;
                        case 'goods':
                        case 'goods1':
                        case 'goods2': 
                        case 'goods3':
                        case 'goods4': 
                             $.each(vv.item, function(k5, v5) {
                                vv.item[k5].goods_url = buildUrl('goods', v5.goods_id);
                            });
                            break;    
                        case 'home2':
                        case 'home4':

                            vv.square_url = buildUrl(vv.square_type, vv.square_data);

                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);

                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);

                            break;

                        case 'home5':

                        case 'home6':

                            vv.square_url = buildUrl(vv.square_type, vv.square_data);

                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);

                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);

                            vv.rectangle3_url = buildUrl(vv.rectangle1_type, vv.rectangle3_data);

                            vv.rectangle4_url = buildUrl(vv.rectangle2_type, vv.rectangle4_data);

                            break;

                        case 'home7':

                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);

                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);

                            vv.rectangle3_url = buildUrl(vv.rectangle1_type, vv.rectangle3_data);

                            break;

                        case 'home8':

                        case 'home9':

                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);

                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);

                            vv.rectangle3_url = buildUrl(vv.rectangle3_type, vv.rectangle3_data);

                            vv.rectangle4_url = buildUrl(vv.rectangle4_type, vv.rectangle4_data);

                            vv.rectangle5_url = buildUrl(vv.rectangle5_type, vv.rectangle5_data);

                            break;

                        case 'home10':

                        case 'home11':

                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);

                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);

                            vv.rectangle3_url = buildUrl(vv.rectangle3_type, vv.rectangle3_data);

                            vv.rectangle4_url = buildUrl(vv.rectangle4_type, vv.rectangle4_data);

                            break;

                        case 'nav':
                            $.each(vv.item, function(k4, v4) {
                                vv.item[k4].image_url = buildUrl(v4.image_type, v4.image_data);
                            });

                            break;



                    }
                    $(".pre-loading").hide();
                    if (k == 0) {
                        $("#main-container1").html(template.render(kk, vv));
                    } else {
                        html += template.render(kk, vv);
                    }
                    return false;
                });
            });

            $("#main-container").html(html);

            $('.adv_list').each(function() {
                if ($(this).find('.item').length < 2) {
                    return;
                }

                Swipe(this, {
                    startSlide: 2,
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function(index, elem) {},
                    transitionEnd: function(index, elem) {}
                });
            });

        }
    });

}
