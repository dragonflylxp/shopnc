 

<?php

//在线咨询
//

use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');
class seller_chatControl extends mobileSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /*
    
    *在线咨询
    
    */
    public function indexOp() {
        Tpl::output('web_seo', C('site_name') . ' - ' . '在线咨询');
        Tpl::showpage('seller_chat_info');
    }
    public function chat_listOp() {
        Tpl::output('web_seo', C('site_name') . ' - ' . '消息列表');
        Tpl::showpage('seller_chat_list');
    }
    /**
     * 最近联系人
     */
    public function get_user_listOp() {

        $model_seller = Model('seller');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['seller_id'] = $_SESSION['seller_id'];
        $seller = $model_seller->getSellerInfo($condition);//账号
        $member_list = array();
        if ($seller['member_id'] > 0) {//验证商家账号
            $model_chat = Model('web_chat');
            $add_time_to = date("Y-m-d");
            $add_time_from = strtotime($add_time_to)-60*60*24*60;
            $add_time_to = strtotime($add_time_to);
            $condition = array();
            $condition['add_time'] = array('time',array($add_time_from,$add_time_to));
            $condition['f_id'] = $seller['member_id'];
            $member_list = $model_chat->getRecentList($condition,100,$member_list);
            $condition = array();
            $condition['add_time'] = array('time',array($add_time_from,$add_time_to));
            $condition['t_id'] = $seller['member_id'];
            $member_list = $model_chat->getRecentFromList($condition,100,$member_list);
        }
        
        $member_info = array();
        // $member_info = $model_chat->getMember($member_id);
        $node_info = array();
        $node_info['node_chat'] = C('node_chat');
        $node_info['node_site_url'] = NODE_SITE_URL;
        $node_info['resource_site_url'] = RESOURCE_SITE_URL;
        output_data(array(
            'node_info' => $node_info,
            'list' => $member_list
        ));
    }
    /**
     * 会员信息
     *
     */
    public function get_infoOp() {
        $val = '';
        $member = array();
        $model_chat = Model('web_chat');
        $types = array(
            'member_id',
            'member_name',
            'store_id',
            'member'
        );
        $key = $_POST['t'];
        $member_id = intval($_POST['u_id']);
        if ($member_id > 0 && trim($key) != '' && in_array($key, $types)) {
            $member_info = $model_chat->getMember($member_id);
            output_data(array(
                'member_info' => $member_info
            ));
        } else {
            output_error('参数错误');
        }
    }
    /**
     * 发消息
     *
     */
    public function send_msgOp() {
        // $member = array();
        // $model_chat = Model('web_chat');
        // $member_id = $this->member_info['member_id'];
        // $member_name = $this->member_info['member_name'];
        // $t_id = intval($_POST['t_id']);
        // $t_name = trim($_POST['t_name']);
        // $goods_id = trim($_POST['chat_goods_id']);
        // $member = $model_chat->getMember($t_id);
        // if ($t_name != $member['member_name']) output_error('接收消息会员账号错误');
        // $msg = array();
        // $msg['f_id'] = $member_id;
        // $msg['f_name'] = $member_name;
        // $msg['t_id'] = $t_id;
        // $msg['t_name'] = $t_name;
        // $msg['t_msg'] = trim($_POST['t_msg']);
        // if ($msg['t_msg'] != '') $chat_msg = $model_chat->addMsg($msg);
        // if ($chat_msg['m_id']) {
        //     $goods_id = intval($_POST['chat_goods_id']);
        //     if ($goods_id > 0) {
        //         $goods = $model_chat->getGoodsInfo($goods_id);
        //         $chat_msg['chat_goods'] = $goods;
        //     }
        //     output_data(array(
        //         'msg' => $chat_msg
        //     ));
        // } else {
        //     output_error('发送失败，请稍后重新发送');
        // }

            $member = array();
            $model_chat = Model('web_chat');
            if (empty($_POST)) $_POST = $_GET;
            $member_id = $_SESSION['member_id'];
            $member_name = $_SESSION['member_name'];
            $t_id = intval($_POST['t_id']);
            $t_name = trim($_POST['t_name']);
            if (($member_id < 1)) output_error('登录超时或者当前账号已退出'); 
            $member = $model_chat->getMember($t_id);
   
            if ($t_name != $member['member_name']) output_error('接收消息会员账号错误'); 
            $msg = array();
            $msg['f_id'] = $member_id;
            $msg['f_name'] = $member_name;
            $msg['t_id'] = $t_id;
            $msg['t_name'] = $t_name;
            $msg['t_msg'] = trim($_POST['t_msg']);
            if ($msg['t_msg'] != '') $chat_msg = $model_chat->addMsg($msg);
            if ($chat_msg['m_id']) {
                output_data(array(
                    'msg' => $chat_msg
                ));
            } else {
                output_error('发送失败，请稍后重新发送');
            }
    }
    /**
     * 商品图片和名称
     *
     */
    public function get_goods_infoOp() {
        $model_chat = Model('web_chat');
        $goods_id = intval($_POST['goods_id']);
        $goods = $model_chat->getGoodsInfo($goods_id);
        output_data(array(
            'goods' => $goods
        ));
    }
    /**
     * 聊天记录查询
     *
     */
    public function get_chat_logOp() {
        $member_id = $this->member_info['member_id'];
        $t_id = intval($_POST['t_id']);
        $add_time_to = date("Y-m-d");
        $time_from = array();
        $time_from['7'] = strtotime($add_time_to) - 60 * 60 * 24 * 7;
        $time_from['15'] = strtotime($add_time_to) - 60 * 60 * 24 * 15;
        $time_from['30'] = strtotime($add_time_to) - 60 * 60 * 24 * 30;
        $key = $_POST['t'];
        if (trim($key) != '' && array_key_exists($key, $time_from)) {
            $model_chat = Model('web_chat');
            $list = array();
            $condition_sql = " add_time >= '" . $time_from[$key] . "' ";
            $condition_sql.= " and ((f_id = '" . $member_id . "' and t_id = '" . $t_id . "') or (f_id = '" . $t_id . "' and t_id = '" . $member_id . "'))";
            $list = $model_chat->getLogList($condition_sql, $this->page);
            $total_page = $model_chat->gettotalpage();
            output_data(array(
                'list' => $list
            ) , mobile_page($total_page));
        }
    }
    /**
     * node信息
     *
     */
    public function get_node_infoOp() {
        $member_id = intval($_GET['u_id']);
        $model_chat = Model('web_chat');
        $member_info = $model_chat->getMember($member_id);
        $data = array();
        $data['node_chat'] = true;
        $data['node_site_url'] = NODE_SITE_URL;
        $data['resource_site_url'] = RESOURCE_SITE_URL;
        $data['member_info'] = $member_info;
        $model_chat = Model('web_chat');
        $model_store = Model('store');
        $store_info = $model_store->getStoreInfoByID($_SESSION['store_id']);
        $store_info['member_avatar'] = $member_info['member_avatar'];
        $store_info['store_avatar'] = getStoreLogo($store_info['store_avatar']);
        $data['user_info'] = $store_info;
        output_data($data);
    }
    /**
     * 未读消息记录总数
     */
    public function get_msg_countOp() {
        $model_chat = Model('web_chat');
        $where['t_id'] = $this->seller_info['member_id'];
        $where['r_state'] = '2';
        $MsgCount = $model_chat->getChatMsgCount($where);
        output_data($MsgCount);
    }
    /**
     * 删除最近联系人消息
     *
     */
    public function del_msgOp() {
        $model_chat = Model('web_chat');
        $member_id = $_SESSION['store_id'];
        $t_id = intval($_POST['t_id']);
        $condition = array();
        $condition['f_id'] = $t_id;
        $condition['t_id'] = $member_id;
        $model_chat->delChatMsg($condition);
        // $condition = array();
        // $condition['t_id'] = $t_id;
        // $condition['f_id'] = $member_id;
        // $model_chat->delChatMsg($condition);
        output_data(1);
    }
}

