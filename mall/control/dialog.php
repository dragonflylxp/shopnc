<?php

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class dialogControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        $this->album = Model('video_album');
    }
}    	