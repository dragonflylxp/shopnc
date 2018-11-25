<?php
/**
 * 管理员权限组
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
class gadminModel extends Model{
    public function __construct() {
        parent::__construct('gadmin');
    }

    /**
     * 根据id查询后台管理员权限组
     * @param int $id
     * @return array
     */
    public function getGadminInfoById($id) {
        return $this->where(array('gid' => $id))->find();
    }
}
