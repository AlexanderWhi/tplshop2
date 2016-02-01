<?php
class PartnersBar {
	
	protected $component;

	protected $img='';
	protected $limit=100;
	
	function __construct($c,$d=null){
		$this->component=$c;
		if($d){
			$this->img='1';
			$this->limit=$d;
		}
		
		$this->actDefault();
	}
	
	function actDefault(){
		global $ST;
		$out='<ul>';
		$rs=$ST->select("SELECT * FROM sc_partner WHERE state=1 ORDER BY sort LIMIT ".$this->limit);
		while($rs->next()){
			$out.='<li>';
			$out.='<a href="'.$rs->get('url').'" title=" '.htmlspecialchars($rs->get('name')).'">';
			$out.='<img src="'.$rs->get('img'.$this->img).'" '.(!$this->img?'class="png" style="_background:auto"':'').' alt=" '.htmlspecialchars($rs->get('name')).'">';
			$out.='<strong>'.$rs->get('name').'</strong> ';
			$out.='<span>'.$rs->get('description').'</span>';
			$out.='</a>';
			$out.='</li>';
		}
		$out.='</ul>';
		echo $out;
	}
}
?>