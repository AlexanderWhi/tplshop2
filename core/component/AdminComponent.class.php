<?php
include_once 'BaseComponent.class.php';
class AdminComponent extends BaseComponent{
	/**
	 * Основные настройки компоненты
	 */
	protected  $tplContainer="template/admin/pages/admin_container.tpl.php";
	protected  $tplComponent="";
	
	
	
	protected $adminMenu=array(
		array("label"=>'Сайт','url'=>"/",'access'=>'manager,operator,courier'),
		array("label"=>'Модули','url'=>"/admin/modules/"),//,'accessgroup'=>'supervisor'
		array("label"=>'Статическое содержимое','url'=>"/admin/content/"),
		array("label"=>'Шаблоны писем','url'=>"/admin/mailtpl/"),
		array("label"=>'Проводник','url'=>"/admin/files/",'accessgroup'=>'supervisor'),	
		array("label"=>'Пользователи','url'=>"/admin/users/"),	
		array("label"=>'Настройки','url'=>"/admin/config/"),	
		array("label"=>'Настройки сайта','url'=>"/admin/config/front"),	
		array("label"=>'Настройки ПС','url'=>"/admin/paysystem/",'accessgroup'=>'supervisor'),	
	);
	
	
	function getNewOrdersCnt(){
		global $ST;
		$new_order_count=0;
		$rs=$ST->select("SELECT COUNT(id) AS c FROM sc_shop_order WHERE order_status=0");
		if($rs->next()){
			$new_order_count=$rs->getInt('c');
		}
		return $new_order_count;
	}
	function getNewContactsCnt(){
		global $ST;
		$count=0;
		$rs=$ST->select("SELECT COUNT(id) AS c FROM sc_contacts WHERE status=2 AND type='feedback'");
		if($rs->next()){
			$count=$rs->getInt('c');
		}
		return $count;
	}
	
	function getTopMenu(){
		$menu=array();
		foreach ($this->adminMenu as $v){
			if(isset($v['accessgroup'])){
				if(in_array($this->getUser('type'),explode(',',$v['accessgroup']))){
					$menu[]=$v;	
				}
				
			}elseif($this->isAdmin() || (isset($v['access']) && in_array($this->getUser('type'),explode(',',$v['access'])))){
				$menu[]=$v;	
			}
		}
		return $menu;
	}
		
	protected $treeMap=null;
	
	protected $adminGrp=array('admin','supervisor');
	protected $accessGrp=array();
	
	function getModAccess(){
		return explode(',',$this->mod_access);
	}
	
	function getMenu(){
		global $ST;
		$menu=array();
		$q="SELECT * FROM sc_module WHERE mod_state=1 AND mod_location like '%admin%' ORDER BY mod_position";
	 	$rs=$ST->select($q);
	 	$item['text_']=array('children'=>array(),'mod_name'=>'<strong>Текстовые странички</strong>');
	 	while($rs->next()){
	 		if(!$this->isAdmin() && !in_array($this->getUser('type'),explode(',',$rs->get('mod_access')))){
	 			continue;
	 		}
	 		
			foreach ($rs->getRow() as $k=>$v) {
				$item[$rs->get('mod_id').'_'][$k]=$v;
			}
			$item[$rs->get('mod_id').'_']['url']=ADMIN.$rs->get('mod_alias');
			if($rs->getInt('mod_parent_id')){
				$item[$rs->get('mod_parent_id').'_']['children'][$rs->getInt('mod_id').'_']=&$item[$rs->get('mod_id').'_'];
			}else{
				if($rs->get('mod_type')==1){//Сгруппировать текстовки
					$item['text_']['children'][$rs->getInt('mod_id').'_']=&$item[$rs->get('mod_id').'_'];
				}else{
					$menu[$rs->get('mod_id').'_']=&$item[$rs->get('mod_id').'_'];
				}
				
			}
		}
		$menu['text_']=&$item['text_'];
		return $menu;
	}
		
	function method(){
		$this->needAuth();
		parent::method();
	}

	function needAuth(){
		if(!in_array($this->getUser('type'),array_merge($this->adminGrp,$this->getModAccess())) || !$this->getUserId()){
			header("Location: /join/?ref=".urlencode($_SERVER['REQUEST_URI']));exit;
		}
		
	}
	function refreshContainer(){
		$this->refreshExplorer();
	}
	
	function refreshExplorer(){
		global $ST;
		$this->explorer[]=array('name'=>$this->mod_name,'url'=>ADMIN.$this->mod_alias);
		$parent=$this->mod_parent_id;
		while ($parent) {
			$rs=$ST->select("SELECT * FROM sc_module WHERE mod_id=".$parent);
			if($rs->next()){
				$parent=$rs->getInt('mod_parent_id');
				$this->explorer[]=array('name'=>$rs->get('mod_name'),'url'=>ADMIN.$rs->get('mod_alias'));
			}else{
				break;
			}
		}
		if($this->mod_alias!='/'){
			$this->explorer[]=array('name'=>'Главная','url'=>ADMIN.'/');
		}
	}
		
	function actDefault(){
		$this->display(array(),'show');
	}
	
	function show(){
		?>
		<div>
		<a href="<?=ADMIN?>/modules/edit/?id=<?=$this->mod_id?>">Редактировать</a>
		</div>
		<?= $this->getContent();?>
		<div>
		<a href="<?=ADMIN?>/modules/edit/?id=<?=$this->mod_id?>">Редактировать</a>
		</div>
		<?
	}
	function getContent(){
		global $ST;
		$rs=$ST->select('SELECT c_text FROM sc_content WHERE c_id='.$this->mod_content_id);
		if($rs->next()){
			return $rs->get('c_text');
		}
		return '';
	}
	
	function enum($field_name='',$field_value=null){
		global $ST;
		$res=array();
		$rs=$ST->select("SELECT * FROM sc_enum WHERE field_name='$field_name' ORDER BY position");
		while ($rs->next()) {
			$res[$rs->get('field_value')]=$rs->get('value_desc');
		}
		if($field_value && isset($res[$field_value])){
			return $res[$field_value];
		}
		return $res;
	}
	function actSwitchUser(){
		if(isset($_SESSION['_USER']['suser'])){
			$_SESSION['_USER']['u_id']=$_SESSION['_USER']['suser'];
			unset($_SESSION['_USER']['suser']);
		}
		header('Location: '.$_SERVER['HTTP_REFERER']);exit;
	}
	
	function logData($type,$data,$userid=0){
		global $ST;
		if(!$userid){
			$userid=$this->getUserId();
		}
		$ST->insert('sc_shop_log',array('data'=>serialize($data),'type'=>$type,'userid'=>$userid));
	}
	function actAsUser(){
		global $get;
		$_SESSION['_USER']['suser']=$this->getUserId();
		$_SESSION['_USER']['u_id']=$get->getInt('id');
		
		if($get->get('type')=='user'){
			header('Location: /cabinet/');exit;
		}elseif(in_array($get->get('type'),array('courier','operator','manager'))){
			header('Location: /admin/shop/');exit;
		}
		header('Location: .');exit;
	}
	
}
?>