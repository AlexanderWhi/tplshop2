<?
include_once 'core/component/Component.class.php';
class Ymap extends Component  {
	function actDefault(){
		global $ST;
		
		$data=array(
			'zone_data'=>'[]',
			'delivery_zone_list'=>$ST->select("SELECT * FROM sc_shop_delivery_zone ")->toArray(),
		);
		$rs=$ST->select("SELECT * FROM sc_shop_delivery_zone_data ORDER BY time DESC limit 1");
		if($rs->next()){
			$data['zone_data']=$rs->get('data');
		}
		$data['delivery_zone_color']=array();
		foreach ($data['delivery_zone_list'] as $row) {
			$data['delivery_zone_color'][$row['id']]=$row['color'];
		}
		$this->display($data,dirname(__FILE__).'/ymap.tpl.php');
	}
	
	function actGetDescrByZone(){
		global $get;
		
		echo BaseComponent::getText('delivery_zone_'.$get->get('id'));
		
	}
	function actDriver(){
		
		$data=array();
		$this->setContainer('simple_container.tpl.php');
		$this->display($data,dirname(__FILE__).'/driver.tpl.php');
	}
	
	function actSetPos(){
		global $ST,$post;
		if($this->getUserId()){
			$ST->update('sc_users',array('district'=>implode(',',$post->get('point'))),"u_id={$this->getUserId()}");
		}
		
		echo 'ok';exit;
	}
	function actGetPos(){
		global $ST,$post;
		$d='';
		$rs=$ST->select("SELECT u.district FROM sc_shop_order o,sc_users u WHERE o.driver=u.u_id AND o.order_status=2 AND o.id={$post->getInt('order_num')}");
		if($rs->next()){
			$d=$rs->get('district');
		}
		echo printJSON(array('point'=>$d));exit;
		
	}
}
?>