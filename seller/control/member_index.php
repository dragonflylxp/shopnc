<?php

/**

 * 我的商城

 *

 *

 *

 *

 */







defined('Inshopec') or exit('Access Invalid!');



class member_indexControl extends mobileMemberControl {



    public function __construct(){

        parent::__construct();

    }



    /**

     * 我的商城

     */

    public function indexOp() {

        $member_info = array();

		$member_info = $this->getMemberAndGradeInfo(true);

        $member_info['user_name'] = $this->member_info['member_name'];

        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);

        $member_info['point'] = $this->member_info['member_points'];

        $member_info['predepoit'] = $this->member_info['available_predeposit'];

        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];

		$member_info['level_name'] = $this->member_info['level_name'];		

		$favorites_model = Model('favorites');

		$member_info['favorites_store'] = $favorites_model->getStoreFavoritesCountByStoreId('',$this->member_info['member_id']);//店铺收藏数

		$member_info['favorites_goods'] = $favorites_model->getGoodsFavoritesCountByGoodsId('',$this->member_info['member_id']);//商品收藏数

        output_data(array('member_info' => $member_info));

    }

	

	/**

     * 我的积分

     */

    public function my_assetOp() {

		$member_info = $this->getMemberAndGradeInfo(true);

		$point = $this->member_info['member_points'];

		$predepoit = $this->member_info['available_predeposit'];

		$balance = $this->member_info['available_rc_balance'];

		$voucher =  Model('voucher')->getCurrentAvailableVoucherCount($this->member_info['member_id']); //取得当前有效代金券数量

		$redpacket =  Model('redpacket')->getCurrentAvailableRedpacketCount($this->member_info['member_id']); //取得当前有效红包数量



		if($_GET["fields"]=='predepoit'){

			output_data(array('predepoit' => $predepoit));

		}elseif($_GET["fields"]=='available_rc_balance'){

			output_data(array('available_rc_balance' => $balance));

		}else{

			output_data(array('point' => $point,'predepoit'=>$predepoit,'available_rc_balance'=>$balance,'redpacket'=>$redpacket,'voucher'=>$voucher));

		}

	}

	protected function getMemberAndGradeInfo($is_return = false){

        $member_info = array();

        //会员详情及会员级别处理

        if($this->member_info['member_id']) {

            $model_member = Model('member');

            $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

            if ($member_info){

                $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));

                $member_info = array_merge($member_info,$member_gradeinfo);

                $member_info['security_level'] = $model_member->getMemberSecurityLevel($member_info);

            }

        }

        if ($is_return == true){//返回会员信息

            return $member_info;

        } else {//输出会员信息

            Tpl::output('member_info',$member_info);

        }

    }

    /**

    *获取我的头像

    */

    // public function ajax_getimgOp(){

    //     $model_mb_user_token = Model('mb_user_token');

    //     $key = $_POST['key'];

    //     $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);



    //     if(empty($mb_user_token_info)) {

    //         echo json_encode(array('status'=>2));

    //         exit();

    //     }

    //     $infos = Model('member')->where(array('member_id'=>$mb_user_token_info['member_id']))->find();

    //     $data['member_avatar'] = getMemberAvatar($infos['member_avatar']);

    //     $data['member_id'] = $mb_user_token_info['member_id'];

    //     echo json_encode(array('status'=>1,'data'=>$data));

  



    // }



    /*

    *获取我的头像

    */

    public function ajax_update_imgOp(){

  

            $key = $_POST['key'];

            $img = $_POST['img'];

            $model_mb_user_token = Model('mb_user_token');

            $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);

            $member_id = $mb_user_token_info['member_id'];

            $file['list']=BASE_UPLOAD_PATH.DS.ATTACH_AVATAR;

            $member_info = Model('member')->getMemberInfoByID($member_id,'member_avatar');

          

            if ($member_info['member_avatar']) {

                $src = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$member_info['member_avatar'];

                @unlink($src);

            }

            if (!file_exists($file['list'])) {

                mkdir($file['list'],0777,true);

            }

            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)){

              $type = $result[2];

              $filename = $file['list']."/avatar_{$member_id}.jpg";

              file_put_contents($filename, base64_decode(str_replace($result[1], '', $img)));

            }

           

            $info = explode('/', $filename);

            $member_avatar = $info[count($info)-1];



            

          

            $up = Model('member')->editMember(array('member_id'=>$member_id),array('member_avatar'=>$member_avatar));

               

            echo json_encode(array('status'=>1,'info'=>'头像上传成功!','img'=>$member_avatar));

            

    }



}

