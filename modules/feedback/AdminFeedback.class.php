<?php
include_once("core/component/AdminListComponent.class.php");
class AdminFeedback extends AdminListComponent {
	
	
	function getType(){
		$type='feedback';
		if(!$type=$this->getURIVal('type')){
			$type=$this->getURIVal('admin');
		}
		return $type;
	}
	
	function actDefault(){
		
		if($this->getType()=='fix'){
			return $this->actFix();
		}
		
		global $ST,$get;
		parent::refresh();
		
		$cond="";
		if($type=$this->getType()){
			$cond.=" AND type='$type'";
		}
		if($status=$get->getInt('status')){
			$cond.=" AND status='$status'";
		}
		$queryStr="SELECT count(*) AS c FROM sc_feedback WHERE 1=1 $cond";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY id DESC";

		$queryStr="SELECT *,t.value_desc AS type_desc,s.value_desc AS status_desc
			FROM (SELECT * FROM sc_feedback WHERE 1=1 $cond  $order LIMIT ".$this->page->getBegin().",".$this->page->per.")  AS c 
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='c_type') AS t ON t.field_value=c.type
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='c_status') AS s ON s.field_value=c.status
		";
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/feedback_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'time'=>'',
			'name'=>'',
			'mail'=>'',
			'file'=>'',
			'phone'=>'',
			'comment'=>'',
			'answer'=>'',
			'type'=>$this->getType(),
			'status'=>'',
			
		);
		
		$status_list=array();
		$rs=$ST->select("SELECT field_value,value_desc FROM sc_enum WHERE field_name='c_status' ORDER BY position");
		while ($rs->next()) {
			$status_list[$rs->get('field_value')]=$rs->get('value_desc');
		}
		
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_feedback WHERE id =".$field['id'];
			$rs=$ST->execute($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
				
				$rs=$ST->select("SELECT value_desc FROM sc_enum WHERE field_name='c_type' AND field_value='{$field['type']}'");
				if($rs->next()){
					$field['type_desc']=$rs->get('value_desc');
				}
				
			}
		}
		$field['status_list']=$status_list;
		$this->display($field,dirname(__FILE__).'/feedback_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		if($id){
			$ST->update('sc_feedback',$_POST,"id=".$id);
		}else{
			$id=$ST->insert('sc_feedback',$post->get());
		}		
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}
	
	function actRemove(){		
		global $ST;
		$q="DELETE FROM sc_feedback WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
	function actRemoveFix(){		
		global $ST;
		$q="DELETE FROM sc_feedback WHERE id=".intval($_GET['id']);
		$ST->executeDelete($q);
		header("Location: {$_SERVER['HTTP_REFERER']}");exit;
	}
	
	
	
	function actFix(){
		global $ST,$get;
		parent::refresh();
		
		$cond="AND type='fix'";
				
		$queryStr="SELECT count(*) AS c FROM sc_feedback WHERE 1=1 $cond";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY id DESC";

		$queryStr="SELECT * FROM sc_feedback WHERE 1=1 $cond  $order LIMIT ".$this->page->getBegin().",".$this->page->per."";
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/feedback_fix_list.tpl.php');
	}
	
	
}
?>