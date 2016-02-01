<?if($rs){?>
<table>
<tr>
<th>Наименование</th>
<th>Цена</th>
<th>Агентские</th>
<th>Начало</th>
<th>Окончание</th>
<th>Мин партия</th>
<th>Объем партии</th>
<th>Доставка</th>
<th>Коментарий</th>
</tr>
<?foreach ($rs as $row) {?>
	<tr>
		<td><?=$row['name']?></td>
		<td><?=$row['price']?></td>
		<td><?=$row['awards_price']?></td>
		<td><?=dte($row['begin_date'])?></td>
		<td><?=dte($row['end_date'])?></td>
		<td><?=$row['volume_min']?></td>
		<td><?=$row['volume_total']?></td>
		<td><?=dte($row['delivery_date'])?> <?=$row['delivery_time']?></td>
		<td><?=$row['delivery_comment']?></td>
	</tr>
<?}?>

</table>
<?}?>