<?php
include_once 'core/component/BaseComponent.class.php';
class OperatorComponent extends BaseComponent{
	/**
	 * Основные настройки компоненты
	 */
	protected  $tplContainer="modules/operator/operator_container.tpl.php";
	
	protected $new_order_count=0;
	
	protected $adminMenu=array(
		array("label"=>'Сайт','url'=>"/"),
	);
	
	protected $treeMap=null;
	
	function updateTree(){
		global $ST;
		$queryStr="SELECT COUNT(*) AS c,order_status FROM sc_shop_order WHERE perfid IN (0,".$this->getUserId().") GROUP BY order_status";
	 	$rs=$ST->select($queryStr);
		$status_count=array();
	 	while ($rs->next()) {
	 		$status_count[$rs->get('order_status')]=$rs->get('c');
	 	}
		$queryStr="SELECT COUNT(*) AS c FROM sc_shop_order WHERE perfid IN (0,".$this->getUserId().") ";
	 	$rs=$ST->select($queryStr);
	 	if ($rs->next()) {
	 		$status_count[0]=$rs->get('c');
	 	}
	 	$treeMap=array();
	 				
	 	$treeMap[]=array('name'=>'Создать заказ','url'=>'/operator/order/');
	 	$treeMap[]=array('name'=>'Список путевых листов','url'=>'/operator/waybillList/');
	 	$treeMap[]=array('name'=>'ВСЕ'.(isset($status_count[0])?' ('.$status_count[0].')':''),'url'=>'/operator/');
	 	$treeMap[]=array('name'=>'Новые'.(isset($status_count[1])?' ('.$status_count[1].')':''),'url'=>'/operator/new/');
	 	$treeMap[]=array('name'=>'Оформленные'.(isset($status_count[2])?' ('.$status_count[2].')':''),'url'=>'/operator/received/');
	 	$treeMap[]=array('name'=>'В сборке'.(isset($status_count[3])?' ('.$status_count[3].')':''),'url'=>'/operator/assembly/');
	 	$treeMap[]=array('name'=>'Согласование'.(isset($status_count[4])?' ('.$status_count[4].')':''),'url'=>'/operator/agreement/');
	 	$treeMap[]=array('name'=>'Формирование путевого листа'.(isset($status_count[5])?' ('.$status_count[5].')':''),'url'=>'/operator/waybill/');
	 	$treeMap[]=array('name'=>'Ожидание доставки'.(isset($status_count[6])?' ('.$status_count[6].')':''),'url'=>'/operator/waiting/');
	 	$treeMap[]=array('name'=>'В пути'.(isset($status_count[7])?' ('.$status_count[7].')':''),'url'=>'/operator/delivery/');
	 	$treeMap[]=array('name'=>'Доставлен'.(isset($status_count[8])?' ('.$status_count[8].')':''),'url'=>'/operator/delivered/');
	 	$treeMap[]=array('name'=>'НЕ доставлен'.(isset($status_count[9])?' ('.$status_count[9].')':''),'url'=>'/operator/notdelivered/');
	 	$treeMap[]=array('name'=>'<br>Перейти на сайт','url'=>'/');
	 	
	 	$st=array();
		$this->treeMap=new TreeMap($treeMap,$st);	
	}
	
	function method(){
		$this->needAuth();
		parent::method();
	}

//	function updateOrderCount(){
//		$rs=$this->getStatement()->execute("SELECT COUNT(*) AS c FROM sc_shop_order WHERE start_time IS NULL");
//		if($rs->next()){
//			$this->new_order_count=$rs->getInt('c');
//		}
//	}
	function needAuth(){
		if(!$this->isAdmin() && $this->getUser('type')!='operator'){
			$args=new ArgumentList($_GET);
			$this->callComponent('/login',new ArgumentList(array('from'=>$this->getURI().$args->getURLString())));
		}
	}
	function refreshContainer(){
		$this->updateTree();
		$this->refreshExplorer();
	}
	
	function refreshExplorer(){
		global $ST;
		$this->explorer[]=array('name'=>$this->mod_name,'url'=>$this->mod_alias);
		$parent=$this->mod_parent_id;
		while ($parent) {
			$rs=$ST->select("SELECT * FROM sc_module WHERE mod_id=".$parent);
			if($rs->next()){
				$parent=$rs->getInt('mod_parent_id');
				$this->explorer[]=array('name'=>$rs->get('mod_name'),'url'=>$rs->get('mod_alias'));
			}else{
				break;
			}
		}
		if($this->mod_alias!='/'){
			$this->explorer[]=array('name'=>'Главная','url'=>'/');
		}
	}

	function expand($args){
		$this->treeMap=new TreeMap(array(),&$this->session_treeState);
		$this->treeMap->expand($args->getArgument("node"));
		$this->callSelfComponent();
	}

	function sort($field_name,$label){
		?><a href="<?=$this->getURI(array('sort'=>$field_name,'ord'=>$this->getURIVal('ord')!='asc'?'asc':'desc'))?>"><?=$label?></a><?
		if($this->getURIVal('sort')==$field_name){
		?><img src="/img/sort_<?=$this->getURIVal('ord')!='asc'?'asc':'desc'?>.gif" /><?
		}
	}
}
?>