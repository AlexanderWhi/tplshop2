<?php
include_once("core/component/AdminListComponent.class.php");
include_once('modules/catalog/Basket.class.php');
class AdminShop extends AdminListComponent {
	
	function __construct(){
		$this->adminGrp[]='operator';
	}
	
	function actDefault(){
		$this->actOrder();
	}
	
	function getPages(){
		return isset($_COOKIE['pages'])&& $_COOKIE['pages']?intval($_COOKIE['pages']):30;
	}
	function setPages($p){
		setcookie('pages',intval($p),COOKIE_EXP,'/');
	}
	
	function actOrder(){
		global $ST,$get;
		$pg=new Page($this->getPages());	
		$data=array();
			
		$cond='';
//		if($from=$this->getFilter('from')){
//			$cond.=" AND create_time>='".dte($from,'Y-m-d')."'";
//		}
//		if($to=$this->getFilter('to')){
//			$cond.=" AND create_time<='".dte($to,'Y-m-d')."'";
//		}
		if($from=$this->getFilter('from')){
			$cond.=" AND date>='".dte($from,'Y-m-d')."'";
		}
		if($to=$this->getFilter('to')){
			$cond.=" AND date<='".dte($to,'Y-m-d')."'";
		}
		if(!in_array($this->getFilter('order_status'),array('all',''))){
			$order_status=(int)$this->getFilter('order_status');
			
				$cond.=" AND order_status=".$order_status;
			
		}
		
		
		if($get->exists('order_status')){
			$cond.=" AND order_status={$get->getInt('order_status')}";
		}if($this->getURIVal('pay_system')){
			$cond.=" AND pay_system='{$this->getURIVal('pay_system')}'";
		}
		
//		if($reg=$this->getUser('region')){//Привязка к региону админа
//			$condition.=" AND o.region IN('".implode("','",explode(',',$reg))."')";
//		}
		
		
		
		$query="SELECT count(*) AS c FROM sc_shop_order AS o  WHERE 1=1 ".$cond ;
		$rs=$ST->select($query);
		if($rs->next()){
			$pg->all=$rs->getInt('c');
		}
		$queryStr="SELECT o.*, u.name AS u_name,u.phone AS u_phone, u.last_name AS last_name, u.first_name AS first_name, u.middle_name AS middle_name,u.type,d.value_desc AS delivery_type,p.value_desc AS pay_system FROM sc_shop_order AS o
			LEFT JOIN sc_users AS u ON u.u_id=o.userid
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_delivery_type') AS d ON d.field_value=o.delivery_type
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_pay_system') AS p ON p.field_value=o.pay_system
			WHERE 1=1 $cond 
			ORDER BY o.create_time DESC 
			LIMIT ".$pg->getBegin().",".$pg->per ;
		$queryStr="SELECT o.*, u.name AS u_name,u.phone AS u_phone, u.last_name AS last_name, u.first_name AS first_name, u.middle_name AS middle_name,u.type,d.value_desc AS delivery_type,p.value_desc AS pay_system,ps.value_desc AS pay_status FROM sc_shop_order AS o
			LEFT JOIN sc_users AS u ON u.u_id=o.userid
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_delivery_type') AS d ON d.field_value=o.delivery_type
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_pay_system') AS p ON p.field_value=o.pay_system
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_pay_status') AS ps ON ps.field_value=o.pay_status
			WHERE 1=1 $cond 
			ORDER BY o.create_time DESC 
			LIMIT ".$pg->getBegin().",".$pg->per ;
		$rs=$ST->select($queryStr);
		$data['rs']=array();
		while ($rs->next()) {
			$data['rs'][$rs->getInt('id')]=$rs->getRow();
			if($order_data=getJSON($rs->get('order_data'))){
				$data['rs'][$rs->getInt('id')]+=$order_data;
			}
			
			
//			$data['rs'][$rs->getInt('id')]['perforder']=$this->getPerfOrder($rs->getInt('id'));
		}
//		$data['delivery_type_list']=$this->delivery_type_list;
		$data['delivery_type_list']=$this->enum('sh_delivery_type');
		$data['pay_system_list']=$this->enum('sh_pay_system');
		$data['order_status']=$this->getOrderStatus();
		$data['status_list']=$this->renderStatusList();
		$data['pg']=$pg;
//		$data['pglist']=$this->renderPgList();
		$this->display($data,dirname(__FILE__).'/admin_order.tpl.php');	
	}
	
	function getPerfOrder($parent){
		global $ST;
//		$cond=" AND parentid=$parent";
		$queryStr="SELECT o.*, u.name AS u_name,u.phone AS u_phone, u.last_name AS last_name, u.first_name AS first_name, u.middle_name AS middle_name,u.type,d.value_desc AS delivery_type,p.value_desc AS pay_system FROM sc_shop_order AS o
			LEFT JOIN sc_users AS u ON u.u_id=o.userid
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_delivery_type') AS d ON d.field_value=o.delivery_type
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_pay_system') AS p ON p.field_value=o.pay_system
			WHERE 1=1 $cond 
			ORDER BY o.create_time DESC "
			 ;
//		$queryStr="SELECT o.*, u.name AS u_name,u.phone AS u_phone, u.last_name AS last_name, u.first_name AS first_name, u.middle_name AS middle_name,u.type,d.value_desc AS delivery_type FROM sc_shop_order AS o
//			LEFT JOIN sc_users AS u ON u.u_id=o.userid
//			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='delivery_type') AS d ON d.field_value=o.delivery_type
//			
//			WHERE 1=1 $cond 
//			ORDER BY o.create_time DESC "
//			 ;
		$out=null;
		$rs=$ST->select($queryStr);
		if($rs->next()){
			$out=$rs->getRow();
		}
			 
		return $out;
		
	}
	
//	public  $order_status=0;

	public $from='';
	public $to='';
	
	function actRemove(){	
		global $ST,$get;	
		$queryStr="DELETE FROM sc_shop_order WHERE id =".$get->get("id");
		$ST->executeDelete($queryStr);
		header("Location: .");exit;
	}
	
	function actChangeStatus(){
		global $post,$get;
		$this->option=$get->get('status');
		header("Location: .");exit;
//		$this->actDefault();
	}

	function renderStatusList(){
		global $ST;
		$order_status_list=$this->getOrderStatus();
		$order_status_count=array();
		
		$cond="";
		
		$q="SELECT COUNT(*) as c,order_status FROM sc_shop_order WHERE 1=1  $cond GROUP BY order_status";
		
		
		$rs=$ST->select($q);
		$all=0;
		while ($rs->next()) {
			$all+=$rs->getInt('c');
			$order_status_count[$rs->get('order_status')]=$rs->getInt('c');
		}
		$list['all']="Все [$all]";
		foreach ($order_status_list as $st=>$desc){
			$list[$st]=$desc;
			if(!empty($order_status_count[$st]))$list[$st].=" [$order_status_count[$st]]";
		}
		
		$data=array(
			'name'=>'order_status',
			'value'=>$this->getFilter('order_status'),
			'list'=>$list,
		);
		return $this->render($data,'template/common/select.tpl.php');
	}
	
	
	function actChangePageSize(){
		$this->setPages(intval($_GET['pages']));
		header("Location: ".$_SERVER['HTTP_REFERER']);exit;
	}
	
	function setFilter($k,$v){
		$_SESSION['admin_shop_filter'][$k]=$v;
	}
	function getFilter($k){
		if(isset($_SESSION['admin_shop_filter'][$k])){
			return $_SESSION['admin_shop_filter'][$k];
		}
		return '';
	}
	function actSearch(){
		global $post;
		
		$this->setFilter('from',$post->get('from'));
		$this->setFilter('to',$post->get('to'));
		$this->setFilter('order_status',$post->get('order_status'));
		
//		$this->from=$post->get('from');
//		$this->to=$post->get('to');
//		$this->order_status=$post->getInt('order_status');
		
		if($post->exists('export')){
			
			$this->export();
			return ;
		}
		header("Location: ".$_SERVER['HTTP_REFERER']);exit;
	}
	
	function export(){
		global $post,$ST;
		
		$cond=" WHERE 1=1";
		
		if($from=$this->getFilter('from')){
			$cond.=" AND create_time>='".dte($from,'Y-m-d')."'";
		}
		if($to=$this->getFilter('to')){
			$cond.=" AND create_time<='".dte($to,'Y-m-d')."'";
		}
		if(($order_status=$this->getFilter('order_status')) && $order_status!='all'){
			$cond.=" AND order_status={$order_status}";
		}
		
		$data=array(
			'rs'=>array(),
			'oi'=>array(),
		);
		$q="
			SELECT *,d.value_desc AS delivery_desc,p.value_desc AS pay_system_desc,s.value_desc AS order_status_desc FROM sc_shop_order o
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_delivery') AS d ON o.delivery_type=d.field_value
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='pay_system') AS p ON o.pay_system=p.field_value
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='order_status') AS s ON o.order_status=s.field_value
			
		$cond
			ORDER BY o.id
		";
		$data['rs']=$ST->select($q)->toArray();
		
		$q="SELECT i.name,o.id FROM sc_shop_item i,sc_shop_order o,sc_shop_order_item oi 
			$cond
			AND oi.orderid=o.id AND oi.itemid=i.id";
		
		$rs=$ST->select($q);
		while ($rs->next()) {
			$data['oi'][$rs->get('id')][]=$rs->get('name');
		}
		
		header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment; filename=ВЫГРУЗКА_ЗАКАЗОВ__'.$this->getFilter($from).'__'.$this->getFilter('to').'.xls');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		echo iconv('cp1251','utf-8',$this->render($data,dirname(__FILE__).'/export.xls.php'));
	}
		
	
	function actOrderItem(){
		
		global $ST,$get;
		$id=$get->getInt('id');
		$field=array(
			'create_time'=>'',
			'order_status'=>'',
			'complete_status'=>'',
			'comment'=>'',
			'review'=>'',
			'pay_time'=>null,
			'pay_system'=>'',
			'pay_bonus'=>0,
			'pay_account'=>'',
//			'pay_account_jur'=>'',
			'pay_status'=>'',

			'userid'=>0,
			'fullname'=>'',
			
			'price'=>'',
			'margin'=>0,
			'total_price'=>0,
			'discount'=>0,
			'driver'=>0,
			'delivery'=>0,
			'delivery_type'=>1,
			'date'=>'',
			'time'=>'',
			'country'=>'',
			'region'=>'',
			'city'=>'',
			'address'=>'',
			'order_data'=>'',
			'phone'=>'',
			'mail'=>'',
			'additionally'=>'',
		);

		if($id){
			$q="SELECT *, o.".implode(',o.',array_keys($field))."
			
				, u.address AS u_address
				, u.city  AS u_city 
				, u.phone  AS u_phone 
				, u.address AS u_address
				, u.mail AS u_mail
				
				
				FROM sc_shop_order AS o 
				LEFT JOIN sc_users AS u ON u.u_id=userid 
				WHERE id=".$id;
			$rs=$ST->select($q);
			if($rs->next()){
				$field=$rs->getRow();
				
				if($field['order_data']){
					if($field['order_data']=@getJSON($field['order_data'])){
						$field=array_merge($field,$field['order_data']);
					}
				}
				$field['time_list']=$this->enum('sh_delivery_time');
//				$rs=$ST->select("SELECT * FROM sc_shop_delivery_time ORDER BY time_to");
//				while ($rs->next()) {
//					$field['time_list'][$rs->get('time_to')]=$rs->get('comment');
//				}
				if(isset($field['time_list'][$field['time']])){
					$field['time']=$field['time_list'][$field['time']];
				}
			}
		}
		$field['id']=$id;
		$field['performer']='';
		$field['order_status_list']=$this->getOrderStatus();
		$field['delivery_type_list']=$this->enum('sh_delivery_type');
		//Другие заказы
		$rs=$ST->select("SELECT * FROM sc_shop_order WHERE userid>0 AND userid=".$field['userid']." ORDER BY create_time DESC");
		$field['client_orders']=array();
		while ($rs->next()) {
			$field['client_orders'][]=$rs->getRow();
		}
		$field['user_discount']=0;
		$rs=$ST->select("SELECT discount FROM sc_users WHERE u_id={$field['userid']}");
		if($rs->next()){
			$field['user_discount']=$rs->get('discount');
		}
		
//		$field['client_discount']=0;//floatval($ST->getOne("SELECT s_discount FROM sc_users WHERE u_id=".$field['userid']));
		
	
		$field['perf_list']=$ST->select("SELECT * FROM sc_users WHERE type='partner'")->toArray();
		$field['driver_list']=$ST->select("SELECT * FROM sc_users WHERE type='courier'")->toArray();
		
				
		$field['pay_system_list']=$this->enum('sh_pay_system');
		
		$field['pay_status_list']=$this->enum('sh_pay_status');
		
//		$c_data=array(
//			'orderContent'=>$basket,
//			'edit'=>true,
//		);
		
//		$field['orderContent']=$this->render($c_data,dirname(__FILE__).'/admin_ordercontent.tpl.php');
		
		$field['edit']=true;
		
		if(in_array($this->getUser('type'),array('courier'))){
			$field['edit']=false;
		}
		$field['orderContent']=$this->renderOrderContent($id,$field);
		$this->setPageTitle('Заказ '.$field['ordernum']);
		$this->display($field,dirname(__FILE__).'/admin_order_item.tpl.php');
	}
	
	function actSetDiscount(){
		global $ST,$post;
		
		$ST->update('sc_users',array('discount'=>$post->getFloat('user_discount')),"u_id={$post->getInt('userid')}");
		
		echo printJSON(array('msg'=>'ok'));exit;
	}
	
	function actRenderOrderContent(){
		global $post;
		echo $this->renderOrderContent($post->getInt('id'),$post->get());
	}
	
	
	function actReplaceOrderContent(){
		global $ST,$post;
		
		if($order_id=$post->getInt('order_id')){
			$margin=0;
			$rs=$ST->select("SELECT margin FROM sc_shop_order WHERE id =$order_id");
			if($rs->next()){
				$margin=$rs->get('margin');
				if($item=$ST->selectRow("SELECT * FROM sc_shop_item WHERE id={$post->getInt('new_id')}")){
					$rs1=$ST->select("SELECT * FROM sc_shop_order_item WHERE orderid=$order_id AND itemid={$item['id']}");
					if(!$rs1->next()){
						$ST->insert('sc_shop_order_item',array('orderid'=>$order_id,'count'=>1,'itemid'=>$item['id'],'price'=>$item['price']+$item['price']/100*$margin));
					}
				}
				if($old_id=$post->getInt('old_id')){
					$ST->delete('sc_shop_order_item',"id=$old_id");
				}
			}
		}
		
//		if($old_id=$post->getInt('old_id')){
//			$margin=0;
//			$rs=$ST->select("SELECT margin FROM sc_shop_order WHERE id IN(SELECT orderid FROM sc_shop_order_item WHERE id=$old_id)");
//			if($rs->next()){
//				$margin=$rs->get('margin');
//			}
//			$item=$ST->selectRow("SELECT * FROM sc_shop_item WHERE id={$post->getInt('new_id')}");
//			
//			
//			if($item){
//				$ST->update('sc_shop_order_item',array('itemid'=>$item['id'],'price'=>$item['price']+$item['price']/100*$margin),"id=$old_id");
//			}
//		}
		echo printJSON(array('msg'=>'ok'));exit;
	}
	
	function actReplaceOrderContentNmn(){
		global $ST,$post;
		if($old_id=$post->getInt('old_id')){
			$rs=$ST->select("SELECT * FROM sc_shop_item_nmn WHERE description='".sql::slashes($post->get('txt'))."'");
			if($rs->next()){
				$nmn_id=$rs->getInt('id');
			}else{
				$nmn_id=$ST->insert('sc_shop_item_nmn',array('description'=>$post->get('txt'),'hidden'=>1));
			}
			$ST->update('sc_shop_order_item',array('nmn'=>$nmn_id),"id=$old_id");
		}
		echo printJSON(array('msg'=>'ok'));exit;
	}
	
	function renderOrderContent($id,$d=array()){
		global $ST;
		$orderItem=array();
		$rs=$ST->select("SELECT oi.*,i.name,i.img,i.description,nmn.description as nmn_description
			FROM sc_shop_order_item AS oi 
			LEFT JOIN sc_shop_item AS i ON i.id=oi.itemid  
			LEFT JOIN sc_shop_item_nmn AS nmn ON nmn.id=oi.nmn  
			WHERE oi.orderid=".$id);
		while ($rs->next()) {
			
			$itm=$rs->getRow();
			
			if(isset($d['price'][$itm['id']])){
				if(empty($d['old_id']) || $d['old_id']!==$itm['id']){
					$itm['price']=$d['price'][$itm['id']];
				}
				
			}
			if(isset($d['count'][$itm['id']])){
				$itm['count']=$d['count'][$itm['id']];
			}
			
			$itm['sum']=$itm['count']*$itm['price'];
			
			
			
			$orderItem[]=$itm;
		}
		$basket=new Basket($orderItem);
		$basket->delivery=$d['delivery'];
		$basket->discount=$d['discount'];
		$c_data=array(
			'orderContent'=>$basket,
			'edit'=>true,
		);
		if(isset($d['edit'])){
			$c_data['edit']=$d['edit'];
		}
		if(isset($d['perf'])){
			$c_data['perf']=$d['perf'];
		}
		return $this->render($c_data,dirname(__FILE__).'/admin_ordercontent.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		
//		$this->logData('saveOrder',$post);
		
		$id=$post->getInt('id');
		$out['msg']='Сохранено';
		
		$data=array(
			'comment'=>$post->get('comment'),
			'order_status'=>$post->get('order_status'),
			'delivery_type'=>$post->get('delivery_type'),
			'driver'=>$post->getInt('driver'),
		);
		
		if($post->get('country')){$data['country']=$post->get('country');}
		if($post->get('region')){$data['region']=$post->get('region');}
		if($post->get('city')){$data['city']=$post->get('city');}
		if($post->get('address')){$data['address']=$post->get('address');}
		if($post->get('phone')){$data['phone']=$post->get('phone');}
		if($post->get('additionally')){$data['additionally']=$post->get('additionally');}
		if($post->exists('pay_status')){$data['pay_status']=$post->get('pay_status');}
		if($post->exists('pay_system')){$data['pay_system']=$post->get('pay_system');}
		
		
		if(!in_array($post->get('order_status'),array(0))){//заказ не новый, то есть принят
			$data['start_time']=date('Y-m-d H:i:s');
			$out['start_time']=$data['start_time'];
		}
		if(in_array($post->get('order_status'),array(4,5))){//array(2,-1,-2)
			$data['stop_time']=date('Y-m-d H:i:s');
			$out['stop_time']=$data['stop_time'];
		}
		
		$rs=$ST->select("SELECT * FROM sc_shop_order WHERE id=$id");//Сохранение заказа
		$userid=0;
		if($rs->next()){
			$userid=$rs->getInt('userid');
			$order=$rs->getRow();
			
			$this->logData('order',$order);
		}
		if($post->exists('delivery')){
			$data['delivery']=$post->getInt('delivery');
		}
		if($post->exists('discount')){
			$data['discount']=$post->getInt('discount');
		}
		if($post->exists('order_price')){
			$data['price']=$post->getFloat('order_price');
		}
		if($post->exists('total_price')){
			$data['total_price']=$post->getFloat('total_price');
		}
		
		
		$ST->update('sc_shop_order',$data,"id=".$id);
		if($count=$post->getArray('count')){
			$price=$post->getArray('price');
			foreach ($count as $key=>$c) {
						$d=array(
						'count'=>(float)$count[$key],
						'price'=>(float)$price[$key],
					);
					$ST->update('sc_shop_order_item',$d,"id=$key");
			}	
		}
		
		
		
		if($post->exists('save_with_notice')){
			$order_status=$this->getOrderStatus();
			$delivery_type=$this->enum('delivery_type');
			$data=array(
				'name'=>$post->get('name'),
				'order_num'=>$post->get('ordernum'),
				'status'=>$order_status[$post->get('order_status')],
				'msg'=>'',
			);
			if($post->getInt('delivery_type')){
				$data['status'].=" ".@$delivery_type[$post->getInt('delivery_type')];
			}
			
			$files=array();
			if($post->get('order_status')==1){
				$company='';
				$rs=$ST->select("SELECT company FROM sc_users WHERE u_id IN(SELECT userid FROM sc_shop_order WHERE id=$id)");
				if($rs->next()){
					$company=trim($rs->get('company'));
				}
				if(!$company){
					$data['msg']='Для оплаты';
				}else{
					$data['msg']='Для получения выставленного счёта';
					$files[]=array(
						'name'=>'Счёт для оплаты.pdf',
						'file'=>'http://'.$_SERVER['HTTP_HOST']."/prnt/ShetPDF?id={$id}&access=allow"
					);
				}
				$data['msg'].=' перейдите в <a href="http://'.$_SERVER['HTTP_HOST'].'/shop/">личный кабинет</a>';
				
			}
			if($post->get('order_status')==2){
				$data['msg']='Благодарим за использования нашего сервиса';
			}
		
			$this->sendTemplateMail($post->get('mail'),'notice_order_change_status',$data,$files);
		}
				
		echo printJSON($out);exit;
	}
	
//	function getPayStatus(){
//		global $ST;
//		$rs=$ST->select("SELECT * FROM sc_enum WHERE field_name='pay_status'");
//		$pay_status=array();
//		while ($rs->next()) {
//			$pay_status[$rs->get('field_value')]=$rs->get('value_desc');
//		}
//		return $pay_status;
//	}
	
	function getOrderStatus(){
		return $this->enum('sh_order_status');
	}

	function actPrint(){
		global $ST,$get;
		$mode=$get->get('mode');
		
		$data=array();
		
		$ids=$get->getArray('item');
		$condition="id IN(".implode(',',$ids).")";
		
		$queryStr="SELECT *,o.*, u.mail,u.address AS u_address,u.mail AS u_mail,u.phone AS u_phone, ps.value_desc AS pay_system, pst.value_desc AS pay_status, d.name AS d_name,u.name AS name  FROM sc_shop_order AS o 
			LEFT JOIN sc_users AS u ON u.u_id=o.userid
			LEFT JOIN sc_users AS d ON d.u_id=o.driver
			
			LEFT JOIN(SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_pay_system') AS ps ON ps.field_value=o.pay_system
			LEFT JOIN(SELECT field_value,value_desc FROM sc_enum WHERE field_name='sh_pay_status') AS pst ON pst.field_value=o.pay_status
			
			WHERE $condition 
			ORDER BY o.create_time DESC ";
		$rs=$ST->select($queryStr);
		$data['rs']=array();
		while ($rs->next()) {
			$data['rs'][$rs->getInt('id')]=$rs->getRow();
			
			
			$orderItem=array();
			$rs1=$ST->select("SELECT oi.*,i.name,i.img,i.description FROM sc_shop_order_item AS oi LEFT JOIN sc_shop_item AS i ON i.id=oi.itemid  WHERE oi.orderid=".$rs->getInt('id'));
			while ($rs1->next()) {
				$orderItem[]=array(
					'id'=>$rs1->get('id'),
					'img'=>null,
					'name'=>$rs1->get('name'),
					'description'=>$rs1->get('description'),
					'count'=>$rs1->get('count'),
					'price'=>$rs1->get('price'),
					'comment'=>$rs1->get('comment'),
				);
			}
			
			if($mode=='collect'){
				$packets=array(20848,20847,20845);
				if($p=$this->cfg('SHOP_PACKETS')){
					$packets=explode(',',$p);
				}
				if($packets){
					$rs1=$ST->select("SELECT i.name,i.price,i.img,i.description FROM sc_shop_item i WHERE i.id IN (".implode(',',$packets).")");
					while ($rs1->next()) {
						$orderItem[]=array(
							'id'=>$rs1->get('id'),
							'img'=>null,
							'name'=>$rs1->get('name'),
							'description'=>$rs1->get('description'),
							'count'=>'',
							'price'=>$rs1->get('price'),
							'comment'=>'',
						);
					}
				}
					
				
			}
			
			$basket=new Basket($orderItem);
			$basket->delivery=$rs->get('delivery');
			$basket->discount=$rs->get('discount');
			
			$tpl="ordercontent_print.tpl.php";
			if($mode=='collect'){
				$tpl="ordercontent_collect_print.tpl.php";
			}
			
			
			$data['rs'][$rs->getInt('id')]['orderContent']=$this->render(array('orderContent'=>$basket),dirname(__FILE__).'/'.$tpl);	
		}
		
		$this->setTitle('Печать заказа');
		$this->tplContainer="core/tpl/admin/admin_print.php";
		$tpl="admin_order_print.tpl.php";
			if($mode=='collect'){
				$tpl="admin_order_collect_print.tpl.php";
			}
		$this->display($data,dirname(__FILE__).'/'.$tpl);
	}
	
	function actConfig(){
		global $ST;
		$rs=$ST->select("SELECT * FROM sc_config WHERE name LIKE 'SHOP%'");
		$data=array('rs'=>$rs);
		
		$this->setTitle('Настройки');
		$this->explorer[]=array('name'=>'Настройки');
		$this->display($data,dirname(__FILE__).'/admin_config.tpl.php');
		
	}
	function actSaveConfig(){
		global $ST,$post;
		foreach ($post->get() as $k=>$v){
			$ST->update('sc_config',array('value'=>$v),"name='".$k."'");
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	function actDelivTime(){
		global $ST;
		$rs=$ST->select("SELECT * FROM sc_shop_delivery_time ORDER BY time_to");
		$data=array('rs'=>$rs);
		
		$this->setTitle('Настройки времени доставки');
		$this->explorer[]=array('name'=>'Настройки времени доставки');
		$this->display($data,dirname(__FILE__).'/admin_deliv_time.tpl.php');
	}
	function actSaveDelivTime(){
		global $ST,$post;
		$ST->exec('TRUNCATE TABLE sc_shop_delivery_time');
		$comment=$post->getArray('comment');
//		$time_from=$post->getArray('time_from');
		$time_to=$post->getArray('time_to');
		foreach ($comment as $k=>$v){
			$ST->insert('sc_shop_delivery_time',array('comment'=>$comment[$k],'time_to'=>$time_to[$k],));
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	function actDeliv(){
		global $ST;
		$rs=$ST->select("SELECT * FROM sc_shop_delivery ORDER BY summ");
		$data=array('rs'=>$rs);
		
		$this->setTitle('Настройки стоимости доставки');
		$this->explorer[]=array('name'=>'Настройки стоимости доставки');
		$this->display($data,dirname(__FILE__).'/admin_deliv.tpl.php');
	}
	function actSaveDeliv(){
		global $ST,$post;
		$ST->exec('TRUNCATE TABLE sc_shop_delivery');
		$summ=$post->getArray('summ');
		$price=$post->getArray('price');
		foreach ($summ as $k=>$v){
			if(trim($summ[$k])!==''){
				$ST->insert('sc_shop_delivery',array('summ'=>(int)trim($summ[$k]),'price'=>(int)$price[$k]),null);
			}
			
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	function actStatistic(){
		$this->setTitle('Статистика');
		$this->explorer[]=array('name'=>'Статистика');
		global $ST;
		
		$data['date_from']=date('Y-m-d',time()-3600*24*30*2);
		$data['date_to']=date('Y-m-d');
		
		$rs=$ST->select("SELECT * FROM sc_shop_item WHERE views>0 ORDER BY views DESC LIMIT 20")->toArray();
		$data['goods_views']=$rs;
		$rs=$ST->select("SELECT i.id,i.product,i.name,count(i.id) as c 
			FROM sc_shop_item i,sc_shop_order_item oi 
			WHERE oi.itemid =i.id GROUP BY i.id,i.product,i.name 
			ORDER BY c DESC LIMIT 20")->toArray();
		$data['goods_orders']=$rs;
		$this->display($data,dirname(__FILE__).'/admin_statistic.tpl.php');
	}
	function actStatSale(){
		global $ST,$post;
		$format="%Y-%m";
		if($post->get('group')=='day'){
			$format="%Y-%m-%d";
		}
		$q="SELECT SUM(total_price) AS s,DATE_FORMAT(create_time,'$format') AS t FROM sc_shop_order 
		WHERE create_time BETWEEN '".dte($post->get('date_from'),'Y-m-d')."' AND '".dte($post->get('date_to'),'Y-m-d')." 23:59:59' 
		GROUP BY DATE_FORMAT(create_time,'$format')
		ORDER BY t DESC
		" ;
		$rs=$ST->select($q);
		$data['rs']=$rs->toArray();
		echo $this->render($data,dirname(__FILE__)."/stat_sale.tpl.php");
	}
	function actStatGoodsSale(){
		global $ST,$post;
		
		$q="SELECT COUNT(i.id) AS c,i.id,i.name FROM sc_shop_order o,sc_shop_order_item oi,sc_shop_item i  
		WHERE o.create_time BETWEEN '".dte($post->get('date_from'),'Y-m-d')."' AND '".dte($post->get('date_to'),'Y-m-d')." 23:59:59'
		AND oi.orderid=o.id AND oi.itemid=i.id
		 
		GROUP BY i.id,i.name
		ORDER BY c DESC
		" ;
		$rs=$ST->select($q);
		$data['rs']=$rs->toArray();
		echo $this->render($data,dirname(__FILE__)."/stat_goods_sale.tpl.php");
	}
	function actStatBrandSale(){
		global $ST,$post;
		
		$q="SELECT COUNT(m.id) AS c,m.id,m.name FROM sc_shop_order o,sc_shop_order_item oi,sc_shop_item i,sc_manufacturer m  
		WHERE o.create_time BETWEEN '".dte($post->get('date_from'),'Y-m-d')."' AND '".dte($post->get('date_to'),'Y-m-d')." 23:59:59'
		AND oi.orderid=o.id AND oi.itemid=i.id AND i.manufacturer_id=m.id
		 
		GROUP BY m.id,m.name
		ORDER BY c DESC
		" ;
		$rs=$ST->select($q);
		$data['rs']=$rs->toArray();
		echo $this->render($data,dirname(__FILE__)."/stat_brand_sale.tpl.php");
	}
	
}
?>