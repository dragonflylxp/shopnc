<?php
/**
 * 邀请返利
 *
 * @User      noikiy
 * @File      invite.php
 * @Link     
 * @Copyright 
 */

use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');

class inviteControl extends BaseHomeControl
{
    public function indexOp(){
        Tpl::showpage('invite');
    }
}
