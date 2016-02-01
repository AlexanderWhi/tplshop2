<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table class="grid">
<thead><tr><th>Описание</th><th style="width:60px">Кол-во</th><th style="width:100px;text-align:right">Цена,руб.</th><th style="width:100px;text-align:right">Стоимость,руб.</th></tr></thead>
<tbody>
<?foreach ($orderContent->basket as $id=>$item){?>
<tr id="item<?=$id?>" class="tr">
<td>
<div style="float:left">
<a href="#" class="replace" title="Заменить"><img src="/img/pic/edit_16.gif"></a>
<a href="#" class="delete" title="Удалить"><img src="/img/pic/del_16.gif"></a>
</div>
<strong><a target="_blank" href="/catalog/goods/<?=$id?>/"><?=$item['name']?></a></strong>
<br /><?=$item['description']?>
</td>
<td style="text-align:center"><input class="count" style="width:30px" name="count[<?=$id?>]" value="<?=$item['count']?>"/><?=$item['unit']?></td>
<td style="text-align:center"><input class="iprice" style="width:60px" name="iprice[<?=$id?>]" value="<?=$item['iprice']?>"></td>
<td style="text-align:center"><input class="price" readonly style="width:60px" name="price[<?=$id?>]" value="<?=$item['price']?>"></td>

</tr>
<?}?>
</tbody>
<tfoot>
<tr>
	<th style="text-align:right">Стоимость заказа</th>
	<th style="text-align:right" colspan="3"><?=$orderContent->getPrice();?> руб.</th>
</tr>
<tr><th style="text-align:right">Стоимость с учётом скидки <input style="width:40px" name="discount" value="<?=$orderContent->discount;?>"/> %</th><th style="text-align:right" colspan="3"><?=$orderContent->getDiscountPrice()?> руб.</th></tr>
<tr><th style="text-align:right">Доставка</th>
<th style="text-align:right" colspan="3"> 

<input style="width:40px" name="delivery" value="<?=$orderContent->delivery;?>"/> руб.</th></tr>
<tr><th style="text-align:right">Итого</th><th style="text-align:right" colspan="3"><?=$orderContent->getTotalPrice();?> руб.</th></tr>
</tfoot>
</table>
<?}else{?>
<div class="error">Ваша корзина заказов пуста</div>
<?}?>