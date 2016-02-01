<?php
include_once("core/component/Component.class.php");

class Catsrv extends Component {
	
	function actOrder1c(){
		global $ST,$get;
		
		$login=$get->get('login');
		$password=$get->get('password');
		$q="SELECT * FROM sc_users WHERE type='admin' 
			AND login='".SQL::slashes($login)."'
			AND password=PASSWORD('".SQL::slashes($password)."')";
		
		$rs=$ST->select($q);
		
		if(!$rs->next()){
			exit;
		}
		
		$data=array(
			'make_date'=>date('Y-m-d'),
			'make_time'=>date('H:i:s'),
			'document'=>array()
		);
		$date_from=date('Y-m-d');
		
		
		$date_to=date('Y-m-d',time()+3600*24);
		if($get->get('date_from')){
			$date_from=$get->get('date_from');
		}
		
		if($get->get('date_to')){
			$date_to=$get->get('date_to');
		}
		
		
		$q="SELECT * FROM sc_shop_order o,sc_users u WHERE u.u_id=o.userid
			AND o.create_time>='$date_from' AND o.create_time<='$date_to'
		";
		$rs=$ST->select($q);
		while ($rs->next()) {
			$d=array(
				'id'=>$rs->get('id'),
				'num'=>$rs->get('id'),
				'date'=>dte($rs->get('create_time'),'Y-m-d'),
				'summ'=>$rs->get('total_price'),
				'contragent'=>array(
					'id'=>$rs->get('u_id').'#'.$rs->get('login').'#'.$rs->get('name'),
					'name'=>$rs->get('type')=='user_jur'?$rs->get('company'):$rs->get('name'),
					'address'=>$rs->get('address'),
					'mail'=>$rs->get('mail'),
				),
				
				'time'=>dte($rs->get('create_time'),'H:i:s'),
				'additionally'=>$rs->get('additionally'),
				'goods'=>array(),
			);
			$q="SELECT *,ec.id AS ext_cat_id, oi.price AS price 	
			FROM sc_shop_order_item AS oi, sc_shop_item AS si
			LEFT JOIN sc_shop_srv_extcat AS ec ON ec.lnk=si.category
			WHERE
				si.id=oi.itemid
				AND oi.orderid={$rs->get('id')} 
				
			";
			$q="SELECT *, oi.price AS price 	
			FROM sc_shop_order_item AS oi, sc_shop_item AS si
			
			WHERE
				si.id=oi.itemid
				AND oi.orderid={$rs->get('id')} 
				
			";
			
			$rs1=$ST->select($q);
			while ($rs1->next()) {
				$g=array(
					'name'=>$rs1->get('name'),
					'id'=>$rs1->get('ext_id'),
//					'cat_id'=>$rs1->get('ext_cat_id'),
					'price'=>$rs1->get('price')/$rs1->get('count'),
					'count'=>$rs1->get('count'),
					'summ'=>$rs1->get('price'),
				
				);
				$d['goods'][]=$g;
			}
			
			$data['document'][]=$d;
			
		}
		if($data['document']){
			echo '<?xml version="1.0" encoding="windows-1251"?>';
			echo $this->render($data,dirname(__FILE__).'/order1c.xml.php');exit;
		}else{
			echo 'Нет заказов';
		}
	}
}
?>