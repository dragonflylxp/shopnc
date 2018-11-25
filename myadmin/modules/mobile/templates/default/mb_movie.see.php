<div id="playerzmblbkjP_wrapper"></div>

<script type="text/javascript">
        jwplayer("playerzmblbkjP_wrapper").setup({
            flashplayer: "<?php echo ADMIN_RESOURCE_URL;?>/js/jwpalyer/jwplayer.flash.swf",
            file: "<?php echo $output['movie_info']['play_url']; ?>",
            height: 600,
            width: 1000,
            controlbar:"none",
            autostart: true,
            primary: 'flash',
            dock: false
        });
</script>