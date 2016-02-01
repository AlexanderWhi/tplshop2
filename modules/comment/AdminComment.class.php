<?php
include_once 'core/component/AdminComponent.class.php';
class AdminComment extends AdminComponent {
	protected $mod_name='Коментарии';
	protected $mod_title='Коментарии';
	
	function actRaitingRef(){		
		global $ST;	
		$rs=$ST->select("SELECT * FROM sc_comment_rait_ref ORDER BY catid,pos,name");
		$data=array('rs'=>$rs);
	
		$this->display($data,dirname(__FILE__).'/raiting_ref.tpl.php');
	}

	function actSaveRaitingRef(){
		global $ST,$post;
		
		$id=$post->get('id');
		$name=$post->get('name');
		$rfrom=$post->getArray('rfrom');
		$rto=$post->getArray('rto');
		$pos=$post->getArray('pos');
		$catid=$post->getArray('catid');
//		$ST->delete('sc_comment_rait_ref');
		$i=0;
		
		if($id){
			$ST->delete('sc_comment_rait_ref',"id NOT IN(".implode(',',$id).")");
		}
		
		foreach ($name as $k=>$v){
			if(trim($name[$k])!=''){
				$d=array(
					'name'=>$name[$k],
					'rfrom'=>(int)$rfrom[$k],
					'rto'=>(int)$rto[$k],
					'catid'=>(int)$catid[$k],
					'pos'=>(int)$pos[$k],
				);
				if($id[$k]>0 ){
					$ST->update('sc_comment_rait_ref',$d,"id=".$id[$k]);
				}else{
					$ST->insert('sc_comment_rait_ref',$d,null);
				}
			}
			
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	function actDefault(){
		return $this->actRaitingRef();
	}
	
	function actEdit(){
		global $ST,$get;
		$data=array();
		$q="SELECT * FROM sc_comment WHERE id={$get->getInt('id')}";
		$rs=$ST->select($q);
		if($rs->next()){
			$data=$rs->getRow();
		}
		$data['status_list']=array(0=>'Скрыто',1=>'Показано');
		
		$this->display($data,dirname(__FILE__).'/comment_edit.tpl.php');
	}
	function actSave(){
		global $ST,$post;
		$data=array(
			'comment'=>$post->get('comment'),
			'answer'=>$post->get('answer'),
//			'time'=>dte($post->get('time'),'Y-m-d'),
//			'time_answer'=>dte($post->get('time_answer'),'Y-m-d'),
			'time_answer'=>date('Y-m-d H:i:s'),
			'status'=>$post->getInt('status'),
		);
		if($id=$post->getInt('id')){
			$ST->update('sc_comment',$data,"id=$id");
		}else{
			$id=$ST->insert("sc_comment",$data);
		}
		
		echo printJSON(array('id'=>$id,'msg'=>'Сохранено!'));exit;
	}
}
?>