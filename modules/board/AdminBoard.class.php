<?php
include_once("core/component/AdminListComponent.class.php");

class AdminBoard extends AdminListComponent {			
	function actDefault(){
		global $ST;
		parent::refresh();

		$cnd="1=1";
		
		$queryStr="SELECT count(*) AS c FROM sc_shop_board b WHERE $cnd";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY time ASC";
				
		$queryStr="SELECT * FROM sc_shop_board b
			LEFT JOIN sc_users as u ON u.u_id=b.userid
		WHERE $cnd $order limit ".$this->page->getBegin().",".$this->page->per;
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/board_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'description'=>'',
			'text'=>'',
			'date_to'=>date('Y-m-d'),
			'status'=>1
		);
		
		if($id=$get->getInt('id')){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_shop_board WHERE id =$id";
			$rs=$ST->select($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$field['status_list']=array(1=>'Опубликовано',0=>'На редактировании');
		
		$this->explorer[]=array('name'=>'Редактировать');
		
		$this->display($field,dirname(__FILE__).'/board_edit.tpl.php');
	}
	
	
	
	function actSave(){
		global $ST,$get,$post;
		$id=$post->getInt('id');
		
		$data=array(
			'description'=>$post->get('description'),
			'text'=>$post->get('text'),
			'status'=>$post->getInt('status'),
			'date_to'=>dte($post->get('date_to'),DTE_FORMAT_SQL),
		);
	
		if($id){
			$ST->update('sc_shop_board',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_shop_board',$data);	
		}		
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}

	
	function actRemove(){		
		global $ST;
		$q="DELETE FROM sc_shop_board WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
}
?>