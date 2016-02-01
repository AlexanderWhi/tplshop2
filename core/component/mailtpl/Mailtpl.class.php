<?php
include_once("core/component/AdminListComponent.class.php");
class Mailtpl extends AdminListComponent {
	
	protected $mod_name='Шаблоны писем';
	protected $mod_title='Шаблоны писем';
	
	function actDefault(){
		$this->refresh();
		$this->display(array(),dirname(__FILE__).'/mailtpl.tpl.php');
	}	
	function refresh(){
		parent::refresh();
		
		$query="SELECT count(*) AS c FROM sc_letter_template" ;
		$rs=$this->getStatement()->execute($query);
		if($rs->next()){
			$this->page->all=$rs->getInt('c');
		}
		$queryStr="select * from sc_letter_template LIMIT ".$this->page->getBegin().",".$this->page->per ;
		$this->rs=$this->getStatement()->execute($queryStr);	
	}

	function actRemove(){	
		global $get,$ST;	
		$queryStr="delete from sc_letter_template where id =".$get->getInt("id");
		$ST->executeDelete($queryStr);
		header('Location: .');exit;
	}
	
	function actEdit(){
		global $ST,$get;
		$field=array(
			'id'=>$get->getInt('id'),
			'name'=>'',
			'theme'=>'',
			'description'=>'',
			'body'=>''
		);
		
		if($field['id']){
			$rs=$this->getStatement()->execute("SELECT ".join(',',array_keys($field))." FROM sc_letter_template WHERE id=".$field['id']);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$this->explorer[]=array('name'=>'Редактировать');
		$this->display($field,dirname(__FILE__).'/mailtpl_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		$data=array('name'=>$post->get('name'),
				'theme'=>$post->get('theme'),
				'body'=>$post->get('body'),
				'description'=>$post->get('description')
		);
		if($id){
			$ST->update('sc_letter_template',$data,'id='.$id);
		}else{
			$id=$ST->insert('sc_letter_template',$data);
		}
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit;
	}
}
?>