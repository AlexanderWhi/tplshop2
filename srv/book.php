<?php
include_once("../config.php");
if(!mysql_connect(DB_HOST,DB_LOGIN,DB_PASSWORD)) {
	exit;
}else{
	mysql_select_db(DB_BASE);
//	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET NAMES 'cp1251'");
}
include('../session.php');

if(isset($_SESSION['_USER']['u_id'])){
	$query="SELECT DISTINCT fullname,city,address,phone FROM sc_shop_order WHERE userid='".$_SESSION['_USER']['u_id']."' ORDER BY create_time DESC";
	$query=@mysql_query($query); 
	?>
	<h2>Ваша адресная книга</h2>
	<table><?
	
	while (true==($row=@mysql_fetch_array($query)))
	{
		?><tr>
		<td><a href="javascript:setFields('<?=htmlspecialchars($row['fullname'])?>','<?=htmlspecialchars($row['city'])?>','<?=htmlspecialchars($row['address'])?>','<?=htmlspecialchars($row['phone'])?>')"><?=$row['fullname']?></a></td>
		<td><?=$row['city']?></td>
		<td><?=$row['address']?></td>
		<td><?=$row['phone']?></td>
		</tr><?
//		echo $row['city']."\r\n";
		//echo iconv('utf-8','windows-1251',$row['city'])."\r\n";
	}
	?></table><?
}
?>