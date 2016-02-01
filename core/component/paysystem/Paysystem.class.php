<?php
include_once 'core/component/AdminComponent.class.php';

class Paysystem extends AdminComponent {	
	
	protected $mod_name='Настройка платёжных систем';
	protected $mod_title='Настройка платёжных систем';
	
	function actDefault(){
		global $ST;
		$data=array(
			'rs'=>array(),
		);
		
		
		$rs=$ST->select("SELECT * FROM sc_pay_system");
		$ps=array();
		while ($rs->next()) {
			$ps[$rs->get('name')]=$rs->getRow();
		}
		
		
		$rs=array();
		$d=opendir('ps');
		while ($file=readdir($d)) {
			if(preg_match('/PS(\w+)\.class\.php/',$file,$res)){
				$name=strtolower($res[1]);
				$class_name="PS".$res[1];
				include_once("ps/$class_name.class.php");
//				$data['rs'][$rs->get('name')]=$rs->getRow();
				
				$cfg='';
				if(isset($ps[$name])){
					$cfg=$ps[$name]['config'];
					$data['rs'][$name]=$ps[$name];
				}
				
				$data['rs'][$name]['ps']=new $class_name($cfg);
			}
		}
		closedir($d);
		
		
//		$rs=$ST->select("SELECT * FROM sc_pay_system");
//		$ps=array();
//		while ($rs->next()) {
//			
//			$class="PS".ucfirst($rs->get('name'));
//			include_once("core/lib/$class.class.php");
//			$data['rs'][$rs->get('name')]=$rs->getRow();
//			$data['rs'][$rs->get('name')]['ps']=new $class(unserialize($rs->get('config')));
//		}
		$this->display($data,dirname(__FILE__).'/paysystem.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		
		
		$config_arr=$post->getArray('config');
		$text_arr=$post->getArray('text');
		$description_arr=$post->getArray('description');
		
		foreach ($description_arr as $name=>$d) {
			$data=array(
				'description'=>$d,
				'text'=>$text_arr[$name],
			);
			
			$class="PS".ucfirst($name);
			include_once("ps/$class.class.php");
			$ps=new $class();
			$config=array();
			foreach ($ps->config as $c) {
				$config[$c]=@$config_arr[$name][$c];
			}
			$data['config']=serialize($config);
			$rs=$ST->select("SELECT * FROM sc_pay_system WHERE name='{$name}'");
			if($rs->next()){
				$ST->update('sc_pay_system',$data,"name='{$name}'");
			}else{
				$data['name']=$name;
				$ST->insert('sc_pay_system',$data);
			}
		}
		if($paysystem=$post->get('default')){
			$rs=$ST->select("SELECT * FROM sc_config WHERE name='PAYSYSTEM'");
			if($rs->next()){
				$ST->update('sc_config',array('value'=>$paysystem),"name='PAYSYSTEM'");
			}else{
				$ST->insert('sc_config',array('value'=>$paysystem,'name'=>'PAYSYSTEM','description'=>'Платёжная система'));
			}
		}
		
		echo printJSON(array('msg'=>'Сохранено!'));exit;
	}
}
?>