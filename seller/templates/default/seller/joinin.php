<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

  body{

    background: #86cab6;

  }

  .xc_box{

    width: 100%;



    margin:0 auto;

     margin-top: 2rem;



  }

  .xc_box img{

    width: 100%;

  }

  .rzxy{

    width: 8rem;

    height:2rem;

    margin:0 auto;

  }

  .btn_box{

    width: 80%;

    height: 4rem;

    margin:0 auto;

    margin-top: 0.5rem;

  }

  .btn_box span{

    width: 45%;

    height: 2rem;

    line-height: 2rem;

    display: inline-block;

    float: left;

    text-align: center;

    margin-top: 1rem;

    color: #fff;

  }

  .btn_box span.people_rz{

    float: right;

  }

  .rzxy_info{

    font-size: 0.65rem;

  }

</style>

</head>

<body>

<div class="xc_box">

  <img src="<?php echo MOBILE_TEMPLATES_URL;?>/images/sjrz.png">

</div>

<div class="rzxy input-box">

  <label class="">

    <input class="checkbox" id="rzxyty" autocomplete="off" type="checkbox">

    <span class="power"><i></i></span> 

  </label>

  <span class="rzxy_info">同意入驻协议</span>

</div>

<div class="btn_box">

  <span class="store_rz">商家入驻</span>

  <span class="people_rz">个人入驻</span>

</div>





<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js?201511"></script>

<script>

  $(function(){

    $('.rzxy_info').click(function(){

      var lyopen = layer.open({type:2});

       $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=join&fun=ajax_zcxy",

            dataType:'json',

            success: function(data) {

                layer.close(lyopen);

                 layer.open({

                   type: 1,

                   title:'商家入驻协议',

                   content: '<div style="overflow:auto;height:300px;">'+data.datas+'</div>',

                   shadeClose: false,

                   style: 'width:' + ($(window).width() * 0.9) + 'px; max-height:' + ($(window).height() * 0.9) + 'px;border-radius:5px; border:none;text-align:center;padding:15px;',

                   yes: function(olayer) {

                      var cla = 'getElementsByClassName';

                      olayer[cla]('close')[0].onclick = function() {

                          layer.closeAll();

                   }

                  }

                })

            }

        })

    })

    $('.store_rz').click(function(){

         var ck =  $("#rzxyty").attr('checked');

         if(!ck){

            layer.open({content:'请同意注册协议!',time:1.5});

            return false;

         }

          

         window.location.href=ApiUrl + "/index.php?con=store_joinin&fun=step1";

         

    })

        $('.people_rz').click(function(){

         var ck =  $("#rzxyty").attr('checked');

         if(!ck){

            layer.open({content:'请同意注册协议!',time:1.5});

            return false;

         }

          

         window.location.href=ApiUrl + "/index.php?con=store_joinin_c2c";

         

    })

  })

</script>