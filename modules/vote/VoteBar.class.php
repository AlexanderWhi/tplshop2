<?php
class VoteBar {
	
	protected $component;

	protected $data;
	
	function __construct($c,$d){
		$this->component=$c;
		$this->data=$d;
		$this->actDefault();
	}
	
	function actDefault(){
		global $ST;
		$rs=$ST->select("SELECT name,id FROM sc_vote WHERE position=".$this->data." AND NOW() BETWEEN start_time AND stop_time AND state=1 ORDER BY RAND() LIMIT 1");
		if($rs->next()){
			$data=$rs->getRow();
			$rs=$ST->select("SELECT * FROM sc_vote_item WHERE voteid=".$rs->getInt('id')." ORDER BY sort");
			$data['items']=array();
			$data['all']=0;
			while ($rs->next()) {
				$data['items'][]=$rs->getRow();
				$data['all']+=$rs->getInt('result');
			}
			$rs=$ST->select("SELECT v.* FROM sc_vote v,sc_vote_item i,sc_vote_det d 
				WHERE 
					DATE(d.time)=DATE(NOW()) 
					AND d.ip='".$_SERVER['REMOTE_ADDR']."' 
					AND d.itemid=i.id AND v.id='".$data['id']."' 
					AND i.voteid=v.id AND NOW() BETWEEN v.start_time AND v.stop_time 
				LIMIT 1");
			if($rs->next()){
				$data['voted']=true;
			}else{
				$data['voted']=false;
			}
			echo $this->component->render($data,dirname(__FILE__).'/vote.tpl.php');
		}
	}
}
?>