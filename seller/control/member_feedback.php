<?php

/**

 * 我的反馈

 *

 *

 *

 *

 */







defined('Inshopec') or exit('Access Invalid!');



class member_feedbackControl extends mobileMemberControl {



    public function __construct() {

        parent::__construct();

    }

    /**

    *反馈首页

    */

    public function indexOp(){

        Tpl::output('web_seo',C('site_name').' - '.'用户反馈');

        Tpl::showpage('member_feedback');

        

    }

    /**

     * 添加反馈

     */

    public function feedback_addOp() {

        $model_mb_feedback = Model('mb_feedback');



        $param = array();

        $param['content'] = $_POST['feedback'];

        $param['type'] = $this->member_info['client_type'];

        $param['ftime'] = TIMESTAMP;

        $param['member_id'] = $this->member_info['member_id'];

        $param['member_name'] = $this->member_info['member_name'];



        $result = $model_mb_feedback->addMbFeedback($param);



        if($result) {

            output_data('1');

        } else {

            output_error('保存失败');

        }

    }

}

