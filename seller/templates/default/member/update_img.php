<?php defined('Inshopec') or exit('Access Invalid!');?>



<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/jquery.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script src="<?php echo MOBILE_TEMPLATES_URL;?>/js/cropper.js" type="text/javascript"></script>
<script src="<?php echo MOBILE_TEMPLATES_URL;?>/js/lrz.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/cropper.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>修改用户头像</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

  <div class="tx-box">

 <div class="tx-toux" onclick="upload_img()"><img src="<?php echo $output['avator'];?>" height="140" width="140"></div>

    <div id="update-touxiang" onclick="upload_img()">更换头像</div>

    <input id="vip-file" style="position:absolute; top:235px; left:20%; opacity:0; display:block; width:210px; height:40px; " onchange="upload(this)" capture="camera" type="file">

  



    <script>

        $(function(){

          $("#header .top_home").on("click", function () { $("#header .home_menu").toggle(); });
        })
         function lackBack(){
                layer.closeAll();
        }

        //更换头像

        function upload_img() {
            $("#vip-file").trigger("click");
        }



        //图像压缩

        function upload(the) {
            lrz(the.files[0], { width: 640 })
                .then(function (rst) {
                    // 把处理的好的图片给用户看看呗
                    var img = new Image();
                    img.src = rst.base64;
                    img.onload = function () {
                        var load = layer.open({
                            type: 1,
                            shadeClose: false,
                             content: '<div class="container" style="width:100%; overflow:hidden"><div class="tx-head"><a href="#" id="img-save" style="float:right; font-size:16px; margin-right:15px; border:1px solid #5f5d5d; line-height:25px; padding:1px 8px; margin-top:8px;  border-radius:3px;">保存</a><a href="javascript:lackBack();"  class="new-a-back" id="backUrl"><span></span></a></div><img id="base64" src="' + rst.base64 + '"></div>',
                            style: 'width:100%; height:' + document.documentElement.clientHeight + 'px; background-color:#F2F2F2; border:none; overflow:hidden'
                        });

                        //裁剪框比例
                        $('#base64').cropper({
                            aspectRatio: 1 / 1,
                            crop: function (data) {
                            },
                            guides: false,  //是否在剪裁框上显示虚线
                            movable: false,  //是否允许移动剪裁框
                            resizable: false,  //是否允许改变剪裁框的大小
                            dragCrop: false,  //是否允许移除当前的剪裁框，并通过拖动来新建一个剪裁框区域
                            minContainerWidth: 300,  //容器的最小宽度
                            minContainerHeight: 300  //容器的最小高度
                        })



                        //保存裁剪图片

                        $("#img-save").click(function () {
                            var touxiang = $('#base64').cropper('getCroppedCanvas', { width: 300, height: 300 }).toDataURL("image/jpeg", 0.9);
                            var loading = layer.open({
                                type: 2,
                                shadeClose:false
                            });
                            console.log(touxiang);
                            // 这里该上传给后端啦
                            $.ajax({
                                url: ApiUrl+"/index.php?con=member_account&fun=ajax_update_img",
                                type: "post",
                                data: { img: touxiang},//base64数据
                                dataType: "json",
                                success: function (data) {
                                    layer.close(loading);
                                    layer.open({
                                        content: '头像上传成功！',
                                        time: 1.5
                                    });
                                    setTimeout(function () { window.location.href="<?php echo urlMobile('member_account','upload_img');?>"},1500);
                                },error:function(data){
                                      layer.open({
                                        content: '头像上传失败！',
                                        time: 1.5
                                    });
                                }
                            });  

                        })

                    };

                })

                .catch(function (err) {
                    // 万一出错了，这里可以捕捉到错误信息
                    // 而且以上的then都不会执行
                    layer.open({
                        content: err,
                        time: 2
                    });
                })
                .always(function () {
                    // 不管是成功失败，这里都会执行

                });

        };



    </script>



</div>

  

</div>