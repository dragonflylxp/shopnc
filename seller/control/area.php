<?php

/**

 * 地区

 *

 *

 *

 */



defined('Inshopec') or exit('Access Invalid!');

class areaControl extends mobileHomeControl{



    public function __construct() {

        parent::__construct();

    }



    public function indexOp() {

        $this->area_listOp();

    }



    /**

     * 地区列表

     */

    public function area_listOp() {

        $area_id = intval($_GET['area_id']);



        $model_area = Model('area');



        $condition = array();

        if($area_id > 0) {

            $condition['area_parent_id'] = $area_id;

        } else {

            $condition['area_deep'] = 1;

        }

        $area_list = $model_area->getAreaList($condition, 'area_id,area_name');

        output_data(array('area_list' => $area_list));

    }

    public function area_list_merchantOp() {

        $model_area = Model('merchant_area');

        $condition = array();

        if(!empty($_GET['area_id'])) {

            $condition['area_parent_code'] = $_GET['area_id'];

        } else {

            $condition['area_parent_code'] = 'CHN';

        }

        $area_list = $model_area->getAreaList($condition, 'area_code,area_name');

        output_data(array('area_list' => $area_list));

    }



}

