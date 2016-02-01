<?php
include_once("core/component/AdminListComponent.class.php");

class AdminFaq extends AdminListComponent {			
	function actDefault(){
		global $ST;
		parent::refresh();
		$this->updateParams();

		$queryStr="SELECT count(*) AS c FROM sc_faq WHERE type='".$this->type."'";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY time ASC";
				
		$queryStr="SELECT * FROM sc_faq WHERE type='".$this->type."' $order limit ".$this->page->getBegin().",".$this->page->per;
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/faq_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'name'=>'',
			'state'=>'public',
			'question'=>'',
			'answer'=>'',
			'type'=>'faq',
			'pos'=>0
		);
		
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_faq WHERE id =".$field['id'];
			$rs=$ST->select($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$field['status']=array('public'=>'Опубликовано','edit'=>'На редактировании');
		
		$this->explorer[]=array('name'=>'Редактировать');
		
		$this->display($field,dirname(__FILE__).'/faq_edit.tpl.php');
	}
	
	
	
	function actSave(){
		global $ST,$get,$post;
		$id=$post->getInt('id');
		
		$data=array(
			'question'=>$post->get('question'),
			'answer'=>$post->get('answer'),
			'pos'=>$post->getInt('pos'),
			'name'=>$post->get('name'),
			'state'=>$post->get('state'),
			'type'=>$this->getURIVal('admin')
		);
	
		if($id){
			$ST->update('sc_faq',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_faq',$data);	
		}		
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}

	
	function updateParams(){
		preg_match('|^/admin/([^/]+)|',$this->getURI(),$arr);
		$this->type=isset($arr[1])?$arr[1]:'news';
	}
		
	function actRemove(){		
		global $ST;
		$q="DELETE FROM sc_faq WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
	
	function actReset(){		
		global $ST;
		$ST->update('sc_news',array('view'=>0),"id=".intval($_POST['id']));
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
		
	function setPosition(ArgumentList $args){
//     	$this->updateParams();
     	
     	$id=$args->getInt("id");
     	$move=$args->getArgument("move");
     	
		if($move=="up"){
	     	$this->getStatement()->update('sc_news',array("position=position+1"),"id=".$id);
		}
		if($move=="down"){
			$this->getStatement()->update('sc_news',array("position=position-1"),"id=".$id);
		}
    	$this->callSelfComponent();    	
	}
	
	function actUpload(){
		global $get;
		if(isset($_FILES['upload'])){
			$name=md5_file($_FILES['upload']['tmp_name']).'.'.file_ext($_FILES['upload']['name']);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$path);
			$img=scaleImg($path,$get->get('size'));
			if($get->get('resize')=='true'){
				$path=scaleImg($path,$get->get('size'));
			}
		}
		echo printJSONP(array('msg'=>'Сохранено','path'=>$path,'img'=>$img),$get->get('cb'));exit;
	}
}
?>