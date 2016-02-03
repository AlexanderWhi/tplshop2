<?php
include_once("../config.php");
if(!mysql_connect(DB_HOST,DB_LOGIN,DB_PASSWORD)) {
	exit;
}else{
	mysql_select_db(DB_BASE);
	mysql_query("SET NAMES 'cp1251'");
}
session_start();

if($_GET['mode']=='country'){
	?><h2>Выбрать страну </h2>
	<div style="overflow:auto;height:280px;">
	<?
	$first=array('Россия');
	foreach($first as $item){
		$query="SELECT country_id,name FROM ref_country WHERE name='".$item."' LIMIT 1";
		$query=@mysql_query($query);
		if(true==($row=@mysql_fetch_array($query))){
			?><div><a href="#" onclick="showRegion(<?=$row['country_id']?>);return false;"><strong><?=$row['name']?></strong></a></div><?
		}
	}
	$query="SELECT country_id,name FROM ref_country WHERE name NOT IN('".join(',',$first)."') ORDER BY name";
	$query=@mysql_query($query); 
	while (true==($row=@mysql_fetch_array($query)))
	{
		?><div><a href="#" onclick="showRegion(<?=$row['country_id']?>);return false;"><?=$row['name']?></a></div><?
	}
	?></div><?
}
if($_GET['mode']=='region' && $_GET['id']>0){
	$first=array('Свердловская обл.','Москва и Московская обл.','Санкт-Петербург и область');
	?><h2>Выбрать регион </h2>
	<div class="nav"><a href="javascript:showCounty()">Все страны</a> / 
	<?
		$query="SELECT country_id,name FROM ref_country WHERE country_id='".$_GET['id']."' LIMIT 1";
		$query=@mysql_query($query);
		if(true==($row=@mysql_fetch_array($query))){
			?><a href="#" onclick="showRegion(<?=$row['country_id']?>);return false;"><?=$row['name']?></a><?
		}
	?>
	</div>
	<div style="overflow:auto;height:280px;">
	<?foreach($first as $item){	
		$query="SELECT region_id,name FROM ref_region WHERE name='".$item."' AND country_id=".intval($_GET['id'])." LIMIT 1";		
		$query=@mysql_query($query);
		if(true==($row=@mysql_fetch_array($query))){
			?><div><a href="#" onclick="showCity(<?=$row['region_id']?>);return false;"><strong><?=$row['name']?></strong></a></div><?
		}
	}	
	$query="SELECT region_id,name FROM ref_region WHERE country_id=".intval($_GET['id'])." AND name NOT IN('".join(',',$first)."') ORDER BY name";
	$query=@mysql_query($query); 
	while (true==($row=@mysql_fetch_array($query)))
	{
		?><div><a href="#" onclick="showCity(<?=$row['region_id']?>);return false;"><?=$row['name']?></a></div><?
	}
	?></div><?
}
if($_GET['mode']=='city' && $_GET['id']>0){
	$first=array('Екатеринбург','Москва','Санкт-Петербург');	
	?>
	<h2>Выбрать город </h2>
	<div class="nav"><a href="javascript:showCountry()">Все страны</a> / 
	<?
		$query="SELECT c.country_id,c.name FROM ref_region AS r,ref_country AS c WHERE r.country_id=c.country_id AND r.region_id='".$_GET['id']."' LIMIT 1";
		$query=@mysql_query($query);
		if(true==($row=@mysql_fetch_array($query))){
			?><a href="javascript:showRegion(<?=$row['country_id']?>)"><?=$row['name']?></a> / <?
		}
		$query="SELECT region_id,name FROM ref_region WHERE  region_id='".$_GET['id']."' LIMIT 1";
		$query=@mysql_query($query);
		if(true==($row=@mysql_fetch_array($query))){
			?><a href="javascript:showCity(<?=$row['region_id']?>)"><?=$row['name']?></a> <?
		}
	?>
	</div>
	<div style="overflow:auto;height:280px;">
	<?foreach($first as $item){
		$query="SELECT city_id,name FROM ref_city WHERE region_id=".intval($_GET['id'])." AND name='".$item."' LIMIT 1";
		
		$query=@mysql_query($query);
		if(true==($row=@mysql_fetch_array($query))){
			?><div><a href="javascript:onChangeCity(<?=$row['city_id']?>)"><strong><?=$row['name']?></strong></a></div><?
		}
	}
	$query="SELECT city_id,name FROM ref_city WHERE region_id=".intval($_GET['id'])." AND name NOT IN('".join(',',$first)."') ORDER BY name";
	$query=@mysql_query($query); 
	
	while (true==($row=@mysql_fetch_array($query)))
	{
		?><div><a href="javascript:onChangeCity(<?=$row['city_id']?>)"><?=$row['name']?></a></div><?
	}
	?></div><?	
}
?>