<?php
include_once("core/component/AdminListComponent.class.php");
class AdminVote extends AdminListComponent {
//	protected $mod_name='Голосование';
//	protected $mod_title='Голосование';
	
	protected $status=array(1=>'Опубликовано',0=>'На редактировании');
	
	function actDefault(){
		global $ST;
		parent::refresh();

		$queryStr="SELECT count(*) AS c FROM sc_vote";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY start_time DESC";

		$queryStr="SELECT * FROM sc_vote $order LIMIT ".$this->page->getBegin().",".$this->page->per;
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/vote_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'name'=>'',
			'start_time'=>date('d.m.Y'),
			'stop_time'=>date('d.m.Y'),
			'state'=>0,
			'position'=>1,
		);
		
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_vote WHERE id =".$field['id'];
			$rs=$ST->execute($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
				$field['start_time']=preg_replace('/^(\d{4})-(\d{2})-(\d{2}).*/','\3.\2.\1',$field['start_time']);
				$field['stop_time']=preg_replace('/^(\d{4})-(\d{2})-(\d{2}).*/','\3.\2.\1',$field['stop_time']);
			}
			
			$rs=$ST->select("SELECT * FROM sc_vote_item WHERE voteid=".$field['id']." ORDER BY sort");
			$field['items']=array();
			while ($rs->next()) {
				$field['items'][]=$rs->getRow();
			}
		}else{
			$field['items']=array();
		}
		$field['status']=$this->status;
		
		$this->explorer[]=array('name'=>'Редактировать');
		
		$this->display($field,dirname(__FILE__).'/vote_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		
		$data=array(
			'name'=>$post->get('name'),
			'start_time'=>preg_replace('/^(\d{2})\.(\d{2})\.(\d{4}).*/','\3-\2-\1',$post->get('start_time')),
			'stop_time'=>preg_replace('/^(\d{2})\.(\d{2})\.(\d{4}).*/','\3-\2-\1',$post->get('stop_time')),
			'state'=>$post->getInt('state'),
			'position'=>$post->getInt('position'),
		);
		
		if($id){
			$ST->update('sc_vote',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_vote',$data);	
		}
		$ST->delete('sc_vote_item','voteid='.$id);
		$result=$post->get('item_res');
		$sort=$post->get('item_sort');
		foreach ($post->get('item') as $key=>$val){
			if(!trim($val))continue;
			$date=array(
				'name'=>$val,
				'voteid'=>$id,
				'result'=>$result[$key],
				'sort'=>$sort[$key],
			);
			if(!is_int($key)){
				$date['id']=$key;
			}
			$ST->insert('sc_vote_item',$date);
		}
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}
		
	function actRemove(){		
		global $ST;
		$ST->delete('sc_vote',"id=".intval($_POST['id']));
		$ST->delete('sc_vote_item',"voteid=".intval($_POST['id']));
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
	
	function actReset(){		
		global $ST;
		$ST->update('sc_vote_item',array('result'=>0),"voteid=".intval($_POST['id']));
		$ST->delete('sc_vote_det',"itemid IN(SELECT id FROM sc_vote_item WHERE voteid=".intval($_POST['id']).")");
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
}
?>