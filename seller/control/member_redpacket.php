<?php
/**
 * 我的红包
 */
defined('Inshopec') or exit('Access Invalid!');
class member_redpacketControl extends mobileMemberControl
{
    public function __construct()
    {
        parent::__construct();
        //判断系统是否开启红包功能
        if (C('redpacket_allow') != 1) {
            showMessage('系统未开启红包功能', urlMobile('login'), 'html', 'error');
        }
        $model_redpacket = Model('redpacket');
        $this->redpacket_state_arr = $model_redpacket->getRedpacketState();
    }
    /*
     *红包首页
     */
    public function indexOp()
    {
        Tpl::output('web_seo', C('site_name') . ' - ' . '我的红包');
        Tpl::showpage('redpacket_list');
    }
    /*
     *領取紅包
     */
    public function redpacket_pwexOp()
    {
        Tpl::output('web_seo', C('site_name') . ' - ' . '领取红包');
        Tpl::showpage('redpacket_pwex');
    }
    /**
     * 红包列表
     */
    public function redpacket_listOp()
    {
        $condition = array();
        $model_redpacket = Model('redpacket');
        //更新红包过期状态
        $model_redpacket->updateRedpacketExpire($this->member_info['member_id']);
        //查询红包
        $where = array();
        $where['rpacket_owner_id'] = $this->member_info['member_id'];
        $rp_state_select = trim($_GET['rp_state_select']);
        if ($rp_state_select) {
            $where['rpacket_state'] = $this->redpacket_state_arr[$rp_state_select]['sign'];
        }
        $list = $model_redpacket->getRedpacketList($where, '*', 0, 10, 'rpacket_id desc');
        foreach ($list as $key => $value) {
            $list[$key]['rpacket_end_date_text'] = date('Y-m-d H:i:s', $value['rpacket_end_date']);
        }
        $page_count = $model_redpacket->gettotalpage();
        output_data(array('redpacket_list' => $list), mobile_page($page_count));
    }
    /**
     * 领取红包
     */
    public function rp_pwexOp()
    {
        if ($this->member_info['member_id']) {
            if (!$this->check()) {
                output_error('验证码错误！');
            }
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(array("input" => $_POST["pwd_code"], "require" => "true", "message" => '请输入红包卡密'));
            $error = $obj_validate->validate();
            if ($error != '') {
                output_error($error);
            }
            //查询红包
            $model_redpacket = Model('redpacket');
            $where = array();
            $where['rpacket_pwd'] = md5($_POST["pwd_code"]);
            $redpacket_info = $model_redpacket->getRedpacketInfo($where);
            if (!$redpacket_info) {
                output_error('红包卡密错误');
            }
            if ($redpacket_info['rpacket_owner_id'] > 0) {
                output_error('该红包卡密已被使用，不可重复领取');
            }
            $where = array();
            $where['rpacket_id'] = $redpacket_info['rpacket_id'];
            $update_arr = array();
            $update_arr['rpacket_owner_id'] = $this->member_info['member_id'];
            $update_arr['rpacket_owner_name'] = $this->member_info['member_name'];
            $update_arr['rpacket_active_date'] = time();
            $result = $model_redpacket->editRedpacket($where, $update_arr, $this->member_info['member_id']);
            if ($result) {
                //更新红包模板
                $update_arr = array();
                $update_arr['rpacket_t_giveout'] = array('exp', 'rpacket_t_giveout+1');
                $model_redpacket->editRptTemplate(array('rpacket_t_id' => $redpacket_info['rpacket_t_id']), $update_arr);
                output_data('红包领取成功');
            } else {
                output_error('红包领取失败');
            }
        } else {
            output_error('请登录！');
        }
    }
    /**
     * AJAX验证
     *
     */
    protected function check()
    {
        if (checkSeccode($_POST['codekey'], $_POST['captcha'])) {
            return true;
        } else {
            return false;
        }
    }

        /**
     * 领取红包
     */
    public function getpackOp(){
        $model = Model();

        $id = intval(trim($_GET['id']));

        //查询用户信息
        $member_model = Model('member');
        $member = $member_model->getMemberInfo(array('member_id'=>$this->member_info['member_id']));
        if(empty($member)){
            output_error('该用户不存在');
        }

        //检查红包状态
        $packet = $model->table('red_packet')->where(array('id'=>$id))->find();
        if($packet['state']==2){
            output_error('该红包活动已结束');
        }
        if(time() > $packet['end_time']){
            output_error('该红包活动已结束');
        }
        if(time() < $packet['start_time']){
            output_error('该红包活动未开始');
        }
        if($packet['packet_number'] == $packet['packet_numbered']){
            output_error('手慢了，红包派送完了');
        }

        //判断该用户是否已经领取
        $rec = $model->table('red_packet_rec')->where(array('packet_id'=>$id,'member_id'=>$member['member_id']))->find();
        if(empty($rec)){
            //查询中奖几率
            $rand = rand(1,100);
            if($rand <= $packet['win_rate']){
                /**
                 * 中奖操作
                 */
                $win_rec = $model->table('red_packet_list')->where(array('packet_id'=>$packet['id']))->find();
                
                //增加会员预存款
                $member_where  = array('member_id'=>$member['member_id']);
                $member_update = array('available_predeposit'=>array('exp','available_predeposit+'.$win_rec['packet_price']));
                $member_model->editMember($member_where,$member_update);

                //添加预存款日志
                $pd_log = array(
                    'lg_member_id'     => $member['member_id'],
                    'lg_member_name'   => $member['member_name'],
                    'lg_type'          => 'red_packet',
                    'lg_av_amount'     => +$win_rec['packet_price'],
                    'lg_freeze_amount' => 0,
                    'lg_add_time'      => time(),
                    'lg_desc'          => '用户参与红包活动：'.$packet['packet_name'],
                );
                $model->table('pd_log')->insert($pd_log);

                //添加用户领取红包记录
                if(!empty($packet['valid_date'])){
                    $valid_date = $packet['valid_date'];
                }else{
                    $a = date('Y-m-d',time());
                    $b = strtotime($a.' 23:59:59');
                    $valid_date = $b + 86400*($packet['valid_date2']-1);
                }

                $rec_log = array(
                    'packet_id'     => $packet['id'],
                    'packet_name'   => $packet['packet_name'],
                    'member_id'     => $member['member_id'],
                    'member_name'   => $member['member_name'],
                    'packet_price'  => $win_rec['packet_price'],
                    'add_time'      => time(),
                    'valid_date'    => $valid_date,
                    'is_use'        => 2,
                );
                $model->table('red_packet_rec')->insert($rec_log);

                //增加领取红包数量
                $model->table('red_packet')->where(array('id'=>$packet['id']))->update(array('packet_numbered'=>array('exp','packet_numbered+1')));

                //删除小红包记录
                $model->table('red_packet_list')->where(array('id'=>$win_rec['id']))->delete();

                //添加用户抽奖标记
                output_data(array('packet_price' => $win_rec['packet_price'],'is_packet'=>1, 'valid_date'=>date("Y-m-d H:i:s")));
            }else{
                $rec_log = array(
                    'packet_id'     => $packet['id'],
                    'packet_name'   => $packet['packet_name'],
                    'member_id'     => $member['member_id'],
                    'member_name'   => $member['member_name'],
                    'packet_price'  => '0.00',
                    'add_time'      => time(),
                    'is_use'        => 1,
                    'use_time'      => time(),
                );
                $model->table('red_packet_rec')->insert($rec_log);
                
                output_error('真可惜，红包与您擦肩而过~');
            }
        }else{
            output_error('您已参加过此次活动~');
        }

    }
}