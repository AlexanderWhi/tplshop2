<?function renderSub($sub,$deep=0){?>

<ul>
<?
$sub=array_values($sub);
foreach ($sub as $n=>$item) {
	if(empty($item['mod_id']))continue;
	?><li ><?
	if($deep==0){?>
		<a href="<?=$item['mod_alias']?>"><?=$item['mod_name']?></a>
	<?}else{?>
		<a href="<?=$item['mod_alias']?>"><?=$item['mod_name']?></a>
	<?}
	if(!empty($item['children']))renderSub($item['children'],$deep+1);
	?></li><?
}?>
</ul>
<?}?>
<div id="map-page">
<?if(in_array($this->mod_uri,array('/404/') )){?>
<?=$this->getText('/404/')?>
<?}else{?>
<?renderSub($this->getMap());?>
<?}?>



</div>