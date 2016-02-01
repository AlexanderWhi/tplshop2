<?php
include_once("core/component/Component.class.php");
class Shop extends Component {
	
	function __construct(){
		$this->tplLeftComponent="modules/cabinet/cabinet_left.tpl.php";
//		$this->setCommonCont();
	}
	
	function actDefault(){
		$this->actOrder(null);
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

	function getOrder($arh=false,$id=0){
		include('modules/catalog/Basket.class.php');
		global $ST;
		$status_list=$this->getOrderStatus();
		$data=array('arh'=>$arh,'ps'=>array());
		
		$condition="userid=".$this->getUserId()."";
		if($arh!==null){
			if($arh){
				$condition.=" AND order_status NOT IN (0,1)";
			}else{
				$condition.=" AND order_status IN (0,1)";
			}
		}
		if($id){
			$condition.=" AND id=$id";
		}

		/*$ps=array();
		$rs=$ST->select("SELECT * FROM sc_pay_system");
		while ($rs->next()) {
			$class="PS".ucfirst($rs->get('name'));
			$ps[$rs->get('name')]=new $class(unserialize($rs->get('config')));
//			$ps[$rs->get('name')]->setType('pay');
//			$ps[$rs->get('name')]->setUserId($this->getUserId());
//			$ps[$rs->get('name')]->setEmail($this->getUser('mail'));
		}
		$ps['payonline']->ReturnUrl='/cabinet/order/';
		$ps['payonline']->params['UserId']=$this->getUserId();;
			
		$data['ps']=&$ps;*/
		
		$pg=new Page(5);
		
		$rs=$ST->select("SELECT COUNT(id) AS c FROM sc_shop_order WHERE $condition");
		if($rs->next()){
			$pg->all=$rs->getInt('c');
		}
				
		$queryStr="SELECT * FROM sc_shop_order  
			WHERE $condition 
			ORDER BY id DESC LIMIT ".$pg->getBegin().', '.$pg->per;
		$rs=$ST->select($queryStr);
		$data['rs']=array();
		while ($rs->next()) {
			$data['rs'][$rs->getInt('id')]=$rs->getRow();
			$data['rs'][$rs->getInt('id')]['order_status_desc']=@$status_list[$rs->get('order_status')];
			
			$orderItem=array();
			$rs1=$ST->select("SELECT oi.*,i.name,i.img,i.description,i.category
				FROM sc_shop_order_item AS oi
				
				LEFT JOIN sc_shop_item AS i ON i.id=oi.itemid
				LEFT JOIN sc_shop_catalog AS c ON c.id=i.category
				LEFT JOIN sc_shop_proposal AS p ON oi.proposalid=p.id  
				WHERE  oi.orderid=".$rs->getInt('id'));
			while ($rs1->next()) {
				
				$orderItem[]=array(
					'id'=>$rs1->get('itemid'),
					'proposalid'=>$rs1->get('proposalid'),
					'img'=>$rs1->get('img'),
					'name'=>$rs1->get('name'),
					'description'=>$rs1->get('description'),
					'count'=>$rs1->get('count'),
					'price'=>$rs1->get('price'),
					'sum'=>$rs1->get('price')*$rs1->get('count'),
//					
				);
			}
			$basket=new Basket($orderItem);
			$basket->delivery=$rs->get('delivery');
			$basket->discount=$rs->get('discount');
			$data['rs'][$rs->getInt('id')]['orderContent']=$this->render(array('orderContent'=>$basket,'date'=>$rs->get('create_time'),'arh'=>$arh,'id'=>$rs->getInt('id')),dirname(__FILE__).'/ordercontent.tpl.php');
			
//			foreach ($ps as $ps_name=>$pay_system) {
////				$pay_system->setOrderNum($rs->getInt('id'));
////				$pay_system->setSumm($rs->getInt('total_price'));
//				$data['rs'][$rs->getInt('id')]['ps'][$ps_name]=$pay_system;
//			}
		}
		
		$data['pg']=$pg;
		return $data;
	}
	
	function actOrder($arh=false){
		global $get;
		$this->needAuth();
		$id=$get->getInt('ShpOrderId');
		$data=$this->getOrder($arh,$id);
		$data['delivery_type_list']=$this->enum('sh_delivery_type');
		$data['pay_system_list']=$this->enum('sh_pay_system');
		
		$data['orderId']=$id;
//		$this->setCommonCont();
		$this->display($data,dirname(__FILE__).'/order.tpl.php');
	}
	
	function actArchives(){
		$this->needAuth();
		$data=$this->getOrder(true);
		$this->setPageTitle('Архив заказов');
		$data['delivery_type_list']=$this->enum('sh_delivery_type');
		$data['pay_system_list']=$this->enum('sh_pay_system');
		$data['orderId']=0;
		$this->display($data,dirname(__FILE__).'/order.tpl.php');
	}
	
	
	function actMakeBasket(){
		global $ST,$get;
		$rs=$ST->select("SELECT * FROM sc_shop_order_item WHERE orderid=".$get->getInt('id'));
		$res=array();
		while ($rs->next()) {
			
			$this->addBasket($rs->get('itemid'),$rs->get('count'),$rs->get('proposalid'));
			
//			$res['id'.$rs->get('itemid')]=$rs->get('count');
		}
//		setcookie('basket',json_encode($res),COOKIE_EXP,'/');
		
		header('Location: /catalog/basket/');exit;
	}
	
	function actReview(){
		global $ST,$post;
		$ST->update('sc_shop_order',array('review'=>$post->get('review')),"id={$post->getInt('id')} AND userid={$this->getUserId()}");
		echo printJSON(array('msg'=>'Отзыв сохранён'));exit;
	}
	
	function actPerform(){
		global $ST,$get;
		
		$orderId=$this->getURIIntVal('perform');
		$rs=$ST->select("SELECT * FROM sc_shop_order WHERE id=$orderId");
		if($rs->next()){
			$order=$rs->getRow();
			if($perfId=$rs->getInt('perfid')){
				$rs=$ST->select("SELECT * FROM sc_users WHERE u_id=$perfId");
				if($rs->next()){
					$perf=$rs->getRow();
					//$new_id.$partner['u_id'].$partner['password']
					if($get->get('key')==md5($orderId.$perfId.$perf['password'])){
						$ST->update("sc_shop_order",array('order_status'=>2,'delivery_type'=>$get->getInt('delivery_type')),"id IN($orderId,{$order['parentid']})");
						
						$order['delivery_type']=$get->getInt('delivery_type');
						$order['delivery_type_list']=$this->enum('delivery_type');
						$this->display($order,dirname(__FILE__).'/perform.tpl.php');
					}
					
				}
			}
		}
	}
}
?>