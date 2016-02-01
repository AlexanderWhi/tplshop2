<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table width="100%" class="grid">
<tr><th rowspan="2">N п/п</th><th rowspan="2">Описание</th><th colspan="3">По заказу</th><th colspan="2">Собрано</th></tr>

<tr><th>Кол-во</th><th>Цена</th><th>Комментарий заказчика к товару</th><th>шт/кг</th><th>Комментарий исполнителя к товару</th>

<?$i=0;
foreach ($orderContent->basket as $item){?>
<tr>
<td><?=++$i?></td>
<td><strong><?=$item['name']?></strong>
</td>
<td style="text-align:center"><?=$item['count']?></td>
<td style="text-align:right"><?=price2($item['price'])?></td>
<td><?=$item['comment']?></td>
<td></td>
<td></td>

</tr>
<?}?>
<tr><td></td><th style="text-align:right">итого</th><th style="text-align:center"><?//=$orderContent->getCount()?></th><th style="text-align:right"><?//=price2($orderContent->getSum());?></th><td></td><td></td><td></td></tr>
<?/*if($orderContent->discount){?>
<tr><th style="text-align:right">Стоимость с учётом скидки <?=$orderContent->discount;?> %</th><th width="60"></th><th style="text-align:right"><?=$orderContent->getDiscountPrice()?> р.</th></tr>
<?}*/?>
<?/*if($orderContent->delivery){?>
<tr><th style="text-align:right">Доставка</th><th width="60"></th><th style="text-align:right"><?=$orderContent->delivery;?> р.</th></tr>
<?}*/?>

<?/*tr><th style="text-align:right">Итого</th><th width="60"></th><th style="text-align:right"><?=price2($orderContent->getTotalPrice());?></th></tr*/?>
</table>
<?}else{?>
<div class="error">Ваша корзина заказов пуста</div>
<?}?>