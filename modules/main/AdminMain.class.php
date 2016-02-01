<?
include_once 'core/component/statistic/SiteStatistic.class.php';
class AdminMain extends SiteStatistic  {
	
//	function actDefault(){
//		$this->explorer=array();
//		parent::actDefault();
//	}
	function __construct(){
		$this->adminGrp[]='operator';
	}
}
?>