<?
include('autocomplete.php');
include('../core/function.php');
if(!empty($_GET['term'])){
	
	$term=iconv('utf-8','cp1251',$_GET['term']);
	
	
	$q="SELECT DISTINCT value FROM sc_shop_prop_val WHERE value LIKE '$term%'";
	
	if(!empty($_GET['prop'])){
		$prop=iconv('utf-8','cp1251',$_GET['prop']);
		$q="SELECT DISTINCT v.value FROM sc_shop_prop_val v,sc_shop_prop p WHERE v.prop_id=p.id AND p.name='$prop' AND v.value LIKE '$term%'";
	}
	$rs=$ST->select($q);
	$out=array();
	while ($rs->next())
	{
		$out[]=array('label'=>$rs->get('value'));
	}
	echo printJSON($out);
}
?>