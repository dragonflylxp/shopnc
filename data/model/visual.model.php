<?php
/**
 * 可视化装修模型
 */
defined('Inshopec') or exit('Access Invalid!');

class visualModel extends Model {
	protected $table1;
	protected $db1;
	protected $id1;
	protected $name1;
	protected $error_msg1;
	public function exchange($table, &$db, $id, $name)
	{
		$tablePre = C('tablepre');
		$this->table1 = $tablePre.$table;
		$this->db1 = &$db;
		$this->id1 = $id;
		$this->name1 = $name;
		$this->error_msg1 = '';
	}

	public function is_only($col, $name, $id = 0, $where = '', $table = '', $idType = '')
	{
		if (empty($table)) {
			$table = $this->table1;
		}

		if (empty($idType)) {
			$idType = $this->id1;
		}

		$sql = 'SELECT COUNT(*) FROM ' . $table . ' WHERE ' . $col . ' = \'' . $name . '\'';
		$sql .= (empty($id) ? '' : ' AND ' . $idType . ' <> \'' . $id . '\'');
		$sql .= (empty($where) ? '' : ' AND ' . $where);
	
		return $this->getOne($sql) == 0;
	}

	public function num($col, $name, $id = 0, $where = '')
	{
		$sql = 'SELECT COUNT(*) FROM ' . $this->table1 . ' WHERE ' . $col . ' = \'' . $name . '\'';
		$sql .= (empty($id) ? '' : ' AND ' . $this->id1 . ' != \'' . $id . '\' ');
		$sql .= (empty($where) ? '' : ' AND ' . $where);
		return $this->getOne($sql);
	}

	public function edit($set, $id, $table = '', $idType = '')
	{
		if (empty($table)) {
			$table = $this->table1;
		}
		

		if (empty($idType)) {
			$idType = $this->id1;
		}

		$sql = 'UPDATE ' . $table . ' SET ' . $set . ' WHERE ' . $idType . ' = \'' . $id . '\'';

		if ($this->query($sql)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function get_name($id, $name = '')
	{
		if (empty($name)) {
			$name = $this->name1;
		}

		$sql = 'SELECT `' . $name . '` FROM ' . $this->table1 . ' WHERE ' . $this->id1 . ' = \'' . $id . '\'';
		return $this->getOne($sql);
	}

	public function drop($id, $table = '', $idType = '')
	{
		if (empty($table)) {
			$table = $this->table1;
		}


		if (empty($idType)) {
			$idType = $this->id1;
		}

		$sql = 'DELETE FROM ' . $table . ' WHERE ' . $idType . ' = \'' . $id . '\'';
		return $this->query($sql);
	}	
    /**
     * 查询单条模板信息
     */
    public function getleft_attr($type = 0, $ru_id = 0, $tem = '', $theme = '') {
    	$condition = array();
    	$condition['ru_id'] = $ru_id;
    	$condition['type'] = $type;
    	$condition['seller_templates'] = $tem;
    	$condition['theme'] = $theme;
    	$fields=array('bg_color','img_file','if_show','bgrepeat','align','fileurl');
        return $this->table('templates_left')->where($condition)->field($fields)->find();
    }
    
public function get_goods_gallery_album($type = 0, $id = 0, $select = array(), $id_name = 'album_id', $order = '')
{
	$where = 1;
    $tablePre = C('tablepre');
	if ($id) {
		$where .= ' AND ' . $id_name . ' = \'' . $id . '\'';
	}

	if ($select && is_array($select)) {
		$select = implode(',', $select);
	}
	else {
		$select = '*';
	}

	if ($type == 2) {
		$where .= ' LIMIT 1';
	}

	$sql = 'SELECT ' . $select . ' FROM '  . $tablePre.'gallery_album' . ' WHERE ' . $where . ' ' . $order;

	if ($type == 1) {
		$album_list = $this->getAll1($sql);

		if ($album_list) {
			foreach ($album_list as $key => $row) {
				$album_list[$key]['add_time'] = local_date('Y-m-d H:i:s', $row['add_time']);
			}
		}
	}
	else if ($type == 2) {
		$album_list = $this->getRow($sql);
	}
	else {
		$album_list = $this->getOne($sql);
	}

	return $album_list;
}
public function getGoodslist($where = '', $sort = '', $search = '', $leftjoin = '')
{
	$tablePre = C('tablepre');
	$sql = 'SELECT COUNT(*) FROM ' . $tablePre .'goods' . ' AS g ' . $leftjoin . $where;
	
	$filter['record_count'] = $this->getOne($sql);
	
	$filter = page_and_size($filter);
	
	$where .= $sort . ' LIMIT ' . $filter['start'] . ',' . $filter['page_size'];//g.promote_start_date, g.promote_end_date, g.promote_price,  g.goods_thumb,
	$sql = 'SELECT g.goods_promotion_price, g.goods_name, g.goods_id, g.goods_price, g.goods_marketprice, g.goods_image ' . $search . ' FROM ' . $tablePre .'goods' . ' AS g ' . $leftjoin . $where;
	
	$goods_list = $this->getAll1($sql);
	$filter['page_arr'] = seller_page($filter, $filter['page']);
	return array('list' => $goods_list, 'filter' => $filter);
}
public function getAlbumList($album_id = 0)
{
    $tablePre = C('tablepre');
	$filter['album_id'] = !empty($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : 0;
	$filter['sort_name'] = !empty($_REQUEST['sort_name']) && ($_REQUEST['sort_name'] != 'undefined') ? intval($_REQUEST['sort_name']) : 2;

	if (0 < $album_id) {
		$filter['album_id'] = $album_id;
	}

	$where = ' WHERE 1';

	if (0 < $filter['album_id']) {
		$where .= ' AND album_id = \'' . $filter['album_id'] . '\'';
	}

	if (0 < $filter['sort_name']) {
		switch ($filter['sort_name']) {
		case '1':
			$where .= ' ORDER BY add_time ASC';
			break;

		case '2':
			$where .= ' ORDER BY add_time DESC';
			break;

		case '3':
			$where .= ' ORDER BY pic_size ASC';
			break;

		case '4':
			$where .= ' ORDER BY pic_size DESC';
			break;

		case '5':
			$where .= ' ORDER BY pic_name ASC';
			break;

		case '6':
			$where .= ' ORDER BY pic_name DESC';
			break;
		}
	}
	$sql = 'SELECT COUNT(*) FROM ' . $tablePre.'pic_album' . $where;
	$filter['record_count'] = $this->getOne($sql);
	$filter = page_and_size($filter, 3);
	$where .= ' LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
	$sql = 'SELECT * FROM ' . $tablePre.'pic_album' . $where;
	$recommend_brands = $this->getAll1($sql);
	$arr = array();	
    if(is_array($recommend_brands)){
    	
   
	foreach ($recommend_brands as $key => $row) {
		$row['pic_file'] = get_image_path($row['pic_id'], $row['pic_file']);
		$row['pic_thumb'] = get_image_path($row['pic_id'], $row['pic_thumb']);
		$row['pic_image'] = get_image_path($row['pic_id'], $row['pic_image']);
		$arr[] = $row;
	}
    }
	$filter['page_arr'] = seller_page($filter, $filter['page'], 14);
	return array('list' => $arr, 'filter' => $filter);
}
/**
 * by:511613932
 */
public function resetBarnd($brand_id = array(), $table = 'goods', $category = 'goods_id')
{
	$tablePre = C('tablepre');
	if ($brand_id) {
		if ($table == 'goods') {
			$sql = 'SELECT ' . $category . ' FROM '  . $tablePre.'goods' . ' WHERE ' . $category . ' in (' . $brand_id . ') AND store_id = \'' . $_SESSION['store_id'] . '\'';
		}
		else if ($table == 'brand') {
			$where = ' WHERE be.is_recommend=1 AND b.brand_id in (' . $brand_id . ')';
			$sql = 'SELECT b.brand_id FROM ' . $tablePre.'brand'  . ' as b left join ' . $tablePre.'brand_extend' . ' AS be on b.brand_id=be.brand_id ' . $where;
		}

		$ids = $this->getAll1($sql);

		if (!empty($ids)) {
			return implode(',', arr_foreach($ids));
		}
		else {
			return '';
		}
	}
	else {
		return '';
	}
}    
    /**
     * 保存模板信息
     */
    public function getleft_up($condition = array(),$data) {
    	return $this->table('store')->where($condition)->update($data);
    }    
    /**
     * 查询单条模板名称
     */
    public function seller_templates($store_id) {
    	$condition = array();
    	$condition['store_id'] = $store_id;
    	$fields= array('seller_templates');
    	$return = $this->table('store')->where($condition)->field($fields)->find();
        return $return['seller_templates'];
    }
    /**
     * 取得平台月结算单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getOrderStatisInfo($condition = array(), $fields = '*',$order = null) {
        return $this->table('order_statis')->where($condition)->field($fields)->order($order)->find();
    }

    /**
     * 取得店铺月结算单列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getOrderBillList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        return $this->table('order_bill')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 取得店铺月结算单单条
     * @param unknown $condition
     * @param string $fields
     */
    public function getOrderBillInfo($condition = array(), $fields = '*') {
        return $this->table('order_bill')->where($condition)->field($fields)->find();
    }

    /**
     * ----------by suijiailong---start-------
     * 取得店铺月结算表的单条
     * @param unknown $condition
     * @param string $fields
     *
     */

    public function getOrderBillByMonthInfo($condition = array(), $fields = '*') {
        return $this->table('order_bill_month')->where($condition)->field($fields)->find();
    }
    //取得店铺月结算单多条记录
    public function getOrderBillByMonthList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        return $this->table('order_bill_month')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }
    //----------by suijiaolong---end-------
    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getOrderBillCount($condition) {
        return $this->table('order_bill')->where($condition)->count();
    }

    public function addOrderStatis($data) {
        return $this->table('order_statis')->insert($data);
    }

    public function addOrderBill($data) {
        return $this->table('order_bill')->insert($data);
    }
    public function editOrderBill($data, $condition = array()) {
        return $this->table('order_bill')->where($condition)->update($data);
    }
    /**
     * ----------by suijiailong---start-------
     * 添加店铺月结算表单的单条信息
     * @param $data
     * @return mixed
     */
    public function addOrderBillByMonth($data) {
        return $this->table('order_bill_month')->insert($data);
    }
    /**
     * ----------by suijiailong-----middle----
     * 添加店铺月结算表单的单条信息
     * @param $data
     * @return mixed
     */
    public function editOrderBillByMonth($data, $condition = array()) {
        return $this->table('order_bill_month')->where($condition)->update($data);
    }
    //----------by suijiailong---end-------
}
