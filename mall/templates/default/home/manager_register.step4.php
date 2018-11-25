<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="explain"><i></i><?php echo $output['joinin_message'];?></div>

<div class="bottom">
    <?php if($output['btn_next']) { ?>
        <a id="next" class="btn">下一步</a>
    <?php } ?>
</div>
<script type="text/javascript">
    $("#next").click(function(){
        var _url = '<?php echo urlShop('manager_register','next');?>';
        $.post(_url,'',function(data){
            if(data.state){
                location.href = '<?php echo urlShop('manager_register','step0');?>';
            }else{
                alert(data.info);
            }
        },'json');
    })
</script>
