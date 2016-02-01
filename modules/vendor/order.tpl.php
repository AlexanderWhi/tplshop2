<table class="order">
<tr>
<th>Заказ</th>
<th>Продукт</th>
<th>Начало</th>
<th>Окончание</th>
<th>Мин партия</th>
<th>Объем партии</th>
<th>Доставка</th>
<th>Покупатель</th>
<th>Кол во</th>
<th>Сумма</th>
<th>Оплата</th>
</tr>
<?foreach ($rs as $row) {?>
<tr>
<td><?=$row['orderid']?> <?=dte($row['create_time'])?></td>
<td><a href="/catalog/goods/<?=$row['p_id']?>/"><?=$row['name']?></a></td>
<td><?=dte($row['begin_date'])?></td>
<td><?=dte($row['end_date'])?></td>

<td class="center"><?=$row['volume_min']?></td>
<td class="center"><?=$row['volume_total']?></td>
<td><?=dte($row['delivery_date'])?></td>
<td><?=$row['phone']?></td>
<td class="center"><?=$row['count']?></td>
<td><?=$row['price']*$row['count']?></td>
<td><?=$row['pay_status']==1?'Да':'Нет'?></td>
<td>
<?if($row['pay_status']==1){?>
<?if($row['time']){?>
Выдано <?=$row['time']?>
<?}else{?>

<a href="?act=confirm&id=<?=$row['id']?>" class="confirm">Выдать</a>
<?}?>

<?/*a href="?act=return&id=<?=$row['id']?>">Возврат</a*/?>
<?}?>

</td>
</tr>	
<?}?>
</table>
<script type="text/javascript" src="/modules/vendor/order.js"></script>
