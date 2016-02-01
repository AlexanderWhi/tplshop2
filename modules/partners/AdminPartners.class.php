<?php
include_once("core/component/AdminListComponent.class.php");
class AdminPartners extends AdminListComponent {
	
	protected $status=array(1=>'Опубликовано',0=>'На редактировании');
	
	function actDefault(){
		global $ST;
		parent::refresh();

		$queryStr="SELECT count(*) AS c FROM sc_partner";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY sort";

		$queryStr="SELECT * FROM sc_partner $order LIMIT ".$this->page->getBegin().",".$this->page->per;
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/partners_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'name'=>'',
			'description'=>'',
			'phone'=>'',
			'mail'=>'',
			'icq'=>'',
			
			'delivery'=>0,'delivery_cond'=>0,'delivery_order_cond'=>0,
			
			'img'=>'',
			'img1'=>'',
			'state'=>0,
			'sort'=>0,
			'url'=>'',
		);
		
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_partner WHERE id =".$field['id'];
			$rs=$ST->execute($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$field['status']=$this->status;
		
		$this->explorer[]=array('name'=>'Редактировать');
		
		$this->display($field,dirname(__FILE__).'/partners_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		
		$data=array(
			'name'=>$post->get('name'),
			'description'=>$post->get('description'),
			
			'phone'=>$post->get('phone'),
			'mail'=>$post->get('mail'),
			'icq'=>$post->get('icq'),
			
			'delivery'=>$post->getInt('delivery'),
			'delivery_cond'=>$post->getInt('delivery_cond'),
			'delivery_order_cond'=>$post->getInt('delivery_order_cond'),
			
			'img'=>$post->get('img'),
			'img1'=>$post->get('img1'),
			'state'=>$post->getInt('state'),
			'sort'=>$post->getInt('sort'),
			'url'=>$post->get('url'),
		);
		
		if($data['img'] && file_exists(ROOT.$data['img'])){
			$path=$this->cfg('PARTNERS_PATH').'/'.md5_file(ROOT.$data['img']).'.'.file_ext($data['img']);
			if(!file_exists(ROOT.$path)){
				rename(ROOT.$data['img'], ROOT.$path);
			}
			$data['img']=$path;     
		}elseif($data['img']=='clear'){
			$data['img']='';
		}
		if($data['img1'] && file_exists(ROOT.$data['img1'])){
			$path=$this->cfg('PARTNERS_PATH').'/'.md5_file(ROOT.$data['img1']).'.'.file_ext($data['img1']);
			if(!file_exists(ROOT.$path)){
				rename(ROOT.$data['img1'], ROOT.$path);
			}   
			$data['img1']=$path;  
		}elseif($data['img1']=='clear'){
			$data['img1']='';
		}
		
		if($id){
			$ST->update('sc_partner',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_partner',$data);	
		}
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}
	
	function actUpload(){
		global $get;
		$num='';
		if(isset($_FILES['upload']) && $_FILES['upload']['name']){
			$name=md5_file($_FILES['upload']['tmp_name']).'.'.file_ext($_FILES['upload']['name']);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$path);
		}
		if(isset($_FILES['upload1']) && $_FILES['upload1']['name']){
			$num='1';
			$name=md5_file($_FILES['upload1']['tmp_name']).'.'.file_ext($_FILES['upload1']['name']);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload1']['tmp_name'],ROOT.$path);
		}
		echo printJSONP(array('msg'=>'Сохранено','path'=>$path,'num'=>$num),$get->get('cb'));exit;
	}
		
	function actRemove(){		
		global $ST;
		$ST->delete('sc_partner',"id=".intval($_POST['id']));
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
}
?>