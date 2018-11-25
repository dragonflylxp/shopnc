<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
			<a class="back" href="index.php?con=manager_member&fun=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>绑定地区管理人</h3>
                <h5>设置每个地区管理人</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
        </div>
        <ul>
            <li>每个地区管理人从对应会员中选择</li>
            <li>每个所属地区节点管理人只能绑定一个，市级节点不予绑定</li>
       		<li>提示：请尽量在每月1号6点-24点之间绑定或修改管理人</li>
        </ul>
    </div>
  <form id="manager_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>选择管理人级别</label>
        </dt>
        <dd class="opt">
            <select name="grade" id="grade" class="input-txt" onchange="set_area(this);" >
                <option value="0">请选择级别</option>
                <option value="1">大区级</option>
                <option value="2">省级（直辖市）</option>
				<option value="3">市级</option>
                <option value="4">县级（区、县级市）</option>
            </select>
          <span class="err"></span>
          <p class="notic">选择管理人所属级别</p>
        </dd>
      </dl>
      <dl class="row">
          <dt class="tit">
            <label><em>*</em> 选择所绑定地区</label>
           </dt>
          <dd class="opt" >
            <select name="area" id="area"  class="input-txt" onchange="get_province(this);"  disabled='true' ">
                <option value="0">请选择大区</option>
                <option value="华北">华北</option>
                <option value="东北">东北</option>
                <option value="华东">华东</option>
                <option value="华南">华南</option>
                <option value="华中">华中</option>
                <option value="西南">西南</option>
                <option value="西北">西北</option>
                <option value="港澳台">港澳台</option>
                <option value="海外">海外</option>
            </select>
            <select name="province" id="province"  class="input-txt" onchange="get_city(this);" style="display:none;"></select>
            <select name="city" id="city"  class="input-txt" onchange="get_district(this);"  style="display:none;"></select>
            <select name="district" id="district"  class="input-txt"  style="display:none;"></select>
          <span class="err"></span>
          <p class="notic">管理人绑定地区，请选择级别后再选择地区</p>
          </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>选择地区管理人</label>
        </dt>
        <dd class="opt">
            <select name="uid" id="uid"  class="input-txt">
             <option value="0">请选择公司名</option>
            <?php foreach ($output['manager_list'] as $km=>$vm){ ?>
                <option value="<?php echo $vm['member_id'];?>"><?php echo $vm['company_name'];?></option>
            <?php }?>
            </select>
			<input type="text" class="like" placeholder="请输入公司名"/><input type="button" onclick="get_manager();" value="搜索"/>
          <span class="err"></span>
          <p class="notic">选择所绑定的管理人</p>
        </dd>
      </dl>
        <dl class="row">
        <dt class="tit">提成比例</dt>
        <dd class="opt">
          <input type="text" value="2.0" name="point" id="point" class="input-txt">%
          <span class="err"></span>
          <p class="notic">注意提成比例为0-100的数字</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script>
	$(function(){
		$("#submitBtn").click(function(){
			if(validate()){
			    if(!confirm('提示：如果此时绑定管理人，本月该地区所有订单提成都将属于该管理人，确认绑定吗？')){
			        return false;
			    }
				$("#manager_form").submit();
			}
		});
	});

	//异步获取管理人
	function get_manager(){
		var keyword = $(".like").val();
		if(!keyword){
			return;
		}
		$.getJSON('index.php?con=manager_member&fun=ajaxGetManager&keyword='+keyword, function(result){
			if(result){
				$("#uid").html(result);
			}else{
				$("#uid").html("");
			}
		});
	}

	//设置级别地区
	function set_area(obj){
		var grade = $(obj).val();
		if(grade==0){
			$("#area").attr("disabled",true).val(0).find("option:selected").text('请选择地区');
			$("#province").html("").hide();
			$("#city").html("").hide();
			$("#district").html("").hide();
		}else{
			$("#area").attr("disabled",false).val(0).find("option:selected").text('请选择地区');
			$("#province").html("").hide();
			$("#city").html("").hide();
			$("#district").html("").hide();
		}

	}
	

	function get_province(obj){
		var area_region = $(obj).val();
		var grade = $("#grade").val();
		$("#province").html("").hide();
		$("#city").html("").hide();
		$("#district").html("").hide();
		if(parseInt(grade)==1){
			return;
		}
	    $.getJSON('index.php?con=manager_member&fun=ajaxGetArea&area_region='+area_region, function(result){
	        if(result){
	           $("#province").html(result).show();
	        }else{
	        	 $("#province").hide();
	        }
	    });
	}
	function get_city(obj){
		var province = $(obj).val();
		var grade = $("#grade").val();
		$("#city").html("").hide();
		$("#district").html("").hide();
		if(parseInt(grade)<=2){
			return;
		}
	    $.getJSON('index.php?con=manager_member&fun=ajaxGetArea&province='+province, function(result){
	        if(result){
	           $("#city").html(result).show();
	        }else{
	        	 $("#city").hide();
	        }
	    });
	}
	function get_district(obj){
		var city = $(obj).val();
		var grade = $("#grade").val();
		$("#district").html("").hide();
		if(parseInt(grade)<=3){
			return;
		}
	    $.getJSON('index.php?con=manager_member&fun=ajaxGetArea&city='+city, function(result){
	        if(result){
	           $("#district").html(result).show();
	        }else{
	        	 $("#district").hide();
	        }
	    });
	}

	function validate(){
		var grade = $("#grade").val();
		var area_region = $("#area").val();
		var province = $("#province").val();
		var city = $("#city").val();
		var district = $("#district").val();
		var uid = $("#uid").val();
		var point = $("#point").val();
		if(grade==0){
			alert("请选择级别");
			return false;
		}else if(grade==1){
			if(area_region==0){
				alert("请选择大区");
				return false;
			}
		}else if(grade==2){
			if(area_region==0 || province==0){
				alert("请选择大区、省");
				return false;
			}
		}else if(grade==3){
			if(area_region==0 || province==0 || city==0){
				alert("请选择大区、省、市 ");
				return false;
			}
		}else if(grade==4){
			if(area_region==0 || province==0 || city==0 || district==0){
				alert("请选择大区、省、市、县");
				return false;
			}
		}
		if(uid==0){
			alert("请选择管理人");
			return false;
		}
		if(point<=0 || point>100){
			alert("提成只能为0.1-100之间");
			return false;
		}
		return true;
	}
</script> 
