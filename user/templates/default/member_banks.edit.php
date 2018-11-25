<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
    .hidden{
        display: none;
    }
</style>
<div class="eject_con">
  <div class="adds">
    <div id="warning"></div>
    <form method="post" action="<?php echo MEMBER_SITE_URL;?>/index.php?con=member_banks&fun=banks" id="banks_form" target="_parent">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="id" value="<?php echo $output['banks_info']['ID'];?>" />
      <input type="hidden" name="bank_name" id="bank_name" value="<?php echo $output['banks_info']['BANK_NAME'];?>" />
        <input type="hidden" name="bank_branch" id="bank_branch" value="<?php echo $output['banks_info']['BRANCH_NAME'];?>" />
        <input type="hidden" name="bank_province_1" id="bank_province_1" value="<?php echo $output['banks_info']['PROVINCE'];?>" />
        <input type="hidden" name="bank_city_1" id="bank_city_1" value="<?php echo $output['banks_info']['CITY'];?>" />
        <input type="hidden" name="bank_info_id_1" id="bank_info_id_1" value="<?php echo $output['banks_info']['BANK_INFO_ID'];?>" />

      <dl>
        <dt><i class="required">*</i><?php echo $lang['member_banks_card'].$lang['nc_colon'];?></dt>
        <dd>
          <input class="text w150" name="bank_card" value="<?php echo _decrypt($output['banks_info']['BANK_CARD']);?>"/>
            <p class="hint">请添加本人银行卡号</p>
        </dd>
      </dl>
        <dl>
            <dt><i class="required">*</i><?php echo $lang['member_banks_name'].$lang['nc_colon'];?></dt>
            <dd>
                <select class="text w150" name="bank_code" id="bank_code">
                    <option value=""><?php echo $lang['member_banks_info_choice'];?></option>
                    <?php
                        $htm = "";
                        foreach($output['banks_list'] as $k=>$bank){
                            if($output['banks_info']['BANK_CODE'] == $bank['bank_code']){
                                $select = "selected";
                            }else{
                                $select = "";
                            }
                            $htm .="<option ".$select." value='".$bank['bank_code']."'>".$bank['bank_name'].'</option>';
                        }
                        echo $htm;
                    ?>
                </select>
            </dd>
        </dl>

        <dl>
            <dt><i class="required">*</i><?php echo $lang['member_banks_branch_name'].$lang['nc_colon'];?></dt>
            <dd>
                <select data-deep="2" class="text w80 b_location <?php if($output['banks_info']['ID']>0){echo 'hidden';}?>" name="bank_province" id="bank_province">
                    <option value=''><?php echo $lang['member_banks_info_choice'];?></option>
                </select>
                <select data-deep="3" class="text w100 b_location hidden" name="bank_city" id="bank_city">
                    <option value=''><?php echo $lang['member_banks_info_choice'];?></option>
                </select>
                <select data-deep="4" class="text w110 b_location hidden" name="bank_info_id" id="bank_info_id">
                    <option value=''><?php echo $lang['member_banks_info_choice'];?></option>
                </select>

                <?php if($output['banks_info']['ID']>0){
                    $htm = '<span class="bank_edit_about">'.$output['banks_info']['PROVINCE'].' '.$output['banks_info']['CITY'].' '.$output['banks_info']['BRANCH_NAME'].'</span>';
                    $htm .='&nbsp;<input type="button" class="bank_edit_btn bank_edit_about" value="编辑">';
                    echo $htm;
                }?>

            </dd>
        </dl>

      <dl>
        <dt><em class="pngFix"></em>设为默认选择<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="checkbox" class="checkbox vm mr5" <?php if ($output['banks_info']['USED']) echo 'checked';?> name="is_default" id="is_default" value="1" />
          <label for="is_default">设置为默认选择</label>
        </dd>
      </dl>

        <dl>
            <dt>&nbsp;</dt>
            <dd>
                <p class="hint">
                    1、开户人为会员真实姓名。<br/>2、只能添加本人银行卡。<br/>3、每个会员最多添加10张银行卡。
                </p>
            </dd>
        </dl>

      <div class="bottom">
        <label class="submit-border">
          <input type="submit" class="submit" value="<?php if($output['type'] == 'add'){?><?php echo $lang['member_banks_new_card'];?><?php }else{?><?php echo $lang['member_banks_edit_card'];?><?php }?>" />
        </label>
        <a class="ncbtn ml5" href="javascript:DialogManager.close('my_banks_edit');">取消</a> </div>
    </form>
  </div>
</div>

<script type="text/javascript">
var SITEURL   = "<?php echo SHOP_SITE_URL; ?>";
var bank_code = "<?php echo $output['banks_info']['BANK_CODE']; ?>";
var bank_id   = "<?php echo $output['banks_info']['ID']; ?>";

$(document).ready(function(){

    if(bank_id != "" && bank_id>=1  && bank_code != "") {        //为编辑，赋值,获取 省列表

        getNextBank(bank_code,1,bank_code);
    }

    //编辑支行按钮
    $(".bank_edit_btn").click(function(){
        if(bank_id != "" && bank_id>=1  && bank_code != ""){
            $(".bank_edit_about").hide();
            $("#bank_province").show();
            $("#bank_info_id_1").val('');
        }
    });

    //加载银行列表
    $("#bank_code").change(function(){
        bank_code = $(this).val();
        var bank_name = $(this).find("option:selected").text();
        $("#bank_name").val(bank_name);
        $("#bank_province,#bank_city,#bank_info_id,#bank_branch").val("");
        $("#bank_city,#bank_info_id").hide();
        $(".b_location").val("");
        if(bank_code != ""){
            getNextBank(bank_code,1,"");
        }
        if(bank_id != "" && bank_id>=1 ){       //编辑支行
            $(".bank_edit_about").hide();
            $("#bank_province").show();
            $("#bank_info_id_1").val('');
        }
    });
    //开户行联动
    $(".b_location").change(function(){
        var obj  = $(this);
        var deep = obj.attr('data-deep');
        var pro_city = obj.val();
        change_val(pro_city,deep,obj);
    });


	$('#banks_form').validate({
    	submitHandler:function(form){
    		ajaxpost('banks_form', '', '', 'onerror');
    	},
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        rules : {
            bank_card : {
                required : true,
                minlength : 16,
                maxlength : 24
            },
            bank_code : {
                required : true
            },
            bank_info_id_1 : {
                required: true
            }
        },
        messages : {
            bank_card : {
                required : '<?php echo $lang['member_banks_card_input_receiver'];?>',
                minlength : '<?php echo $lang['member_banks_card_input_receiver'];?>',
                maxlength : '<?php echo $lang['member_banks_card_input_receiver'];?>'
            },
            bank_code : {
                required : '<?php echo $lang['member_banks_code_input_receiver'];?>'
            },
            bank_info_id_1 : {
                required : '<?php echo $lang['member_banks_branch_input_receiver'];?>'
            }
        }
    });
});

    //支行联动值改变时调用
    function change_val(pro_city,deep,obj){
        if(pro_city == ""){
            if(deep == 2){
                $("#bank_city,#bank_info_id").hide();
            }else if(deep == 3){
                $("#bank_info_id").hide();
            }
            return false;
        }else{
            if(deep == 2 || deep == 3 || deep ==1){
                if(deep == 2){
                    $("#bank_city,#bank_branch,#bank_info_id").val("");
                    $("#bank_info_id").hide();
                    $("#bank_province_1").val($("#bank_province").val());
                }else if(deep == 3){
                    $("#bank_branch,#bank_info_id").val("");
                    $("#bank_city_1").val($("#bank_city").val());
                }
                getNextBank(bank_code,deep,pro_city);
            }else{          //等于4的时候赋值
                $("#bank_branch").val(obj.find("option:selected").text());
                $("#bank_info_id_1").val(obj.val());
            }
        }
    }

    //获取支行信息
    function getNextBank(bank_code,deep,pro_city=""){
        if(deep <0 || deep >3 || bank_code == ""){
            showDialog('数据错误', 'error','','','','','','','',2);
            return false;
        }
        url = "<?php echo MEMBER_SITE_URL;?>/index.php?con=member_banks&fun=bank_next_list&bank_code="+bank_code+"&deep="+deep;
        if(deep ==2){
            if(pro_city== ""){
                showDialog('数据错误', 'error','','','','','','','',2);
                return false;
            }
            url +="&province="+pro_city;
        }else if(deep == 3){
            if(pro_city== ""){
                showDialog('数据错误', 'error','','','','','','','',2);
                return false;
            }
            url +="&city="+pro_city;
        }
        $.get(url,function(data) {
            if(data.status=='200'){
                var d_list   = data.list;
                var d_list_l = d_list.length;
                if(d_list_l <1){
                    showDialog('数据为空', 'error','','','','','','','',2);
                    return false;
                }
                var htm = "<option value=''>-选择-</option>";
                if(deep == 1){      //此处获取 省列表
                    for(var i =0;i<d_list_l;i++){
                        htm +="<option value='"+d_list[i]['province']+"'>"+d_list[i]['province']+"</option>";
                    }
                    $("#bank_province").html(htm);
                    //$("#bank_city,#bank_branch,#bank_info_id").val("");
                }else if(deep == 2){    //此处获取 市列表
                    for(var i =0;i<d_list.length;i++){
                        htm +="<option value='"+d_list[i]['city']+"'>"+d_list[i]['city']+"</option>";
                    }
                    $("#bank_city").html(htm);
                    $("#bank_city").show();

                }else if(deep == 3){    //获取 支行
                    for(var i =0;i<d_list.length;i++){
                        htm +="<option value='"+d_list[i]['id']+"'>"+d_list[i]['branch_name']+"</option>";
                    }
                    $("#bank_info_id").html(htm);
                    $("#bank_info_id").show();

                }
                return true;
            }else{
                showDialog(data.msg, 'error','','','','','','','',2);
                return false;
            }
        },'json');
    }
</script>