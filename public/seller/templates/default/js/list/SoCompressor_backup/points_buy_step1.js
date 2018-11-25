var key = getCookie("key");

var invoice_id = 0;
var address_id, vat_hash, offpay_hash, offpay_hash_batch, voucher, pd_pay, password, fcode = "",
rcb_pay, rpt, payment_code;
var message = {};
var freight_hash, city_id, area_id;
var area_info;

$(function() {
   
   
    $("#list-address-valve").click(function() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_address&fun=address_list",
            data: {
                key: key
            },
            dataType: "json",
            async: false,
            success: function(e) {
                checkLogin(e.login);
                if (e.datas.address_list == null) {
                    return false
                }
                var a = e.datas;
                a.address_id = address_id;
                var i = template.render("list-address-add-list-script", a);
                $("#list-address-add-list-ul").html(i)
            }
        })
    });
    $.animationLeft({
        valve: "#list-address-valve",
        wrapper: "#list-address-wrapper",
        scroll: "#list-address-scroll"
    });
    $("#list-address-add-list-ul").on("click", "li",
    function() {
        $(this).addClass("selected").siblings().removeClass("selected");
        eval("address_info = " + $(this).attr("data-param"));

        insertHtmlAddress(address_info);
        //console.log(address_info);
        $("#list-address-wrapper").find(".header-l > a").click()
    });
    $.animationLeft({
        valve: "#new-address-valve",
        wrapper: "#new-address-wrapper",
        scroll: ""
    });
    $.animationLeft({
        valve: "#select-payment-valve",
        wrapper: "#select-payment-wrapper",
        scroll: ""
    });
    $("#new-address-wrapper").on("click", "#varea_info",
    function() {
        $.areaSelected({
            success: function(e) {
                city_id = e.area_id_2 == 0 ? e.area_id_1: e.area_id_2;
                area_id = e.area_id;
                area_info = e.area_info;
                $("#varea_info").val(e.area_info)
            }
        })
    });
    $.animationLeft({
        valve: "#invoice-valve",
        wrapper: "#invoice-wrapper",
        scroll: ""
    });
   
   
   var address_options = $("#address_options").attr('address_options');
   var lilength = $('.nctouch-cart-item li').length;
     if (!address_options) {
            layer.open({
                content: '请添加地址',
                btn: ['嗯', '不要'],
                yes: function(index){
                    $("#new-address-valve").click();
                    layer.close(index);
                },no:function(index){
                    history.go( -1);
                     layer.close(index);
                }
            });
            return false
        }else if(lilength<1){
            layer.open({
                content: '订单信息有误!',
                btn: ['嗯'],
                yes: function(index){
                     history.go( -1);
                    layer.close(index);
                }
            });
            return false
           
        }else{
             $("#ToBuyStep2").parent().addClass("ok");
        }


  
    var insertHtmlAddress = function(e) {
        address_id = e.address_id;
        $("#true_name").html(e.true_name);
        $("#mob_phone").html(e.mob_phone);
        $("#address").html(e.area_info + e.address);
        $("#address_options").attr("address_options",address_id);
        $("input[name='address_options']").var(address_id);
        area_id = e.area_id;
        city_id = e.city_id;
        $("#ToBuyStep2").parent().addClass("ok");
        
    };
    
    $.sValid.init({
        rules: {
            vtrue_name: "required",
            vmob_phone: "required",
            varea_info: "required",
            vaddress: "required"
        },
        messages: {
            vtrue_name: "姓名必填！",
            vmob_phone: "手机号必填！",
            varea_info: "地区必填！",
            vaddress: "街道必填！"
        },
        callback: function(e, a, i) {
            if (e.length > 0) {
                var t = "";
                $.map(a,
                function(e, a) {
                    t += "<p>" + e + "</p>"
                });
                errorTipsShow(t)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#add_address_form").find(".btn").click(function() {
        if ($.sValid()) {
            var e = {};
            e.key = key;
            e.true_name = $("#vtrue_name").val();
            e.mob_phone = $("#vmob_phone").val();
            e.address = $("#vaddress").val();
            e.city_id = city_id;
            e.area_id = area_id;
            e.area_info = $("#varea_info").val();
            e.is_default = 0;
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=member_address&fun=address_add",
                data: e,
                dataType: "json",
                success: function(a) {
                    if (!a.datas.error) {
                   
                         insertHtmlAddress(a.datas.address_info);
                        $("#new-address-wrapper,#list-address-wrapper").find(".header-l > a").click()
                    }
                }
            })
        }
    });
  
    $("#ToBuyStep2").click(function() {
     var address_options = $("#address_options").attr('address_options');
    var address_options1 = $("input[name='address_options']").val();
    var point_ordermessage = $("#point_ordermessage").val();
    $("input[name='pcart_message']").val(point_ordermessage);
     if (!address_options && !address_options1) {
            layer.open({
                content: '请添加地址',
                btn: ['嗯', '不要'],
                yes: function(index){
                    $("#new-address-valve").click();
                    layer.close(index);
                },no:function(index){
                    history.go( -1);
                     layer.close(index);
                }
            });
            return false
        }
        $("#substep2").submit();
       
    })
});