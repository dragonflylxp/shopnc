$(function() {
    var d = 10;
    var g = false;
    var a = null;
    var f = null;
    var b = null;
    var c = 1;
	var banktype='3';
    var e = function() {
        var k = function(p) {
            layer.open({content:p,time:1})
        };
        function l(m) {
            m = Math.round(m * 1000) / 1000;
            m = Math.round(m * 100) / 100;
            if (/^\d+$/.test(m)) {
                return m + ".00"
            }
            if (/^\d+\.\d$/.test(m)) {
                return m + "0"
            }
            return m
        }
        var h = /^[1-9]{1}\d*$/;
        var j = "";
        var f = $('.gray6');
        var i = function() {
            var m = a.val();
            if (m != "") {
                if (j != m) {
                    if (!h.test(m)) {
                        a.val(j).focus()
                    } else {
                        j = m;
                        f.html('我要充值充值<em class="orange">' + l(m) + "</em>元")
                    }
                }
            } else {
                j = "";
                a.focus();
                f.html('我要充值充值<em class="orange">0.00</em>元')
            }
        };
        $("#ulOption > li").each(function(m) {
           
            var n = $(this);
            if (m < 5) {
                n.click(function() {

                    g = false;
                    d = n.attr("money");
                    n.children("a").addClass("z-sel");
                    n.siblings().children().removeClass("z-sel").removeClass("z-initsel");
                    f.html('我要充值充值<em class="orange">' + n.attr("money") + ".00</em>元")
                })
            } else {
                a = n.find("input");
                a.focus(function() {
                    g = true;
                    if (a.val() == "输入金额") {
                        a.val("")
                    }
                    a.parent().addClass("z-initsel").parent().siblings().children().removeClass("z-sel");
                    if (b == null) {
                        b = setInterval(i, 200)
                    }
                }).blur(function() {
                    clearInterval(b);
                    b = null
                })
            }
        });
   
        $("#btnSubmit").click(function() {
            d = g ? a.val() : d;
            if (d == "" || parseInt(d) == 0) {
                k("请输入充值金额");
            } else {
                var m = /^[1-9]\d*\.?\d{0,2}$/;
                if (m.test(d)) {
                    if (c == 1 || c==2 ||c==3) {
                    $("#order-list").on("click", ".check-payment",
                        function on() {
                            var e = $(this).attr("data-paySn");
                            toPay(e, "member_buy", "pay");
                            return false
                    });	
                            layer.open({type:2});				
                              $.post(cleartrach+"/" + new Date().toTimeString(),{'id':id},function(data){
                                    if(data.status){
                                            self.parent().hide()
                                            layer.open({content: data.info, time: 1});
                                    }else{
                                           
                                            layer.open({content: data.info, time: 1});
                                    }

                                 },'json');
                    } 
                } else {
                    k("充值金额输入有误");
                }
            }
        })
    };
    e();
});