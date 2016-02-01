<?foreach ($rs as $row) {?>
	<div class="item">
	
	<img src="<?=scaleImg($row['img'],'w70')?>">
	<div>
	<a href="/catalog/goods/<?=$row['id']?>/"><?=$row['name']?></a>
	<strong><?=price($row['price'])?></strong>
	
	<a href="#" class="down"></a><input name="count[<?=$row['id']?>]" value="1" class="count <?=$row['weight_flg']?'weight':'' ?>" /><a href="#" class="up"></a>
	
	</div>
	<a class="remove" href="#"></a>
	</div>
<?}?>

<button name="add_to_list" class="button long">Добавить в список</button>