<?php
include_once('core/component/Component.class.php');
class Vote extends Component {
		
	function actDefault(){
	
	}
	function actSend(){
		global $ST,$post;
		$id=$post->getInt('vote');
		if($id){
			$rs=$ST->select("SELECT * FROM sc_vote_det WHERE itemid=".$id." AND DATE(time)=DATE(NOW()) AND ip='".$_SERVER['REMOTE_ADDR']."'");
			if(!$rs->next()){
				$data=array(
					'itemid'=>$id,
					'ip'=>$_SERVER['REMOTE_ADDR']
				);
				$ST->insert('sc_vote_det',$data);
				$ST->executeUpdate("UPDATE sc_vote_item SET result=result+1 WHERE id=".$id);
			}
			
			$rs=$ST->select("SELECT v.* FROM sc_vote v,sc_vote_item i WHERE i.id=".$id." AND i.voteid=v.id AND NOW() BETWEEN start_time and stop_time LIMIT 1");
						
			if($rs->next()){
				$data=array(
					'name'=>$rs->get('name')
				);
				$rs=$ST->select("SELECT * FROM sc_vote_item WHERE voteid=".$rs->getInt('id')." ORDER BY sort");
				$data['items']=array();
				$data['all']=0;
				while ($rs->next()) {
					$data['items'][]=$rs->getRow();
					$data['all']+=$rs->getInt('result');
				}
				echo $this->render($data,dirname(__FILE__).'/vote_result.tpl.php');
			}
		}
		exit;
	}
}
?>