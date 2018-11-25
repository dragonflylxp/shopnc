<?php

/**

 * 用户注册

 *

 *

 *

 */

defined('Inshopec') or exit('Access Invalid!');

class registerControl extends BaseLoginControl {

	public function __construct() {

		parent::__construct();

		if (!empty($_SESSION['is_login'])) {

			@header('location: index.php?con=member');
			exit ;

		}

	}

	/*

	 *注册首页

	 */

	public function indexOp() {
		if (!empty($_GET['inviterid'])) {
			$rec = $_GET['inviterid'];
			$_SESSION['inviter_id'] = $rec;
		}

		/**
		 * 邀请注册 20160906
		 */
		$inviter_id = intval($_GET['inviterid']);
		$model_member = Model('member');
		$inviter_info = $model_member -> getMemberInfoByID($inviter_id);
		$inviter_name = $inviter_info['member_name'];
		setNcCookie('inviter_id', $inviter_id);
		setNcCookie('inviter_name', $inviter_name);
		Tpl::output('web_seo', C('site_name') . ' - ' . '用户注册');

		Tpl::showpage('register');

	}

	/*

	 *注册首页

	 */

	public function register_inviteOp() {

		if (!empty($_GET['inviterid'])) {
			$rec = $_GET['inviterid'];
			$_SESSION['inviter_id'] = $rec;
		}

		Tpl::output('web_seo', C('site_name') . ' - ' . '用户注册');

		Tpl::showpage('register_invite');

	}

	/*

	 *会员协议

	 */

	public function agreementOp() {

		$s_document = Model() -> table('document') -> where(array('doc_id' => 1)) -> find();

		output_data($s_document);

	}

	/*

	 *会员注册

	 */

	public function runregisterOp() {

		$model_member = Model('member');

		$register_info = array();

		$register_info['username'] = $_POST['username'];

		$register_info['password'] = $_POST['password'];

		$register_info['password_confirm'] = $_POST['password_confirm'];

		$register_info['email'] = $_POST['email'];
		if (!empty($_SESSION['inviter_id'])) {
			$register_info['inviter_id'] = $_SESSION['inviter_id'];
		}

		$member_info = $model_member -> register($register_info);

		if (!isset($member_info['error'])) {

			$model_member -> createSession($member_info);

			$token = $this -> _get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);

			if ($token) {

				$_SESSION['key'] = $token;

				output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));

			} else {

				output_error('注册失败');

			}

		} else {

			output_error($member_info['error']);

		}

	}

	/**

	 * 注册

	 */

	public function register_invite_runOp() {

		$model_member = Model('member');

		// p($_POST);die;

		//运维三级邀请条件调用

		if (!empty($_POST['invite_id'])) {

			$rec_id = $_POST['invite_id'];

			$member = $model_member -> getMemberInfo(array('member_id' => $rec_id));

			$invite_one = $rec_id;

			$invite_two = $member['invite_one'];

			$invite_three = $member['invite_two'];

		} else {

			$invite_one = 0;

			$invite_two = 0;

			$invite_three = 0;

		}

		$register_info = array();

		$register_info['username'] = $_POST['username'];

		$register_info['password'] = $_POST['password'];

		$register_info['password_confirm'] = $_POST['password_confirm'];

		$register_info['email'] = $_POST['email'];

		$register_info['invite_one'] = $invite_one;

		$register_info['invite_two'] = $invite_two;

		$register_info['invite_three'] = $invite_three;

		$member_info = $model_member -> register($register_info);

		if (!isset($member_info['error'])) {

			$token = $this -> _get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);

			if ($token) {

				output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));

			} else {

				output_error('注册失败');

			}

		} else {

			output_error($member_info['error']);

		}

	}

	//手机注册第一步

	public function register_mobileOp() {

		Tpl::output('web_seo', C('site_name') . ' - ' . '手机注册');

		Tpl::showpage('register_mobile');

	}

	//手机注册第二步

	public function register_mobile_codeOp() {

		Tpl::output('web_seo', C('site_name') . ' - ' . '手机验证码验证');

		Tpl::showpage('register_mobile_code');

	}

	//手机注册第3步

	public function register_mobile_passwordOp() {

		Tpl::output('web_seo', C('site_name') . ' - ' . '手机注册密码设置');

		Tpl::showpage('register_mobile_password');

	}

	/**

	 * 登录生成token

	 */

	private function _get_token($member_id, $member_name, $client) {

		$model_mb_user_token = Model('mb_user_token');

		//重新登录后以前的令牌失效

		//暂时停用

		//$condition = array();

		//$condition['member_id'] = $member_id;

		//$condition['client_type'] = $client;

		//$model_mb_user_token->delMbUserToken($condition);

		//生成新的token

		$mb_user_token_info = array();

		$token = md5($member_name . strval(TIMESTAMP) . strval(rand(0, 999999)));

		$mb_user_token_info['member_id'] = $member_id;

		$mb_user_token_info['member_name'] = $member_name;

		$mb_user_token_info['token'] = $token;

		$mb_user_token_info['login_time'] = TIMESTAMP;

		$mb_user_token_info['client_type'] = $client;

		$result = $model_mb_user_token -> addMbUserToken($mb_user_token_info);

		if ($result) {

			return $token;

		} else {

			return null;

		}

	}

}
