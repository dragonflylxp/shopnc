<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_cart.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-title">

      <h1>在线充值</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

  <div id="pd_count" class="nctouch-asset-info"></div>

    <!--底部总金额固定层End-->

  <div class="nctouch-bottom-mask">

    <div class="nctouch-bottom-mask-bg"></div>

    <div class="nctouch-bottom-mask-block">

      <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>

      <div class="nctouch-bottom-mask-top">

        <p class="nctouch-cart-num">本次在线充值需支付<em id="onlineTotal">0.00</em>元</p>

        <p style="display:none" id="isPayed"></p>

        <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a> </div>

      <div class="nctouch-inp-con nctouch-inp-cart">



        <div class="nctouch-pay">

          <div class="spacing-div"><span>在线支付方式</span></div>

          <div class="pay-sel">

            <label style="display:none">

              <input type="radio" name="payment_code" class="checkbox" id="alipay" autocomplete="off" />

              <span class="alipay">支付宝</span></label>

            <label style="display:none">

              <input type="radio" name="payment_code" class="checkbox" id="wxpay_jsapi" autocomplete="off" />

              <span class="wxpay">微信</span></label>

         <!--     <label style="display:none">

              <input type="radio" name="payment_code" class="checkbox" id="tenpay" autocomplete="off" />

              <span class="tenpay">财付通</span></label> -->

                    <label style="display:none">

              <input type="radio" name="payment_code" class="checkbox" id="jdpay" autocomplete="off" />

              <span class="jdpay">京东支付</span></label>

          </div>

        </div>

        <div class="pay-btn"> <a href="javascript:void(0);" id="toPay" class="btn-l">确认支付</a> </div>

      </div>

    </div>

  </div>

  <section class="clearfix g-member">

        <div class="g-Recharge">

            <ul id="ulOption">

                <li money="10"><a href="javascript:;" class="z-sel">10元<s></s></a></li>

                <li money="20"><a href="javascript:;">20元<s></s></a></li>

                <li money="30"><a href="javascript:;">30元<s></s></a></li>

                <li money="100"><a href="javascript:;">100元<s></s></a></li>

                <li money="200"><a href="javascript:;">200元<s></s></a></li>

                <li><b><input class="z-init" placeholder="输入金额" maxlength="8" type="text"><s></s></b></li>

            </ul>

        </div>

        <div class="gray6">我要充值<em class="orange">10.00</em>元</div>

        <div class="mt10 f-Recharge-btn mb20">

            <a id="btnSubmit" href="javascript:;" class="orgBtn">确认充值</a>

        </div>

        <div id="js-phone-code-tip">

        <h2 class="border_bottom">积分充值怎么换算？</h2>

        <p>1、积分 1元=1元</p>

        <p>2、填写的时候请填写整数eg.200</p>

        </div>

    

    </section>  

 

</div>



<script type="text/html" id="pd_count_model">

        <div class="container pre">

            <i class="icon"></i>

            <dl>

                <dt>预存款余额</dt>

                <dd>￥<em><%=predepoit;%></em></dd>

            </dl>

        </div>

</script> 



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.waypoints.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/order_payment_common.js"></script> 



<script>

var ajax_url = "<?php echo urlMobile('member_fund','ajax_recharge_order'); ?>";

    $(function(){

    var d = 10;

    var g = false;

    var a = null;

    var f = null;

    var b = null;

    var c = 1;



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

                        f.html('我要充值<em class="orange">' + l(m) + "</em>元")

                    }

                }

            } else {

                j = "";

                a.focus();

                f.html('我要充值<em class="orange">0.00</em>元')

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

                    f.html('我要充值<em class="orange">' + n.attr("money") + ".00</em>元")

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

       $("#btnSubmit").click(function on() {

            d = g ? a.val() : d;

            if (d == "" || parseInt(d) == 0) {

                k("请输入充值金额");

            } else {

                var m = /^[1-9]\d*\.?\d{0,2}$/;

                if (m.test(d)) {

                    if (c == 1 || c==2 ||c==3) {

                      var index = layer.open({type:2});               

                      $.post(ajax_url+"&t=" + new Date().toTimeString(),{'pdr_amount':d},function(data){

                            if(data.code==200){

                                layer.close(index);

                                var e = data.datas.pay_sn;

                                toPay(e, "member_fund", "pd_pay");

                                return false;

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



        var key = getCookie('key');

        if (!key) {

            window.location.href =  WapUrl + "/tmpl/member/login.html";

            return;

        }

  

        //获取预存款余额

        $.getJSON(ApiUrl + '/index.php?con=member_index&fun=my_asset', {'key':key,'fields':'predepoit'}, function(result){

            var html = template.render('pd_count_model', result.datas);

            $("#pd_count").html(html);



            $('#fixed_nav').waypoint(function() {

                $('#fixed_nav').toggleClass('fixed');

            }, {

                offset: '50'

            });

        });

    });

</script>

