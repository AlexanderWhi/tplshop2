<?php
class Diagram{
	
	var $m_x;
	
	var $m_y;
	
	var $m_desc;
	
	var $max=0;
	var $min=0;
	
	//var $m_width=150;
	
	var $m_maxHeight=170;
	
	var $m_width=5;
	var $m_color="red";
	
	function render(){
		if(isset($this->m_y[0])){
			$this->min=$this->m_y[0];
		}
		for($i=0;$i<count($this->m_y);$i++){
			if($this->max<$this->m_y[$i]){
				$this->max=$this->m_y[$i];
			}
			if($this->min>$this->m_y[$i]){
				$this->min=$this->m_y[$i];
			}
		}
		
		$needCalc=false;
		if($this->max==$this->min){
				if($this->max>0){
					$height=$this->m_maxHeight;
					$this->min=0;
				}else{
					$height=0;
				}
		}else{
				
				$needCalc=true;
		}
		$output="<div>";
		$output.=$this->max;
		$output.='<table style="border-collapse: collapse; border: solid 0px;border-top:solid 1px black;border-bottom:solid 1px black;width:auto"><tr>';
		$output.='<td style="vertical-align:bottom;border:none">'.$this->min.'</td>';
		for($x=0;$x<count($this->m_x);$x++){
			
			if($needCalc)$height=ceil(($this->m_y[$x]-$this->min)/($this->max-$this->min)*$this->m_maxHeight);
			$output.='<td style="vertical-align:bottom;border:none;padding:1px">';
			$output.='<span style="font-size:7px">'.$this->m_y[$x].'</span>';
			$output.='<div title="'.$this->m_x[$x]." : ".$this->m_y[$x].'" style="font-size:0px;background-color:blue;width:'.$this->m_width.'px;height:'.$height.'px" onmouseover="this.style.backgroundColor=\'red\' "onmouseout="this.style.backgroundColor=\'blue\'"></div>';
			$output.='<span style="font-size:7px">'.$this->m_desc[$x].'</span>';
			$output.='</td>';
		}
		$output.='</tr></table>';
		$output.="</div>";
		return $output;
	}	
	
}