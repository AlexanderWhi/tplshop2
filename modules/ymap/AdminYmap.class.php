<?
include_once 'core/component/AdminComponent.class.php';
class AdminYmap extends AdminComponent  {
	protected $mod_name='Зоны доставки';
	protected $mod_title='Зоны доставки';
	function actDefault(){
		global $ST;
		$data=array(
			'delivery_zone_list'=>$ST->select("SELECT * FROM sc_shop_delivery_zone ")->toArray(),
			'delivery_zone_data_list'=>$ST->select("SELECT time FROM sc_shop_delivery_zone_data ")->toArray(),
		);
		
		$data['delivery_zone_color']=array();
		foreach ($data['delivery_zone_list'] as $row) {
			$data['delivery_zone_color'][$row['id']]=$row['color'];
		}
		
		
		$this->display($data,dirname(__FILE__).'/admin_ymap.tpl.php');
	}
	
	
	function actDeliveryZone(){
		global $ST;
		$rs=$ST->select("SELECT * FROM sc_shop_delivery_zone WHERE 1=1 ORDER BY id");
		$data=array('rs'=>$rs->toArray());
		$this->setPageTitle('Список зон');
			
		$this->display($data,dirname(__FILE__).'/admin_delivery_zone.tpl.php');
	}
	
	function actSaveZone(){
		global $ST,$post;
		
		$ids=$post->getArray('id');
		$name=$post->getArray('name');
		$color=$post->getArray('color');
		$smm_cfg=$post->getArray('smm_cfg');
		
		if($ids){
			foreach ($ids as $n=>$id) {
				$data=array(
							'name'=>$name[$n],
							'color'=>$color[$n],
							'smm_cfg'=>$smm_cfg[$n],
						);
				if(intval($id)){
					$rs=$ST->select("SELECT * FROM sc_shop_delivery_zone WHERE id=".intval($id));
					
					if($rs->next()){
						$ST->update('sc_shop_delivery_zone',$data,"id={$rs->getInt('id')}");
					}else{
						$data['id']=$id;
						$ST->insert('sc_shop_delivery_zone',$data);
					}
				}else{
					$ids[]=$ST->insert('sc_shop_delivery_zone',$data);
				}			
			}
			$ST->delete('sc_shop_delivery_zone',"id NOT IN(".implode(',',$ids).")");
		}
		
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	
	function actSave(){
		global $ST,$post;
		$data=array('data'=>$post->get('data'));
		$d=json_decode($post->get('data'),true);
		foreach ($d as $k=>&$dd) {
			foreach ($dd['Coordinates'] as $n=>&$t) {
				if(empty($t)){
					unset($dd['Coordinates'][$n]);
				}
			}
			if(empty($dd['Coordinates'])){
				unset($d[$k]);
			}
		}
		$data=array('data'=>json_encode(array_values($d)));
		if($post->get('time')){
			
		}else{
			$data['time']=date('Y-m-d H:i:s');
			$ST->insert('sc_shop_delivery_zone_data',$data);
		}
		$out=array(
			'time_list'=>array(),
		);
		$rs=$ST->select("SELECT time FROM sc_shop_delivery_zone_data");
		while ($rs->next()) {
			$out['time_list'][]=$rs->get('time');
		}
		
		
		echo printJSON(array('msg'=>'Сохранено')+$out);exit;
	}	
	function actRemove(){
		global $ST,$post;
		$data=array('data'=>$post->get('data'));
		
		if($post->get('time')){
			$ST->delete('sc_shop_delivery_zone_data',"time='{$post->get('time')}'");
		}
		$out=array(
			'time_list'=>array(),
		);
		$rs=$ST->select("SELECT time FROM sc_shop_delivery_zone_data");
		while ($rs->next()) {
			$out['time_list'][]=$rs->get('time');
		}
		
		
		echo printJSON(array('msg'=>'Удалено!')+$out);exit;
	}
	
	function actGetZoneData(){
		global $ST,$get;
		
		$rs=$ST->select("SELECT * FROM sc_shop_delivery_zone_data WHERE time='{$get->get('time')}'");
		if($rs->next()){
			echo $rs->get('data');exit;
		}
	}
}
?>