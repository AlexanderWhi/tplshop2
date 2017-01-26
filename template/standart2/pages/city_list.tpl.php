<div>
<strong><?=$city?></strong>
<ul>
<?foreach ($city_list as $k=>$v) {?>
	<li>
	<a href="javascript:changeCity('<?=$v?>')"><?=$v?></a>
	</li>
<?}?>
</ul>
</div>