<?php

/**

*推广码

 */









defined('Inshopec') or exit('Access Invalid!');



class member_inviteControl extends mobileMemberControl {





    public function __construct(){

        parent::__construct();

    }

    public function indexOp() {

    	$memberid = $this->member_info['member_id'];

    	Tpl::output('memberid',$memberid);

    	Tpl::output('web_seo',C('site_name').' - '.'我的推广连接');

        Tpl::showpage('invite_code');

    }

	

    /**

     * 获取一级会员佣金列表

     */

    public function inviteoneOp() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_one'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);     

		

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = $member_model->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = $member_model->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }

   /**

     * 获取二级会员佣金列表

     */

    public function invitetwoOp() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_two'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = $member_model->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = $member_model->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }

	

  /**

     * 获取三级会员佣金列表

     */

    public function invitethirOp() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_three'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = $member_model->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = $member_model->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }

	/*

	*生成推广二维码

	*/

	public function maker_qrcodeOp()

	{

		$memberid = $this->member_info['member_id'];

        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');

        $PhpQRCode = new PhpQRCode();

        $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_MALBUM.DS);

	

		//生成推广二维码

		$qrcode_url=WAP_SITE_URL . '/index.php?con=register&fun=index&inviterid='.$memberid;

		$PhpQRCode->set('date',$qrcode_url);

		$PhpQRCode->set('pngTempName', $memberid . '_member.png');

		$PhpQRCode->init();
         
		 $file = BASE_UPLOAD_PATH.DS.ATTACH_MALBUM.DS.$memberid . '_member.png';
		if(file_exists($file)){
			$urld = UPLOAD_SITE_URL.'/'.'shop'.'/'.'member'.'/'.$memberid . '_member.png';
			output_data(array('urld' => $urld));
		}else{
			output_data(array('urld' => 'NULL'));
		}
        

	}



}

