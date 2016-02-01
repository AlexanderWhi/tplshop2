<?=$name?><br /><br />
<?foreach ($items as $item) {?>
<div class="item">
	<?=$item['name']?> : 
	
	<strong style="border-left:solid <?=(100*$item['result']/$all)?>px #95c12b"><?=round($item['result']/$all*100)?>%</strong></div>
<?}?>