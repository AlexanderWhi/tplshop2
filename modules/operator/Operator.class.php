<?php
include_once("OperatorComponent.class.php");

class Operator extends OperatorComponent {
	
	protected $mod_name='Кабинет оператора';
	protected $mod_title='Кабинет оператора';
	protected $order_status_link=array(
		'new'=>1,
		'received'=>2,
		'assembly'=>3,
		'agreement'=>4,
		'waybill'=>5,
		'waiting'=>6,
		'delivery'=>7,
		'delivered'=>8,
		'notdelivered'=>9,
	);
	protected $order_status_color=array(
		1=>'#F00',
		2=>'#F60',
		3=>'#F66',
		4=>'#600',
		5=>'#6F0',
		6=>'#6FF',
		7=>'#00F',
		8=>'#F6F',
		9=>'#666',
	);
	
	function getOrderStatusList(){
		global $ST;
		$rs=$ST->select("SELECT * FROM sc_enum WHERE field_name='order_status' ORDER BY position");
		$order_status=array();
		while ($rs->next()) {
			$order_status[(int)$rs->get('field_value')]=$rs->get('value_desc');
		}
		return $order_status;
	}
	
	protected $delivery_type_list=array(1=>'Обычная',2=>'Экспресс',3=>'Самовывоз');
	
//	function getStatusById($id){
//		foreach ($this->status_list as $row)if($row['id']==$id)return $row['desc'];
//		return '';
//	}
	
	function actDefault(){
		global $ST;
		
		$status_name=$this->getURIVal('operator');		
		
		$status=0;
		
		$order_status_list=$this->getOrderStatusList();
		
		if(isset($this->order_status_link[$status_name])){
			$status=$this->order_status_link[$status_name];
			
			$this->explorer[]=array('name'=>$order_status_list[$status]);
			$this->setTitle($order_status_list[$status]);
		}
		
		$pg=new Page(isset($_COOKIE['pages'])&& $_COOKIE['pages']?intval($_COOKIE['pages']):15);
		$cond='1';
		
		if($status>0){
			$cond.=" AND order_status=$status";
			if($status>1){
				$cond.=" AND perfid IN(0,".$this->getUserId().")";
			}
		}
		$order="create_time DESC";
		
		$rs=$ST->select("SELECT COUNT(*) AS c FROM sc_shop_order WHERE $cond");
		if($rs->next()){
			$pg->all=$rs->getInt('c');
		}
		$rs=$ST->select("SELECT *,o.address,o.phone,o.discount FROM sc_shop_order o 
			LEFT JOIN sc_users AS u ON u_id=o.userid
			WHERE $cond
			ORDER BY $order 
			LIMIT {$pg->getBegin()},{$pg->per}"
		)->toArray();
		
		$data=array(
			'pg'=>$pg,
			'rs'=>$rs,
		);
		$data['order_status_list']=$order_status_list;
		$data['order_status_color']=$this->order_status_color;
		$this->display($data,dirname(__FILE__).'/order_list.tpl.php');
	}
	
	
	function actChangePageSize(){
		setcookie('pages',intval($_POST['val']),time()+3600*24*180,'/');
		header("Location: ".$_SERVER['HTTP_REFERER']);exit;
	}
	
	function actOrder(){
		
		global $ST,$get;
		$id=$get->getInt('id');
		$this->setTitle('Редактировать заказ'.($id?' №'.$id:''));
		$this->explorer[]=array('name'=>'Редактировать заказ'.($id?' №'.$id:''));
		
		
		$field=array(
			'create_time'=>date('Y-m-d H:i:s'),
			'order_status'=>'',
//			'start_time'=>null,
//			'stop_time'=>null,
			'complete_status'=>'',
			'comment'=>'',
			'review'=>'',
			'pay_time'=>null,
			'pay_system'=>'',
			'pay_account'=>'',
			'pay_status'=>'',
			'perfid'=>0,
			'userid'=>0,
			
			'price'=>'',
			'margin'=>0,
			'total_price'=>0,
			'discount'=>0,
			'delivery'=>$this->cfg('SHOP_DELIVERY'),
			'delivery_type'=>1,
			'date'=>date('d.m.Y'),
			'time'=>'',
			'fullname'=>'',
			'address'=>'',
			'phone'=>'',
			'additionally'=>''
		);
		
		if($id){
			$rs=$ST->select("SELECT o.".implode(',o.',array_keys($field)).",u.mail,u.comment AS user_comment 
				FROM sc_shop_order AS o 
				LEFT JOIN sc_users u ON u.u_id=userid 
				WHERE o.id=".$id);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$field['id']=$id;
		$field['performer']='';
		$field['order_status_list']=$this->getOrderStatusList();
		$field['order_status_color']=$this->order_status_color;		
		//Другие заказы
		$rs=$ST->select("SELECT * FROM sc_shop_order WHERE userid=".$field['userid']." AND userid>0 ORDER BY create_time");
		$field['client_orders']=array();
		while ($rs->next()) {
			$field['client_orders'][]=$rs->getRow();
		}
				
		$orderItem=array();
		$rs=$ST->select("SELECT * FROM sc_shop_order_item WHERE orderid=".$id." ORDER BY id");
		while ($rs->next()) {
			$orderItem[$rs->get('itemid')]=array(
				'count'=>$rs->get('count'),
				'price'=>$rs->get('price')
			);
		}
		$rs=$ST->select("SELECT * FROM sc_shop_order_log WHERE orderid=".$id." ORDER BY time");
		$status=0;
		$order_log=array();
		while ($rs->next()) {
			if($rs->get('order_status')!=$status){
				$status=$rs->get('order_status');
				$order_log[]=array(dte($rs->get('time'),'d.m.Y H:i:s'),$status);
			}
		}
		$field['orderOldContent']='';
		$rs=$ST->select("SELECT * FROM sc_shop_order_datalog WHERE orderid=$id ORDER BY time LIMIT 1");
		if($rs->next()){
			$curOrderItem=$orderItem;
			ksort($curOrderItem);
			$old_data=@unserialize($rs->get('data'));
			if(serialize($curOrderItem)!=serialize($old_data['items'])){
				$field['orderOldContent']=$this->renderBasket($old_data['items'],0,0,'/ordercontent_print.tpl.php');
			}
		}
		$delivery_list=array();
		$rs=$ST->select("SELECT * FROM sc_shop_delivery ORDER BY distance");
		while ($rs->next()) {
			$delivery_list[]=array((float)$rs->get('distance'),$rs->getInt('price'));
		}
		
		
		$field['delivery_list']=printJSON($delivery_list);
		$field['order_log']=$order_log;
		$field['delivery_type_list']=$this->delivery_type_list;
		$field['orderContent']=$this->renderBasket($orderItem,$field['discount'],$field['delivery']);
		$this->display($field,dirname(__FILE__).'/order.tpl.php');
	}
	
	function renderBasket($items=array(),$discount=0,$delivery=0,$tpl='/ordercontent.tpl.php'){
		include_once('modules/catalog/Basket.class.php');
		global $ST;
	
		$keys=array_keys($items);
		
		$orderItem=array();
		$rs=$ST->select("SELECT i.name,i.price,i.img,i.description,i.id,u.value_desc AS unit
			FROM sc_shop_item AS i 
			LEFT JOIN (SELECT * FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=i.unit
			WHERE id IN('".implode("','",$keys)."')");
		while ($rs->next()) {
			$c=$items[$rs->get('id')]['count'];
			if(empty($c))$c=1;
			$price=$rs->get('price')*$c;
			if(!empty($items[$rs->get('id')]['price']))$price=$items[$rs->get('id')]['price'];
			$items[$rs->get('id')]=array(
				'img'=>$rs->get('img'),
				'name'=>$rs->get('name'),
				'description'=>$rs->get('description'),
				'count'=>$c,
				'price'=>$price,
				'unit'=>$rs->get('unit'),
				'iprice'=>round($price/$c,2),
			);
		}
		$basket=new Basket($items);
		$basket->delivery=$delivery;
		$basket->discount=$discount;
		$data=array(
			'orderContent'=>$basket,
			);
		return $this->render($data,dirname(__FILE__).$tpl);
	}
	
	function actSaveOrder(){
		include('modules/catalog/Basket.class.php');
		global $ST,$post;
		$id=$post->getInt('id');
		
		$count=$post->getArray('count');
		$price=$post->getArray('price');
		
		if(!$count){
			echo printJSON(array('msg'=>'Ошибка сохранения. Корзина пуста'));exit;
		}
		
		$orderItem=$count;
		$rs=$ST->select("SELECT * 
			FROM sc_shop_item  
			WHERE id IN (".implode(',',array_keys($count)).")");
		while ($rs->next()) {
			$orderItem[$rs->get('id')]=array(
				'count'=>$count[$rs->get('id')],
				'price'=>$price[$rs->get('id')],
			);
		}
		$basket=new Basket($orderItem);
		$basket->delivery=$post->getFloat('delivery');
		$basket->discount=$post->getFloat('discount');
		
		$data=array(
			'order_status'=>$post->get('order_status'),
			'comment'=>$post->get('comment'),
			'review'=>$post->get('review'),
			'perfid'=>$this->getUserId(),
			'price'=>$basket->getPrice(),
			'total_price'=>$basket->getTotalPrice(),
			'discount'=>$basket->discount,
			'delivery'=>$basket->delivery,
			'delivery_type'=>$post->get('delivery_type'),
			'date'=>dte($post->get('date'),'Y-m-d'),
			'time'=>$post->get('time'),
			'fullname'=>$post->get('fullname'),
			'address'=>$post->get('address'),
			'phone'=>$post->get('phone'),
			'additionally'=>$post->get('additionally')
		);
		
		
		$out['msg']='Сохранено';
		
		if($id){
			if(in_array($data['order_status'],array(3))){
				//Логируем изменения
				$cur_data=array(
					'date'=>'',
					'discount'=>'',
					'delivery'=>'',
					'delivery_type'=>'',
				);
				$cur_data_item=array();
				$rs=$ST->select("SELECT ".implode(',',array_keys($cur_data))." FROM sc_shop_order WHERE id=$id");
				if($rs->next()){
					$cur_data=$rs->getRow();
					$rs=$ST->select("SELECT itemid,count,price FROM sc_shop_order_item WHERE orderid=$id ORDER BY itemid");
					while ($rs->next()) {
						$row=$rs->getRow();
						$cur_data['items'][$rs->get('itemid')]=array('count'=>$rs->get('count'),'price'=>$rs->get('price'));
					}
				}
				
//				$change=array();
//				foreach ($cur_data as $k=>$v){
//					if(trim($v)!=trim($data[$k])){
//						$change[$k]=$data[$k];
//					}
//				}
//				ksort($orderItem);
//				if(json_encode($cur_data_item)!=json_encode($orderItem)){
//					$change['item']=$cur_data_item;
//				}
				
				if($cur_data){
					$ST->insert('sc_shop_order_datalog',array('orderid'=>$id,'data'=>serialize($cur_data)));
				}
			}
			////////////////////////////////////
			$ST->update('sc_shop_order',$data,"id=".$id);
			$ST->delete('sc_shop_order_item','orderid='.$id);
		}else{
			$data['userid']=$this->getUserId();
			$id=$ST->insert('sc_shop_order',$data);
		}
		$this->logOrder($id,$this->getUserId(),$post->get('order_status'));
		foreach ($orderItem as $key=>$row){
			$d['orderid']=$id;
			$d['itemid']=$key;
			$d['count']=$row['count'];
			$d['price']=$row['price'];
			$ST->insert('sc_shop_order_item',$d);
		}
		$out['id']=$id;		
		echo printJSON($out);exit;
	}
	
	function logOrder($id,$perfid,$status){
		global $ST;
		if(is_array($id)){
			foreach ($id as $i) {
				$ST->insert('sc_shop_order_log',array('orderid'=>$i,'perfid'=>$perfid,'order_status'=>$status));
			}
		}else{
			$ST->insert('sc_shop_order_log',array('orderid'=>$id,'perfid'=>$perfid,'order_status'=>$status));
		}
	}
	
	function actRefreshBasket(){
		global $post;
		$count=$post->getArray('count');
		$price=$post->getArray('price');
		$items=array();
		foreach ($price as $key=>$val){
			$items[$key]=array('price'=>$val,'count'=>(float)str_replace(',','.',$count[$key]));
			if(empty($items[$key]['count'])){
				$items[$key]['count']=1;
			}
		}
		
		if($post->exists('replace')){
			unset($items[$post->get('replace')]);
		}
		foreach ($post->getArray('sel_item') as $key=>$count){
//			if(empty($items[$key]))
			$items[$key]['count']=$count;
		}
		echo $this->renderBasket($items,$post->getFloat('discount'),$post->getFloat('delivery'));exit;
	}
	
	function getCatalog($parent=0){
		global $ST;
		$catalog=array();
		$queryStr="SELECT * FROM sc_shop_catalog WHERE parentid = ".$parent."  ORDER BY sort,parentid ";
		$rs=$ST->select($queryStr);
		while ($rs->next()){
			$item=$rs->getRow();
			$item['children']=$this->getCatalog($item['id']);
			$catalog[]=$item;
		}
		return $catalog;
	}
	
	function getGoods($pgSize=20,$category=0,$search=''){
		global $ST,$get;
				
		$pg=new Page($pgSize);
		
		$catIds=array();
		if($category){
			$rs=$ST->select("SELECT * FROM sc_shop_catalog WHERE id=".$category);
			if($rs->next()){
				$catIds=unserialize($rs->get('cache_child_catalog_ids'));
			}
			$catIds[]=$category;
		}
		
		$condition="WHERE 1=1 ";
		if($catIds){
			$condition.=" AND category IN('".join("','",$catIds)."')";
		}
		if($search=SQL::slashes(strtolower(trim($search))) ){
			$condition.=" AND (LOWER(name) LIKE '%$search%' OR product='$search')";
		}
		
		$query="SELECT count(*) AS c FROM sc_shop_item i ".$condition ;
		$rs=$ST->select($query);
		if($rs->next()){
			$pg->all=$rs->getInt('c');
		}
		
		
		$order='ORDER BY ';
		$ord=$this->getURIVal('ord')!='asc'?'asc':'desc';
		if($this->getURIVal('sort')=='name'){
			$order.='name '.$ord;
			
		}elseif($this->getURIVal('sort')=='price'){
			$order.='price '.$ord;
		
		}elseif($this->getURIVal('sort')=='sort'){
			$order.='sort '.$ord;
		
		}elseif($this->getURIVal('sort')=='in_stock'){
			$order.='in_stock '.$ord;
		
		}else{
			if($search){
				$order.="IF(LOCATE('$search',LOWER(i.name)),LOCATE('$search',LOWER(i.name)),256),category ,name";
			}else{
				$order.='category ,name';
			}
		}
		
		$queryStr="SELECT * FROM sc_shop_item i $condition $order LIMIT ".$pg->getBegin().",".$pg->per ;
		$data['rs']=$ST->select($queryStr);
		$data['pg']=$pg;
		
		$data['catalog']=$this->catRef=$this->getCatalog();
		return $data;
	}
	
	public $popupCategory=0;
	public $popupSearch='';
	public $popupPageSize=20;
	function actGoodsPopup(){
		global $ST,$get,$post;		
		$category=0;
		if($get->exists('goods')){
			$rs=$ST->select("SELECT category FROM sc_shop_item WHERE id=".$get->get('goods'));
			if($rs->next()){
				$this->popupCategory=$rs->getInt('category');
			}
		}
		if($post->exists('catalog')){
			$this->popupCategory=$post->get('catalog');
		}
		if($post->exists('page_size')){
			$this->popupPageSize=$post->get('page_size');
		}
		if ($post->exists('search')) {
			$this->popupSearch=$post->get('search');
		}
		
		
		$data=$this->getGoods($this->popupPageSize,$this->popupCategory,$this->popupSearch);
		$data['search']=$this->popupSearch;
		$data['CATALOG_SELECT']=$this->render(
			array('catalog'=>$this->getCatalog(),'selected'=>$this->popupCategory),
			dirname(__FILE__).'/catalog_select.tpl.php');
		
		$data['PAGE_SELECT']=$this->render(array(
			'list'=>array(20,50,100,500,1000),
			'current'=>$this->popupPageSize
		),dirname(__FILE__).'/pages_select.tpl.php');
			
		$this->tplContainer='core/tpl/admin/admin_popup.php';
		$this->display($data,dirname(__FILE__).'/goods_popup.tpl.php');
	}

	function actWaybillList(){
		global $ST,$post;
		
		if($post->exists('driver_select')){
			$this->driver_select=$post->get('driver_select');
		}
		
		$pg=new Page(isset($_COOKIE['pages'])&& $_COOKIE['pages']?intval($_COOKIE['pages']):15);
		
		$cond='1';
		
		if($this->driver_select){
			$cond="driver=".$this->driver_select;
		}
		$rs=$ST->select("SELECT COUNT(*) AS c FROM sc_shop_waybill WHERE $cond");
		if($rs->next()){
			$pg->all=$rs->getInt('c');
		}
		
		$rs=$ST->select("SELECT *,wb.id FROM sc_shop_waybill wb
			LEFT JOIN sc_shop_waybill_driver d ON d.id=wb.driver
			LEFT JOIN (SELECT COUNT(oid) AS a,wbid FROM sc_shop_waybill_item GROUP BY wbid) AS a ON a.wbid=wb.id
			LEFT JOIN (SELECT COUNT(oid) AS c1 ,wbid FROM sc_shop_waybill_item,sc_shop_order WHERE oid=id AND order_status=8 GROUP BY wbid) AS d1 ON d1.wbid=wb.id 
			LEFT JOIN (SELECT COUNT(oid) AS c2,wbid FROM sc_shop_waybill_item,sc_shop_order WHERE oid=id AND order_status=9 GROUP BY wbid) AS d2 ON d2.wbid=wb.id 
			WHERE $cond LIMIT {$pg->getBegin()},{$pg->per}")->toArray();
		$data=array(
			'pg'=>$pg,
			'rs'=>$rs,
		);
		$driver_list=array('Водитель не выбран');
		$rs=$ST->select("SELECT * FROM sc_shop_waybill_driver WHERE state=1 ORDER BY name");
		while ($rs->next()) {
			$driver_list[$rs->get('id')]=$rs->get('name')." [{$rs->get('car')}]";
		}
		$select=array(
			'name'=>'driver_select',
			'list'=>$driver_list,
			'value'=>$this->driver_select,
		);
		$data['driver_select']=$this->render($select,'core/tpl/common/select.tpl.php');
		
		$this->setTitle('Список путевых листов');
		$this->explorer[]=array('name'=>'Список путевых листов');
		$this->display($data,dirname(__FILE__).'/waybill_list.tpl.php');
	}
	
	public $driver_select=0;
	
	function actSearchWaybill(){
		global $post;
		$this->driver_select=$post->get('driver_select');
		header("Location: ".$_SERVER['HTTP_REFERER']);exit;
	}
	
	function actWaybillEdit(){
		global $ST,$get;
		$id=$get->getInt('id');
		$data=array(
			'id'=>$id,
			'driver'=>0,
			
			);
		$items=array();
		if($id){
			$rs=$ST->select("SELECT * FROM sc_shop_waybill WHERE id={$id}");
			if($rs->next()){
				$data=$rs->getRow();
				$rs=$ST->select("SELECT * FROM sc_shop_waybill_item WHERE wbid={$id}");
				while ($rs->next()) {
					$items[$rs->get('oid')]=$rs->get('sort');
				}
			}
		}
		$data['orders']=$this->renderWaybillOrders($items);
		$data['driver_list']=array();
		$rs=$ST->select("SELECT * FROM sc_shop_waybill_driver WHERE state=1");
		while ($rs->next()){
			$data['driver_list'][]=$rs->getRow();
		}
		$data['order_status_list']=$this->getOrderStatusList();
		$data['status_list']=array(5,6,7);
		
		$this->setTitle('Редактировать путевой лист');
		$this->explorer[]=array('name'=>'Редактировать путевой лист');
		$this->display($data,dirname(__FILE__).'/waybill_edit.tpl.php');
	}
	function actWaybillSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		$data=array(
			'driver'=>$post->getInt('driver'),
		);
		if($id){
			$ST->update('sc_shop_waybill',$data, "id={$id}");
			$ST->delete('sc_shop_waybill_item',"wbid={$id}" );
		}else{
			$id=$ST->insert('sc_shop_waybill',$data, "id={$id}");
		}
		$sort_list=$post->getArray('sort');
		foreach ($sort_list as $key=>$sort){
			$d=array(
				'wbid'=>$id,
				'oid'=>$key,
				'sort'=>$sort,
			);
			$ST->insert('sc_shop_waybill_item',$d );
		}
		$out=array('msg'=>'Сохранено','id'=>$id);
		$order_status_list=$this->getOrderStatusList();
		if(($order_status=$post->getInt('order_status'))>0 && $sort_list){
			$ST->update('sc_shop_order',array('order_status'=>$order_status),"id IN('".implode("','",$ids=array_keys($sort_list))."')");
			$this->logOrder($ids,$this->getUserId(),$order_status);
			$out['order_status']=$order_status_list[$order_status]." [$order_status]";
		}
		echo printJSON($out);exit;
	}
	
	function renderWaybillOrders($items=array()){
		global $ST;
		$res=array();
		$data['order_status_list']=$this->getOrderStatusList();
		$data['rs']=$items;
		if($items){
			
			$rs=$ST->select("SELECT * FROM sc_shop_order WHERE id IN('".implode("','",array_keys($items))."')");
			while ($rs->next()) {
				$res[$rs->getInt('id')]=$rs->getRow();
			}
			
			asort($items);
			
			foreach ($items as $k=>$sort){
				if(isset($res[$k])){
					$items[$k]=$res[$k];
					$items[$k]['sort']=$sort;
				}else{
					unset($items[$k]);
				}
				
			}
			$data['rs']=$items;
		}
		return $this->render($data,dirname(__FILE__).'/waybill_edit_orders.tpl.php');
	}
	
	function actOrdersPopup(){
		global $ST,$post;		
		$status=5;
		
		$pg=new Page(isset($_COOKIE['pages'])&& $_COOKIE['pages']?intval($_COOKIE['pages']):15);
		$cond="order_status=$status";
		
		if($status>1){
			$cond.=" AND perfid IN(".$this->getUserId().")";
		}
		$order="create_time DESC";
		
		$rs=$ST->select("SELECT COUNT(*) AS c FROM sc_shop_order WHERE $cond");
		if($rs->next()){
			$pg->all=$rs->getInt('c');
		}
		
		$rs=$ST->select("SELECT * FROM sc_shop_order o 
			WHERE $cond
			ORDER BY $order 
			LIMIT {$pg->getBegin()},{$pg->per}"
		)->toArray();
		
		$data=array(
			'pg'=>$pg,
			'rs'=>$rs,
		);
		
		$data['PAGE_SELECT']=$this->render(array(
			'list'=>array(20,50,100,500,1000),
			'current'=>$this->popupPageSize
		),dirname(__FILE__).'/pages_select.tpl.php');
			
		$this->tplContainer='core/tpl/admin/admin_popup.php';
		$this->display($data,dirname(__FILE__).'/orders_popup.tpl.php');
	}
	
	function actRefreshOrders(){
		global $post;
		$items=$post->getArray('sort');
		$sel_item=$post->getArray('sel_item');
		foreach ($sel_item as $val){
			$items[$val]=0;
		}
		echo $this->renderWaybillOrders($items);exit;
	}
	
	function actPrint(){
//		include('modules/catalog/Basket.class.php');
		global $ST,$get;
		
		$type=$get->get('type');
		
		$data=array('type'=>$type);
		
		$ids=$get->getArray('item');
		$condition="id IN(".implode(',',$ids).")";
		
		$queryStr="SELECT o.*, o.phone,p.name FROM sc_shop_order AS o 
			LEFT JOIN sc_users p ON p.u_id=o.perfid
			WHERE $condition 
			ORDER BY o.create_time DESC ";
		$rs=$ST->select($queryStr);
		$data['rs']=array();
		while ($rs->next()) {
			$data['rs'][$rs->getInt('id')]=$rs->getRow();
			$data['rs'][$rs->getInt('id')]['delivery_type']=$this->delivery_type_list[$rs->get('delivery_type')];
			$data['rs'][$rs->getInt('id')]['time1']='';
			$data['rs'][$rs->getInt('id')]['time2']='';
			$rs1=$ST->select("SELECT * FROM sc_shop_order_log WHERE orderid={$rs->getInt('id')} ORDER BY time");
			while ($rs1->next()) {
				if($rs1->get('order_status')==3 && !$data['rs'][$rs->getInt('id')]['time1']){
					$data['rs'][$rs->getInt('id')]['time1']=$rs1->get('time');
				}
				if($data['rs'][$rs->getInt('id')]['time1'] && $rs1->get('order_status')!=3){
					$data['rs'][$rs->getInt('id')]['time2']=$rs1->get('time');
				}
			}
			
			
			$orderItem=array();
			$rs1=$ST->select("SELECT oi.* FROM sc_shop_order_item AS oi  WHERE oi.orderid=".$rs->getInt('id'));
			while ($rs1->next()) {
				$orderItem[$rs1->get('itemid')]=array(
					'count'=>$rs1->get('count'),
					'price'=>$rs1->get('price'),
				);
			}
			$data['rs'][$rs->getInt('id')]['orderContent']=$this->renderBasket($orderItem,$rs->get('discount'),$rs->get('delivery'),'/ordercontent_print.tpl.php');	
					
			$data['rs'][$rs->getInt('id')]['orderOldContent']='';
			
			if($type=='collect'){
				$rs1=$ST->select("SELECT * FROM sc_shop_order_datalog WHERE orderid={$rs->getInt('id')} ORDER BY time LIMIT 1");
				if($rs1->next()){
					$curOrderItem=$orderItem;
					ksort($curOrderItem);
					$old_data=@unserialize($rs1->get('data'));
					if(serialize($curOrderItem)!=serialize($old_data['items'])){
						$data['rs'][$rs->getInt('id')]['orderOldContent']=$this->renderBasket($old_data['items'],0,0,'/ordercontent_print.tpl.php');
					}
				}
			}
		}
		
		$this->setTitle('Печать заказа');
		$this->tplContainer="core/tpl/admin/admin_print.php";
		$this->display($data,dirname(__FILE__).'/order_print.tpl.php');
	}
	
	function actPrintWaybill(){
		
		global $ST,$get;
		
		$data=array();
		
		$ids=$get->getArray('item');
		$condition="wb.id IN(".implode(',',$ids).")";
		
		$queryStr="SELECT * FROM sc_shop_waybill wb 
			LEFT JOIN sc_shop_waybill_driver AS d ON d.id=wb.driver
			WHERE $condition 
			ORDER BY time DESC ";
		$rs=$ST->select($queryStr);
		$data['rs']=array();
		while ($rs->next()) {
			$data['rs'][$rs->getInt('id')]=$rs->getRow();
//			$data['rs'][$rs->getInt('id')]['delivery_type']=$this->delivery_type_list[$rs->get('delivery_type')];
			
			
			$waybillOrders=array();
			$rs1=$ST->select("SELECT * FROM sc_shop_order o,sc_shop_waybill_item i WHERE i.oid=o.id AND i.wbid=".$rs->getInt('id')." ORDER BY i.sort");
			while ($rs1->next()) {
				$waybillOrders[]=$rs1->getRow();
			}
			$data['rs'][$rs->getInt('id')]['waybillOrders']=$this->render(array('rs'=>$waybillOrders),dirname(__FILE__).'/waybill_print_orders.tpl.php');	
		}
		
		$this->setTitle('Печать путевого листа');
		$this->tplContainer="core/tpl/admin/admin_print.php";
		$this->display($data,dirname(__FILE__).'/waybill_print.tpl.php');
	}
}
?>