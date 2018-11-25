<?php defined('Inshopec') or exit('Access Invalid!');?>



<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/rzxy.css">

<style type="text/css">

.btnselect{

display: inline-block;

height: 0.9rem;

padding: 0.25rem 0.5rem;

font-size: 0.55rem;

color: #888;

line-height: 0.9rem;

background: #FFF;

border: solid 0.05rem #EEE;

border-radius: 0.15rem;

}

.current {

padding: 0.28rem 0.53rem;

color: #FFF;

background: #0094DE;

border: none;

}

</style>

</head>

<body>



  <?php require('store_joinin_c2c_apply.'.$output['sub_step'].'.php'); ?>

