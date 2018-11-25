<?php

/**

 * 文章

 *

 *

 *

 ***/





defined('Inshopec') or exit('Access Invalid!');



class articleControl extends mobileHomeControl{

	public function indexOp(){

		Tpl::output('web_seo',C('site_name').' - '.'新闻资讯');

		$condition['ac_parent_id'] = 0;

		$condition['order'] = "ac_sort asc";

		$class_list = Model('article_class')->getClassList($condition);

		Tpl::output('class_list',$class_list);

		Tpl::showpage('article_index');

	}

	

	/**

	 * 文章列表显示页面

	 */

	public function articleOp(){

		

		if(empty($_GET['cid']) && $_GET['cid'] != 0){



			echo json_encode(array('status'=>0,'info'=>"缺少参数:文章类别编号"));//'缺少参数:文章类别编号'

		}

		

		/**

		 * 根据类别编号获取文章类别信息

		 */

		$condition 	= array();

		

		if($_GET['cid']!=0){

			$article_class_model	= Model('article_class');

			$article_class	= $article_class_model->getOneClass(intval($_GET['cid']));

			if(empty($article_class) || !is_array($article_class)){

				echo json_encode(array('status'=>0,'info'=>"该文章分类并不存在"));

			}

			/**

			 * 文章列表

			 */

			$child_class_list	= $article_class_model->getChildClass(intval($_GET['cid']));

			$ac_ids	= array();

			if(!empty($child_class_list) && is_array($child_class_list)){

				foreach ($child_class_list as $v){

					$ac_ids[]	= $v['ac_id'];

				}

			}

			$ac_ids	= implode(',',$ac_ids);

			$condition['ac_ids']	= $ac_ids;

			$where['ac_id']=array('in',$ac_ids);

		

		}



		$article_model	= Model('article');

		$where['article_show']	= '1';

		$condition['article_show']	= '1';

		$article_total = Model()->table('article')->where($where)->count();

		$page = intval($_GET['p'])-1;

		$pageSize = 10; //每页显示数 

		$totalPage = ceil($article_total/$pageSize); //总页数 

		$startPage = $page*$pageSize;

		$condition['limit'] ="{$startPage},{$pageSize}";

		$condition['field'] ="article_id,article_title,ac_id,article_time,article_url,article_show,article_summary,article_img,article_view,article_zan,article_pl";

		$article_list	= $article_model->getArticleList($condition);

		

		// query("select count(*) from s_article where  ");

		if(is_array($article_list) && !empty($article_list)){

			foreach($article_list as &$vhy){

				

				if ($vhy['article_img']!= '') {

			       $vhy['article_img'] = UPLOAD_SITE_URL."/".ATTACH_ARTICLE."/".$vhy['article_img'];

			    }else{

			       $vhy['article_img'] = UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/default_article.png';

			    }

			    

				$vhy['article_time'] = friendlyDate($vhy['article_time']);

				$class_name = Model('article_class')->getOneClass($vhy['ac_id']);

				$vhy['class_name'] = $class_name['ac_name'];

			

			}

			$arrs= array();

			$arrs['status'] = 1;

			$arrs['pages'] = $totalPage;

			$arrs['datas']['nlists'] = $article_list;

			echo json_encode($arrs);

		}else{

			echo json_encode(array('status'=>0,'info'=>'没有了,别点了...'));

		}



	}

	

	/**

	*ajax_details获取文章详情

	*/

	public function showOp(){



	

		if(empty($_GET['article_id'])){

			showMessage('缺少参数:文章编号','','html','error');//''

		}

		/**

		 * 根据文章编号获取文章信息

		 */



		$article_model	= Model('article');

		Model()->table('article')->where(array('article_id'=> intval($_GET['article_id'])))->update(array('article_view'=>array('exp', 'article_view + 1')));

		$article	= $article_model->getOneArticle(intval($_GET['article_id']));

		$wheres['ac_id'] = $article['ac_id'];

		$wheres['field'] = "ac_name";

		$class_name = Model('article_class')->getOneClass($wheres);

		$article['class_name'] = $class_name['ac_name'];

		$article['article_time'] = friendlyDate($article['article_time']);

		if(empty($article) || !is_array($article) || $article['article_show']=='0'){

			showMessage($lang['article_show_not_exists'],'','html','error');//'该文章并不存在'

		}

		Tpl::output('article',$article);



		$condition['ac_parent_id'] = 0;

		$condition['order'] = "ac_sort asc";

		$class_list = Model('article_class')->getClassList($condition);

		Tpl::output('class_list',$class_list);

		Tpl::output('web_seo',C('site_name').' - '.$article['article_title']);

		Tpl::showpage('article_show');

	}







	/**

	*ajax_zan ajax赞

	*/

	public function ajax_zanOp(){

		$newsId	= intval($_POST['newsId']);

		if(!empty($newsId)){

			$up =Model()->table('article')->where(array('article_id'=> $newsId))->update(array('article_zan'=>array('exp', 'article_zan + 1')));

			if($up){

				  echo json_encode(array('status'=>1));

			}else{

				 echo json_encode(array('status'=>0,'info'=>'点赞失败'));

			}

		}else{

			 echo json_encode(array('status'=>0,'info'=>'点赞失败'));

		}

	}



	/**

	*ajax评论

	*/

	public function  ajax_contentOp(){

		    //检查是否可以评论

		$model_mb_user_token = Model('mb_user_token');

		$key = $_POST['key'];

		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);



		if(empty($mb_user_token_info)) {

			echo json_encode(array('status'=>3));

			exit();

		}

        $add_array['s_comment_content'] = $_POST['Pcontent'];

        $add_array['s_conmmet_uid'] = $mb_user_token_info['member_id'];

        $add_array['s_conmmet_article_id'] = intval($_POST['nid']);

        $add_array['s_comment_time'] = time();

        $find = Model('article_comment')->where(array('s_conmmet_uid'=> $mb_user_token_info['member_id'],'s_conmmet_article_id'=>intval($_POST['nid'])))->find();

        if($find){

        	echo json_encode(array('status'=>2));

        }else{

        	$up =Model()->table('article_comment')->insert($add_array);

        	if($up){

        		echo json_encode(array('status'=>1,'article_pl'=>$find['article_pl']+1));

        	}else{

        		echo json_encode(array('status'=>0));

        	}



        }



	}

	/*

	*ajax获取热门评论

	*/



	public function get_hotOp(){

		$nid = $_GET['nid'];

		$page = intval($_GET['p'])-1;

		$pageSize = 10; //每页显示数 

		$totalPage = ceil($article_total/$pageSize); //总页数 

		$startPage = $page*$pageSize;

		$article_model = Model('article');

		$wheres['limit'] ="{$startPage},{$pageSize}";

		$wheres['s_conmmet_article_id'] =intval($nid);

  		$article_comment_list = $article_model->getArticleCommentList($wheres);

  		$article = $article_model->getOneArticle(intval($nid));

  		$arrs= array();

  		if(is_array($article_comment_list) && !empty($article_comment_list)){

  			foreach($article_comment_list as &$vhy){

				$infos = Model('member')->where(array('member_id'=>$vhy['s_conmmet_uid']))->find();

				$vhy['member_name'] = $infos['member_name'];

				$vhy['member_avatar'] = getMemberAvatar($infos['member_avatar']);

				$vhy['s_comment_time'] = friendlyDate($vhy['s_comment_time']);

			}

			

			$arrs['status'] = 1;

			$arrs['pages'] = $totalPage;

			$arrs['datas']['clists'] = $article_comment_list;

			echo json_encode($arrs);

  		}else{

  			$arrs['status'] = 0;

  			$arrs['info'] = '没有了,别点了...';

  			$arrs['datas']['clists'] = array();

  			echo json_encode($arrs);

  		}



	}



}

?>

