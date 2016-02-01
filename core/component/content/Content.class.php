<?php
include_once("core/component/AdminListComponent.class.php");
/**
 * 07.09.2011 Возможность создавать содержимое по названию
 *
 */
class Content extends AdminListComponent {
	protected $mod_name='Текстовое содержимое';
	protected $mod_title='Текстовое содержимое';
	function actDefault(){
		$this->refresh();
		$this->display(array(),dirname(__FILE__).'/content.tpl.php');
	}
	
	function refresh(){	
		parent::refresh();
		$query="SELECT count(*) AS c FROM sc_content" ;
		$rs=$this->getStatement()->execute($query);
		if($rs->next()){
			$this->page->all=$rs->getInt('c');
		}
		$queryStr="SELECT * FROM sc_content ORDER BY c_name LIMIT ".$this->page->getBegin().",".$this->page->per ." ";
		$this->rs=$this->getStatement()->execute($queryStr);	
	}
	
	function actRemove(){
		global $ST,$get,$post;
		if($get->getInt("id")){
			$ST->delete('sc_content',"c_id ={$get->getInt("id")}");
			header('Location: .');exit;
		}elseif($itm=$post->getArray('item')){
			foreach ($itm as $i) {
				$ST->delete('sc_content',"c_id ={$i}");
			}
			echo printJSON(array('msg'=>'Удалено'));exit;
		}
		
	}
	
	function actEdit(){
		global $ST,$get;
		$field=array(
			'c_id'=>$get->getInt('id'),
			'c_text'=>'',
			'c_name'=>$get->get('name')
		);
		
		if($field['c_id'] || $field['c_name']){
			$rs=$ST->select("SELECT ".join(',',array_keys($field))." FROM sc_content WHERE c_id=".$field['c_id']." OR c_name='".$field['c_name']."'");
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$this->display($field,dirname(__FILE__).'/content_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('c_id');
		if(!trim($post->get('c_name'))){
			echo printJSON(array('err'=>'Введите название'));exit;
		}
		$rs=$ST->select("SELECT * FROM sc_content WHERE c_name='".SQL::slashes($post->get('c_name'))."' AND c_id<>$id");
			if($rs->next()){
				echo printJSON(array('err'=>'Содержимое с таким названием СУЩЕСТВУЕТ'));exit;
			}
		$data=array(
			'c_name'=>$post->get('c_name'),
			'c_text'=>$post->get('c_text'));
		if($id){
			$ST->update('sc_content',$data,'c_id='.$id);
		}else{
			
			$id=$ST->insert('sc_content',$data,'c_id');
		}
		echo printJSON(array('msg'=>'Сохранено','c_id'=>$id));exit;
	}
}
?>