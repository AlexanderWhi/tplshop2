<?foreach ($rs as $row) {?>
	<div class="item">
	<input type="checkbox" name="item[]" value="<?=$row['id']?>" id="item<?=$row['id']?>" checked><label for="item<?=$row['id']?>" style="float:left;height:20px;"></label>
	<div>
	<a href="/catalog/goods/<?=$row['id']?>/" class="header"><?=$row['name']?></a>
	<strong><?=price($row['price'])?></strong>
	
	
	</div>
	<div>
	<a href="#" class="down"></a><input name="count_list[<?=$row['id']?>]" value="<?=$row['count']?>" class="count <?=$row['weight_flg']?'weight':'' ?>" /><a href="#" class="up"></a>
	
	</div>
	<a class="remove" href="#"></a>
	</div>
<?}?>

<button name="add_basket" class="button grand" type="submit">Отправить в корзину</button>
<br>
<br>
<button name="save_list" class="button silver">Сохранить изменения</button><a class="remove_list" href="#">Удалить список</a>