<?
include('autocomplete.php');
include('../core/function.php');
if(!empty($_GET['term'])){
	
	$term=iconv('utf-8','cp1251',$_GET['term']);
	
	$q="SELECT DISTINCT name FROM sc_shop_prop WHERE LOWER(name) LIKE LOWER('$term%')";
	
	if(!empty($_GET['grp'])){
		$q="SELECT DISTINCT name FROM sc_shop_prop p WHERE p.grp='{$_GET['grp']}' AND LOWER(p.name) LIKE LOWER('$term%')";
	}
	$rs=$ST->select($q);

	$out=array();
	while ($rs->next())
	{
		$out[]=array('label'=>$rs->get('name'));
	}
	echo printJSON($out);
}
?>