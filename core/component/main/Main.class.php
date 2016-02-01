<?
include_once 'core/component/statistic/SiteStatistic.class.php';
class Main extends SiteStatistic  {
//	protected  $tplComponent="main.tpl.php";
//	protected  $tplContainer="core/tpl/common_container.tpl.php";
	
	function actDefault(){
		$this->explorer=array();
		parent::actDefault();
	}
//	
//	function actTest(){
//
//		echo $this->render(array('data'=>'Привет дата'),$this->tplComponent);
//	}
}
?>