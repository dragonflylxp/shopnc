var pgoods_id = getQueryString("pgoods_id");
var map_list = [];
var map_index_id = "";
var store_id;
$(function() {
    function a() {
        var e = $("#mySwipe")[0];
        window.mySwipe = Swipe(e, {
            continuous: false,
            stopPropagation: true,
            callback: function(e, t) {
                $(".goods-detail-turn").find("li").eq(e).addClass("cur").siblings().removeClass("cur")
            }
        })
    }
$(".minus").click(function() {
    var e = $(".buy-num").val();
    if (e > 1) {
        $(".buy-num").val(parseInt(e - 1))
    }
});
$(".add").click(function() {
    var e = parseInt($(".buy-num").val());
    var kc = parseInt($('.nctouch-bottom-mask-block .kcnum').text());
    if (e < kc) {
        $(".buy-num").val(parseInt(e + 1))
    }
});

   $.animationUp({
        valve: ".animation-up,#goods_spec_selected",
        wrapper: "#product_detail_spec_html",
        scroll: "#product_roll",
        start: function() {
            $(".goods-detail-foot").addClass("hide").removeClass("block")
        },
        close: function() {
            $(".goods-detail-foot").removeClass("hide").addClass("block")
        }
    });
   

$("body").on('click','.buy-now2',function(){
  
    var ts = $(this);
    var pgoods_id = ts.attr('pgoods_id');
    var pgoods_islimit = ts.attr('pgoods_islimit');
    var pgoods_limitnum = ts.attr('pgoods_limitnum');
    var pgoods_storage = ts.attr('pgoods_storage');
    var buynum =  parseInt($(".buy-num").val());
    if(pgoods_islimit==1){
         if(buynum>pgoods_limitnum){
            layer.open({
                content:"你只能兑换"+pgoods_limitnum+"件产品",
                time:1.5
            });
         }
    }
     layer.open({type:2,content:'正在跳转...',});
      $.getJSON(ApiUrl+'/index.php?con=pointcart&fun=add', {
                    pgid: pgoods_id,
                    quantity: buynum
        },function(result){
            if(result.done==false){
                layer.open({
                    content:result.msg,
                    time:1.5
                });

                if(result.url){
                   setTimeout(function () {
                        window.location.href = result.url;   
                    }, 1500);    
                }
                
            }else{

           setTimeout(function () {
                window.location.href = ApiUrl + "/index.php?con=pointcart"
            }, 1500); 
         
        }
        })

})

$.scrollTransparent();   
    $.sValid.init({
        rules: {
            buynum: "digits"
        },
        messages: {
            buynum: "请输入正确的数字"
        },
        callback: function(e, t, o) {
            if (e.length > 0) {
                var a = "";
                $.map(t,
                function(e, t) {
                    a += "<p>" + e + "</p>"
                });
             
                layer.open({
                    content:a,
                    time:1.5
                })
            }
        }
    });
    function n() {
        $.sValid()
    }
    

 
    $("body").on("click", "#goodsBody,#goodsBody1",
    function() {
        window.location.href = ApiUrl + "/index.php?con=points&fun=goods_body&pgoods_id=" + pgoods_id
    });
    $("body").on("click", "#goodsdh",
    function() {
        window.location.href = ApiUrl + "/index.php?con=points&fun=records&pgoods_id=" + pgoods_id
    });


  
});
