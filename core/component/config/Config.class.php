<?php
include_once 'core/component/AdminComponent.class.php';

class Config extends AdminComponent {	
	protected $mod_name='Конфигурация';
	protected $mod_title='Конфигурация';
	
	function getListData(){
		global $ST,$CONFIG;
		$data=array(
			'rs'=>$ST->select("SELECT * FROM sc_config ORDER BY name")->toArray(),
		);
		$rs=$ST->select("SELECT * FROM sc_enum WHERE LOWER(field_name) LIKE 'cfg_%'");
		while ($rs->next()) {
			$field_name=preg_replace('/^cfg_/i','',$rs->get('field_name'));
			$data['enum'][$field_name][$rs->get('field_value')]=$rs->get('value_desc');
		}
		return $data;
	}
	
	
	function actDefault(){
		global $ST,$CONFIG,$post;
		
		$data=$this->getListData();
		
		if($this->isSu()){
			$this->display($data,dirname(__FILE__).'/config_su.tpl.php');
		}else{
			$this->display($data,dirname(__FILE__).'/config.tpl.php');
		}
	}
	
	function actSave(){
		global $ST,$CONFIG,$post;
		foreach ($CONFIG as $k=>$v){
			if($post->exists(strtolower($k))){
				$ST->update('sc_config',array('value'=>$post->get(strtolower($k))),"LOWER(name)=LOWER('".$k."')");
			}
		}
		echo printJSON(array('msg'=>'Данные обновлены'));exit;
	}
	
	function actAdd(){
		global $ST,$post;
		
		$d=array(
			'name'=>$post->get('name'),
			'value'=>$post->get('value'),
			'description'=>$post->get('description'),
		
		);
		$rs=$ST->select("SELECT * FROM sc_config WHERE name='".SQL::slashes($d['name'])."'");
		if(!$rs->next()){
			$ST->insert('sc_config',$d);
			$out['msg']='добавлено';
			$out['html']=$this->rndList();
		}else{
			$out['err']=$d['name'].' уже существует';
		}
		
		exit(printJSON($out));
	}
	
	function rndList(){
		$d=$this->getListData();
		return $this->render($d,dirname(__FILE__).'/config_list.tpl.php');
	}
	function actRemove(){
		global $ST,$get,$post;
		if($get->get('name')){
			$ST->delete('sc_config',"name='".SQL::slashes($get->get('name'))."'");
		}
		if($item=$post->getArray('item')){
			foreach ($item as $n) {
				$ST->delete('sc_config',"name='".SQL::slashes($n)."'");
			}
		}
		echo $this->rndList();
		
	}
}
?>