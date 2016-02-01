<?php
include_once('core/component/Component.class.php');
class Partners extends Component {
		
	function actDefault(){
		global $ST;
		$rs=$ST->select("SELECT * FROM sc_partner WHERE state=1 ORDER BY sort");
		$this->setCommonCont();
		$this->display(array('rs'=>$rs),dirname(__FILE__).'/partners.tpl.php');
	}
	
	function getPartners(){
		global $ST;
		$out='<ul>';
		$rs=$ST->select("SELECT * FROM sc_partner WHERE state=1 ORDER BY sort");
		while($rs->next()){
			$out.='<li>';
			$out.='<a href="'.$rs->get('url').'" title=" '.htmlspecialchars($rs->get('name')).'">';
			$out.='<img src="'.$rs->get('img').'" alt=" '.htmlspecialchars($rs->get('name')).'">';
			$out.='<strong>'.$rs->get('name').'</strong> ';
			$out.='<span>'.$rs->get('description').'</span>';
			$out.='</a>';
			$out.='</li>';
		}
		$out.='</ul>';
		return $out;
	}
}
?>