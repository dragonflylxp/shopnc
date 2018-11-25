<?php
/**
 * 会员直播
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class member_liveControl extends mobileMemberControl {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 发送聊天记录
     */
    public function send_chatOp(){
        $live_id = intval($_POST['live_id']);
        if($live_id <= 0){
            output_error('参数错误');
        }
        $model = Model('dis_msg');
        $param = array();
        $param['live_id'] = $live_id;
        $param['member_id'] = $this->member_info['member_id'];
        $param['member_name'] = $this->member_info['member_name'];
        $param['msg_txt'] = trim($_POST['msg_txt']);
        $param['add_time'] = TIMESTAMP;
        $param['msg_state'] = 1;

        $live_info = Model('mb_video')->getMbVideoInfoByID($live_id);
        if($live_info['member_id'] != $this->member_info['member_id']){
            $param['msg_type'] = 1;
        } else {
            $param['msg_type'] = 2;
        }

        $table_name = $model->getMsgTableName($live_id);
        $res = $model->addMessage($table_name,$param);

        $condition = array();
        $condition['live_id'] = $live_id;
        $condition['msg_state'] = 1;
        $msg_list = $model->getTopMessage($table_name,$condition,5);
        output_data(array('send_stat' => intval($res), 'msg_list' => $msg_list));
    }

    /**
     * 获取聊天列表
     */
    public function get_chatOp(){
        $live_id = intval($_POST['live_id']);
        if($live_id <= 0){
            output_error('参数错误');
        }
        $msg_list = $this->_getChatList($live_id);
        output_data(array('msg_list' => $msg_list));
    }

    /**
     * 获取直播观看用户
     */
    public function live_memberOp(){
        $live_id = intval($_POST['live_id']);
        if($live_id <= 0){
            output_error('参数错误');
        }
        $member = $this->_getLiveMemberList($live_id);
        output_data(array('member_list' => $member['list'],'member_count' => $member['count']));
    }

    /**
     * 获取直播信息
     */
    public function live_infoOp(){
        $live_id = intval($_POST['live_id']);
        if($live_id <= 0){
            output_error('参数错误');
        }
        $return = array();
        $model_live = Model('dis_msg');
        $live_info = Model('mb_video')->getMbVideoInfoByID($live_id);
        if($live_info['member_id'] != $this->member_info['member_id']){
            $add_stat = $this->_addLiveLog($model_live, $live_id,-10);
        } else {
            $add_stat = 0;
        }
        if($add_stat >= 0){
            $member = $this->_getLiveMemberList($live_id);
            $return['member_list'] = $member['list'];
            $return['member_count'] = $member['count'];
            $goods = $this->_getLiveGoodsList($live_info['member_id']);
            $return['goods_list'] = $goods['list'];
            $return['goods_count'] = $goods['count'];
            $live_member_info = array();
            $live_member_info['member_id'] = $live_info['member_id'];
            $live_member_info['member_name'] = $live_info['member_name'];
            $live_member_info['member_avatar'] = getMemberAvatarForID($live_info['member_id']);
            $live_member_info['movie_play_url'] = getMbMoiveUrl($live_info['movie_rand'],'m3u8');
            $live_member_info['movie_cover_img'] = getMbMoiveImageUrl($live_info['movie_cover_img'],$live_info['member_id']);
            $live_member_info['movie_state'] = $live_info['movie_state'];
            $live_member_info['movie_title'] = $live_info['movie_title'];
            $return['live_member_info'] = $live_member_info;
            $return['live_stat_id'] = $add_stat;
            Model('mb_video')->editMbVideo(array('page_view',array('exp','page_view+1')),$live_id);
            output_data($return);
        }else{
            output_error('参数错误');
        }
    }

    /**
     * 退出直播观看
     */
    public function live_closeOp(){
        $live_id = intval($_POST['live_id']);
        if($live_id <= 0){
            output_error('参数错误');
        }
        $live_stat_id = intval($_POST['live_stat_id']);
        if($live_stat_id < 0){
            output_error('参数错误');
        }
        $model_live = Model('dis_msg');
        $this->_addLiveLog($model_live, $live_id, $live_stat_id);
        output_data('1');
    }

    /**
     * 获取商品规格
     */
    public function get_specOp(){
        $goods_commonid = intval($_POST['goods_commonid']);
        if($goods_commonid < 0){
            output_error('参数错误');
        }
        $dis_memberid = intval($_POST['dis_memberid']);
        if($dis_memberid < 0){
            output_error('参数错误');
        }
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;
        $goods = $model_goods->getGoodsInfo($condition);
        if(empty($goods)){
            output_error('商品不存在或已下架');
        }
        $condition['member_id'] = $dis_memberid;
        $dis_goods = Model('dis_goods')->getDistriGoodsInfo($condition);

        $goods_id = $goods['goods_id'];
        $goods_detail = $model_goods->getGoodsDetail($goods_id);
        $goods_info = $goods_detail['goods_info'];
        $return = array();
        $return['goods_id'] = $goods_info['goods_id'];
        $return['goods_name'] = $goods_info['goods_name'];
        $return['goods_price'] = $goods_info['promotion_price'] ? $goods_info['promotion_price'] : $goods_info['goods_price'];
        $return['goods_commonid'] = $goods_info['goods_commonid'];
        $return['spec_value'] = $goods_info['spec_value'];
        $return['spec_name'] = $goods_info['spec_name'];
        $return['goods_spec'] = $goods_info['goods_spec'];
        $return['distri_id'] = $dis_goods['distri_id'];
        $return['goods_img_url'] = cthumb($goods_info['goods_image'],'360',$goods_info['store_id']);
        $return['store_id'] = $goods_info['store_id'];
        $return['spec_list'] = $goods_detail['spec_list'];
        $return['spec_list_mobile'] = $goods_detail['spec_list_mobile'];
        $return['spec_image'] = $goods_detail['spec_image'];
        output_data($return);
    }

    /**
     * 获取商品信息
     */
    public function get_goodsOp(){
        $goods_id = intval($_POST['goods_id']);
        if($goods_id < 0){
            output_error('参数错误');
        }
        $dis_memberid = intval($_POST['dis_memberid']);
        if($dis_memberid < 0){
            output_error('参数错误');
        }
        $model_goods = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_id);
        $goods_info = $goods_detail['goods_info'];

        $condition = array();
        $condition['goods_commonid'] = $goods_info['goods_commonid'];
        $condition['member_id'] = $dis_memberid;
        $dis_goods = Model('dis_goods')->getDistriGoodsInfo($condition);

        $return = array();
        $return['goods_id'] = $goods_info['goods_id'];
        $return['goods_name'] = $goods_info['goods_name'];
        $return['goods_price'] = $goods_info['promotion_price'] ? $goods_info['promotion_price'] : $goods_info['goods_price'];
        $return['goods_commonid'] = $goods_info['goods_commonid'];
        $return['spec_value'] = $goods_info['spec_value'];
        $return['spec_name'] = $goods_info['spec_name'];
        $return['goods_spec'] = $goods_info['goods_spec'];
        $return['distri_id'] = $dis_goods['distri_id'];
        $return['goods_img_url'] = cthumb($goods_info['goods_image'],'360',$goods_info['store_id']);
        $return['store_id'] = $goods_info['store_id'];
        $return['spec_list'] = $goods_detail['spec_list'];
        $return['spec_list_mobile'] = $goods_detail['spec_list_mobile'];
        $return['spec_image'] = $goods_detail['spec_image'];
        output_data($return);
    }

    //获取聊天记录
    private function _getChatList($live_id){
        $model = Model('dis_msg');
        //确定统计分表名称
        $table_name = $model->getMsgTableName($live_id);
        $condition = array();
        $condition['live_id'] = $live_id;
        $condition['msg_state'] = 1;
        $msg_list = $model->getTopMessage($table_name,$condition,5);
        return $msg_list;
    }

    //获取会员列表
    private function _getLiveMemberList($live_id){
        $model = Model('dis_msg');
        $table_name = $model->getLiveTableName($live_id);
        $condition = array();
        $condition['live_id'] = $live_id;
        $condition['member_state'] = 1;
        $member_count = $model->getLiveMemberCount($table_name, $condition);
        $member_list = $model->getTopLiveMember($table_name, $condition, 10);
        $list = array();
        foreach($member_list as$v){
            $tmp = array();
            $tmp['member_id'] = $v['member_id'];
            $tmp['member_name'] = $v['member_name'];
            $tmp['member_avatar'] = getMemberAvatarForID($v['member_id']);
            $list[] = $tmp;
        }
        return array('list' => $list, 'count' => $member_count);
    }

    //获取直播商品列表
    private function _getLiveGoodsList($distri_member_id) {
        $model_goods = Model('dis_goods');
        $condition = array('member_id' => $distri_member_id);
        $condition['dis_goods.distri_goods_state'] = 1;
        $file = 'goods.*,dis_goods.distri_id,dis_goods.distri_time';
        $goods_list = $model_goods->getDistriGoodsInfoList($condition,$file,'','goods.goods_commonid desc','','goods.goods_commonid');
        $list = array();
        $goods_count = 0;
        foreach((array)$goods_list as $key => $value){
            $tmp = array();
            $tmp['distri_id'] = $value['distri_id'];
            $tmp['goods_id'] = $value['goods_id'];
            $tmp['goods_commonid'] = $value['goods_commonid'];
            $tmp['goods_name'] = $value['goods_name'];
            $tmp['goods_price'] = $value['goods_price'];
            $tmp['distri_time'] = $value['distri_time'];
            $tmp['store_name'] = $value['store_name'];
            $tmp['store_id'] = $value['store_id'];
            $tmp['goods_image_url'] = cthumb($value['goods_image'], 60,$value['store_id']);
            $list[$key] = $tmp;
            $goods_count++;
        }
        return array('list' => $list, 'count' => $goods_count);
    }

    //添加/更新访客记录
    private function _addLiveLog($model_live, $live_id, $stat = -10){
        $table_name = $model_live->getLiveTableName($live_id);
        if($stat == -10){
            $param = array();
            $param['live_id'] = $live_id;
            $param['member_id'] = $this->member_info['member_id'];
            $param['member_name'] = $this->member_info['member_name'];
            $param['add_time'] = TIMESTAMP;
            $param['member_state'] = 1;
            Model('mb_video')->editMbVideo(array('page_view',array('exp','page_view+1')),$live_id);
            return $model_live->addLiveLog($table_name,$param);
        }elseif($stat > 0){
            $param = array();
            $param['member_state'] = 0;
            $condition = array('log_id' => $stat);
            return $model_live->updateLiveLog($table_name, $condition, $param);
        }
    }
}