<?
class MSXML{
	var $xml;
	var $data;
	function __construct($xml,$data,$enc=null){
		
		if($enc){
			array_walk_recursive($data,'toUtf');
		}
		
		$this->xml=$xml;
		$this->data=$data;
	}
	function render(){
		
		$xml=$this->xml;
		$data=$this->data;
		
		return $this->rend($this->xml,$this->data);
		
	}
	
	function rend($str,$data){	
		
		
//		if(preg_match_all('|{\?([a-z_\d]+)}(.+){\?/\1}|Usim',$str,$res)){
//			foreach ($res[0] as $n=>$d) {
//				
//				$result='';
//				if(isset($data[$res[1][$n]])){
//					$result=$this->rend($res[2][$n],$data[$res[1][$n]]);
//				}
//				$str=str_replace($res[0][$n],$result,$str);
//			}
//		}
			
		if(preg_match_all('|{([:\?])([a-z_\d]+)}(.+){\1/\2}|Usim',$str,$res)){
			
			
			foreach ($res[0] as $n=>$d) {
					
				if($res[0][$n][1]==':'){
					
					if($res[2][$n]=='pagebreaks'){
						
					}
					
					$result='';
					if(!empty($data[$res[2][$n]][0])){
						foreach ($data[$res[2][$n]] as $row){
							$result.=$this->rend($res[3][$n],$row);
						}
						
					}else{
						if(!empty($data[$res[2][$n]])){
							$result=$this->rend($res[3][$n],$data[$res[2][$n]]);
						}
						
					}
	
					$str=str_replace($res[0][$n],$result,$str);
					
				}else{
						$result='';
						if(isset($data[$res[2][$n]])){
//							$result=$this->rend($res[3][$n],$data[$res[2][$n]]);
							$result=$this->rend($res[3][$n],$data);
						}
						$str=str_replace($res[0][$n],$result,$str);
				}
			}
		}
		if(preg_match_all('|{!([a-z_\d]+)}|Usim',$str,$res)){
			foreach ($res[0] as $n=>$d) {
			
				if(isset($data[$res[1][$n]])){
					$result=$data[$res[1][$n]];
					$str=str_replace($d,$result,$str);
				}
				
			}
		}
		
		return $str;
	}
}

?>