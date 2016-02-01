<?if($rs){?>
<table class="grid">
	<tr>
		<th><input type="checkbox" name="all"/></th>
		<th>Заказ</th>
		<th>Порядок</th>
		<th>Дата/время доставки</th>
		<th>Куда</th>
		<th>Стоимость</th>
		<th>Скидка</th>
		<th>Доставка</th>
		<th>Итог</th>
		<th>Статус</th>
	</tr>
	<?foreach($rs as $k=>$item){?>
	<tr>
		<td><input class="item" type="checkbox" name="item[]" value="<?=$item["id"];?>"/></td>
		<td><a href="<?=$this->mod_uri?>order/?id=<?=$item['id'];?>" title="Просмотр">№<strong style="font-size:14px"><?=$item['id'];?></strong>
		<br /><?=dte($item["create_time"],'d.m H:i')?></a>
		</td>
		<td><input class="sort" style="width:20px" name="sort[<?=$k?>]" value="<?=$item['sort']?>"/></td>
		<td>
		<strong><?=dte($item['date']);?></strong><br />
		<i><?=$item['time']?></i>
		</td>
		<td><i><?=$item['address'];?></i></td>
		<td><?=$item['price']?> р.</td>
		<td><?=$item['discount']?> р.</td>
		<td><?=$item['delivery']?> р.<br />
		<strong><?=$this->delivery_type_list[$item['delivery_type']]?></td>
		<td><?=$item['total_price']?> р.</td>
		<td><strong class="order_status"><?=$order_status_list[$item['order_status']]?></strong></td>
	</tr>
	<?}?>
</table>
<?}else{?>
<div>Список пуст</div>
<?}?>