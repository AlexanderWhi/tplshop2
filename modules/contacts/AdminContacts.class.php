<?php
include_once("core/component/AdminListComponent.class.php");
class AdminContacts extends AdminListComponent {
	
	
	function getType(){
		$type='contacts';
		if(!$type=$this->getURIVal('type')){
			$type=$this->getURIVal('admin');
		}
		return $type;
	}
	
	function actDefault(){
		global $ST,$get;
		parent::refresh();
		
		$cond="";
		if($type=$this->getType()){
			$cond.=" AND type='$type'";
		}
		if($status=$get->getInt('status')){
			$cond.=" AND status='$status'";
		}
		$queryStr="SELECT count(*) AS c FROM sc_contacts WHERE 1=1 $cond";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY id DESC";

		$queryStr="SELECT *,t.value_desc AS type_desc,s.value_desc AS status_desc,u.mod_name AS url_desc
			FROM (SELECT * FROM sc_contacts WHERE 1=1 $cond  $order LIMIT ".$this->page->getBegin().",".$this->page->per.")  AS c 
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='c_type') AS t ON t.field_value=c.type
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='c_status') AS s ON s.field_value=c.status
			LEFT JOIN (SELECT mod_name,mod_alias FROM sc_module WHERE mod_type<>2) AS u ON u.mod_alias=c.url 
		";
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/contacts_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'time'=>'',
			'name'=>'',
			'mail'=>'',
			'phone'=>'',
			'comment'=>'',
			'type'=>$this->getType(),
			'status'=>'',
			'url'=>'',
		);
		
		$status_list=array();
		$rs=$ST->select("SELECT field_value,value_desc FROM sc_enum WHERE field_name='c_status' ORDER BY position");
		while ($rs->next()) {
			$status_list[$rs->get('field_value')]=$rs->get('value_desc');
		}
		
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_contacts WHERE id =".$field['id'];
			$rs=$ST->execute($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
				
				$rs=$ST->select("SELECT value_desc FROM sc_enum WHERE field_name='c_type' AND field_value='{$field['type']}'");
				if($rs->next()){
					$field['type_desc']=$rs->get('value_desc');
				}
				$rs=$ST->select("SELECT mod_name,mod_alias FROM sc_module WHERE mod_alias='{$field['url']}'");
				if($rs->next()){
					$field['url_desc']=$rs->get('mod_name');
				}
			}
		}
		$field['status_list']=$status_list;
		$this->display($field,dirname(__FILE__).'/contacts_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		if($id){
			$ST->update('sc_contacts',$_POST,"id=".$id);
		}else{
			$id=$ST->insert('sc_contacts',$post->get());
		}		
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}
	
	function actRemove(){		
		global $ST;
		$q="DELETE FROM sc_contacts WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
	//--Адреса
	
	function actAddr(){
		global $ST;
		parent::refresh();
		
		$queryStr="SELECT count(*) AS c FROM sc_contacts_points";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY id DESC";

		$queryStr="SELECT * FROM sc_contacts_points $order LIMIT ".$this->page->getBegin().",".$this->page->per."";
		$this->rs=$ST->select($queryStr);
		$this->setPageTitle('Адреса');
		$this->display(array(),dirname(__FILE__).'/contacts_points_list.tpl.php');
	}
	function actRemoveAddr(){		
		global $ST;
		$q="DELETE FROM sc_contacts_points WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
	
	function actEditAddr(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'name'=>'',
			'point'=>'',
			'description'=>'',
			'addr'=>'',
			'city'=>'',
			'code'=>'',
			'phone'=>'',
			'fax'=>'',
			'mail'=>'',
		);
				
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_contacts_points WHERE id=".$field['id'];
			$rs=$ST->select($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$this->setPageTitle('Адреса');
		$this->display($field,dirname(__FILE__).'/contacts_addr_edit.tpl.php');
	}
	
	function actSaveAddr(){
		global $ST,$post;
		$id=$post->getInt('id');
		if($id){
			$ST->update('sc_contacts_points',$_POST,"id=".$id);
		}else{
			$id=$ST->insert('sc_contacts_points',$post->get());
		}		
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}
	
}
?>