<?php
include_once 'core/component/AdminComponent.class.php';
class Enum extends AdminComponent {
	protected $mod_name='Списки';
	protected $mod_title='Списки';
	
	function actDefault(){		
		global $ST,$get;
		$field_name=$this->getURIVal('enum');
		
		if(($field_value=$this->getURIVal($field_name)) && $field_value!='mode'){
			
			$data=array(
				'field_name'=>$field_name,
				'field_value'=>$field_value,
				'position'=>0,
				'value_desc'=>'',
			);
			
			$rs=$ST->select("SELECT * FROM sc_enum WHERE field_name='".SQL::slashes($field_name)."' AND  field_value='".SQL::slashes($field_value)."'");
			if($rs->next()){
				$data=$rs->getRow();
			}
			$this->display($data,dirname(__FILE__).'/enum_item.tpl.php');
		}elseif($field_name){ 
			$rs=$ST->select("SELECT * FROM sc_enum WHERE field_name='$field_name' ORDER BY position");
			$data=array('rs'=>$rs,'field_name'=>$field_name);
			
			
			$data['mode']=array('add','pos','value','desc','name');
			if($mode=$this->getURIVal('mode')){
				$data['mode']=explode(',',$mode);
			}
			if($get->get('title')){
				$this->setPageTitle($get->get('title'));
				$data['hidename']=true;
			}
			if($get->exists('autoval')){
				$data['autoval']=true;
			}
			
			
			$this->display($data,dirname(__FILE__).'/enum.tpl.php');
		}else{
			$rs=$ST->select("SELECT DISTINCT field_name FROM sc_enum ORDER BY field_name")->toArray();
			$data=array('rs'=>$rs);
			
	
			$this->display($data,dirname(__FILE__).'/enum_list.tpl.php');
		}
		
		
	}

	function actSave(){
		global $ST,$post;
		
		$field_name=$post->get('field_name');
		$field_value=$post->getArray('field_value');
		$value_desc=$post->getArray('value_desc');
		$position=$post->getArray('position');
		$ST->delete('sc_enum',"field_name='$field_name'");
		$i=0;
		foreach ($field_value as $k=>$v){
			if($post->get('autoval')=='true'){
				$field_value[$k]=++$i;
			}
			if(trim($field_value[$k])!=''){
				$d=array(
					'field_value'=>$field_value[$k],
					'value_desc'=>$value_desc[$k],
					'position'=>(int)$position[$k],
					'field_name'=>$field_name,
				);
				
				$ST->insert('sc_enum',$d,null);
			}
			
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	function actSaveItem(){
		global $ST,$post;
		
		$field_name=$post->get('field_name');
		$field_value=$post->get('field_value');
		$value_desc=$post->get('value_desc');
		$position=$post->get('position');
		$ST->delete('sc_enum',"field_name='$field_name' AND field_value='$field_value' ");
		
		if(trim($field_value)!=''){
				$d=array(
					'field_value'=>$field_value,
					'value_desc'=>$value_desc,
					'position'=>(int)$position,
					'field_name'=>$field_name,
				);
				$ST->insert('sc_enum',$d);
		}
		
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	function actRemove(){
		global $ST,$post;
		$field_name=$post->get('field_name');
		$ST->delete('sc_enum',"field_name='{$field_name}'");
		exit( printJSON(array('msg'=>'Удалено')));
	}
}
?>