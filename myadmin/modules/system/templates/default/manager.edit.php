
<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title"><a class="back" href="index.php?con=manager&fun=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>管理人管理 - 编辑管理人</h3>
				<h5>网站所有管理人索引及管理</h5>
			</div>
		</div>
	</div>
	<!-- 操作说明 -->
	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
			<h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
			<span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
		<ul>
			<li>平台管理员可对管理人进行增加、编辑和删除</li>
			<li>增加管理人将展示在管理人列表</li>
			<li>提示：绑定管理人之前需新增管理人；如果管理已绑定地区，请先解绑后才能删除</li>
		</ul>
	</div>
	<form id="user_form" enctype="multipart/form-data" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">
					<label for="member_name"><em>*</em>管理人账号</label>
				</dt>
				<dd class="opt">
					<?php echo $output['manager_info']['member_name'];?>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="member_passwd"><em>*</em>管理人密码</label>
				</dt>
				<dd class="opt">
					<input type="text" id="member_passwd" name="member_passwd" class="input-txt" >
					<span class="err"></span>
					<p class="notic">留空表示不修改密码</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="member_passwd"><em>*</em>管理人公司名</label>
				</dt>
				<dd class="opt">
					<input type="text" id="company_name" name="company_name" class="input-txt" value="<?php echo $output['manager_info']['company_name'];?>">
					<span class="err"></span>
					<p class="notic">管理人公司名</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="member_email"><em>*</em>管理人邮箱</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['manager_info']['member_email'];?>" id="member_email" name="member_email" class="input-txt" >
					<span class="err"></span>
					<p class="notic">请输入常用的邮箱，将用来找回密码、接受订单通知等。</p>
				</dd>
			</dl>
		</div>
		<input type="hidden" name="manager_id" value="<?php echo $output['manager_info']['manager_id'];?>" />
		<input type="hidden" name="member_id" value="<?php echo $output['manager_info']['member_id'];?>" />
		<div class="ncap-form-default">
			<div style="width:920px;margin: 10px 0px 10px 137px ;border: 1px solid #999;padding: 18px;">
				<p style="text-align: center;margin: 0px 0px 18px 0px;font-weight: bold;">以下为管理人提交内容</p>
				<dl class="row">
					<dd class="opt" style="width:300px;">
						公司名称：<?php echo $output['manager_info']['complete_company_name'];?>
					</dd>
					<dd class="opt"  style="width:300px;">
						公司电话：<?php echo $output['manager_info']['company_phone'];?>
					</dd>
					<dd class="opt"  style="width:300px;">
						公司地址：<?php echo $output['manager_info']['company_address'].$output['manager_info']['company_address_detail'];?>
					</dd>
				</dl>
				<dl class="row">
					<dd class="opt" style="width:300px;">
						法人姓名：<?php echo $output['manager_info']['legal_person_name'];?>
					</dd>
					<dd class="opt"  style="width:300px;">
						营业执照号：<?php echo _decrypt($output['manager_info']['business_licence_number']);?>
					</dd>
					<dd class="opt"  style="width:300px;">
						组织机构代码：<?php echo  _decrypt($output['manager_info']['organization_code']);?>
					</dd>
				</dl>
			</div>
			<div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
		</div>
	</form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
	//裁剪图片后返回接收函数
	function call_back(picname){
		$('#member_avatar').val(picname);
		$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname)
				.attr('onmouseover','toolTip("<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'>")');
	}
	$(function(){
		$('input[class="type-file-file"]').change(uploadChange);
		function uploadChange(){
			var filepath=$(this).val();
			var extStart=filepath.lastIndexOf(".");
			var ext=filepath.substring(extStart,filepath.length).toUpperCase();
			if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
				alert("file type error");
				$(this).attr('value','');
				return false;
			}
			if ($(this).val() == '') return false;
			ajaxFileUpload();
		}
		function ajaxFileUpload()
		{
			$.ajaxFileUpload
			(
					{
						url : '<?php echo ADMIN_SITE_URL?>/index.php?con=common&fun=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_AVATAR;?>',
						secureuri:false,
						fileElementId:'_pic',
						dataType: 'json',
						success: function (data, status)
						{
							if (data.status == 1){
								ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','<?php echo ADMIN_SITE_URL?>/index.php?con=common&fun=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&url='+data.url,690);
							}else{
								alert(data.msg);
							}
							$('input[class="type-file-file"]').bind('change',uploadChange);
						},
						error: function (data, status, e)
						{
							alert('上传失败');
							$('input[class="type-file-file"]').bind('change',uploadChange);
						}
					}
			)
		};
		//按钮先执行验证再提交表单
		$("#submitBtn").click(function(){
			if($("#user_form").valid()){
				$("#user_form").submit();
			}
		});
		$('#user_form').validate({
			errorPlacement: function(error, element){
				var error_td = element.parent('dd').children('span.err');
				error_td.append(error);
			},
			rules : {
				member_name: {
					required : true,
					minlength: 3,
					maxlength: 20,
					remote   : {
						url :'index.php?con=member&fun=ajax&branch=check_user_name',
						type:'get',
						data:{
							user_name : function(){
								return $('#member_name').val();
							},
							member_id : ''
						}
					}
				},
				member_company: {
					required : true,
					maxlength: 200,
					minlength: 1
				},
			},
			messages : {
				member_name: {
					required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_null']?>',
					maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_length']?>',
					minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_length']?>',
					remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_exists']?>'
				},
				member_passwd : {
					required : '<i class="fa fa-exclamation-circle"></i><?php echo '密码不能为空'; ?>',
					maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>',
					minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>'
				},
				company_name : {
					required : '<i class="fa fa-exclamation-circle"></i><?php echo '公司名不能为空'; ?>',
					maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>',
					minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>'
				},
			}
		});
	});
</script>
