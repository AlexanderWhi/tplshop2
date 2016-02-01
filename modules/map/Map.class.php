<?
include_once 'core/component/Component.class.php';
class Map extends Component  {
	function actDefault(){
		$this->display(array(),dirname(__FILE__).'/map.tpl.php');
	}

	/*function displayMap(){
		$query="SELECT mod_alias,mod_name,mod_id FROM sc_module WHERE mod_parent_id=0  AND mod_location like '%main%' order by mod_position";
		$rs=$this->getStatement()->execute($query);
		?><ul><?
		while($rs->next()){
			?><li><a href="<?=$rs->get('mod_alias')?>"><?=$rs->get('mod_name')?></a><?
			$query="SELECT mod_alias,mod_name FROM sc_module WHERE mod_parent_id=".$rs->get('mod_id')."  AND mod_location like '%main%' order by mod_position";
			$rs1=$this->getStatement()->execute($query);
			if($rs1->getCount()){
				?><ul><?
				while($rs1->next()){
				?><li><a href="<?=$rs1->get('mod_alias')?>"><?=$rs1->get('mod_name')?></a></li><?
				}
				?></ul><?
			}
			?></li><?
		}
		?></ul><?
	}*/
	
	function getMap(){
		return $this->getMenu(array('main','map'));
	}
	
	
//	function show(){
//		$this->displayMap();
//	}	
}
?>