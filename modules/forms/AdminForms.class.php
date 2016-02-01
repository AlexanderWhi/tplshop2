<?php
include_once("core/component/AdminListComponent.class.php");
class AdminForms extends AdminListComponent {
	
	function actDefault(){
		global $ST;
		$data=array('pg'=>&$pg,
			'form'=>trim($this->mod_alias,'/'),
		);
		
		$pg=new Page($this->getPages());
		
		$queryStr="SELECT count(*) AS c FROM sc_forms";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$pg->all=$rs->getInt("c");
		}

		$order="ORDER BY id DESC";

		$queryStr="SELECT *,t.value_desc AS type_desc,s.value_desc AS status_desc
			FROM (SELECT * FROM sc_forms $order LIMIT ".$pg->getBegin().",".$pg->per.")  AS c 
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='f_type') AS t ON t.field_value=c.form::varchar
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='f_status') AS s ON s.field_value=c.status::varchar 
		";
		$this->rs=$ST->select($queryStr);
		
		
		
		$this->display($data,dirname(__FILE__).'/admin_forms.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'time'=>'',
		);
		
		$status_list=array();
		$rs=$ST->select("SELECT field_value,value_desc FROM sc_enum WHERE field_name='f_status' ORDER BY position");
		while ($rs->next()) {
			$status_list[$rs->get('field_value')]=$rs->get('value_desc');
		}
		
		if($field['id']){
			$queryStr="SELECT * FROM sc_forms WHERE id =".$field['id'];
			$rs=$ST->select($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
				$field['html']=$field['data'];
				unset($field['data']);
				$field['html']=$this->render(unserialize($field['html']),dirname(__FILE__).'/'.$field['form'].'_view.tpl.php');
			}
		}
		$field['status_list']=$status_list;
		$this->display($field,dirname(__FILE__).'/admin_form_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		if($id){
			$ST->update('sc_forms',$_POST,"id=".$id);
		}		
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}
	
	function actRemove(){		
		global $ST;
		$q="DELETE FROM sc_forms WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
}
?>