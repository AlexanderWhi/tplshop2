<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table width="100%" class="grid">
<tr><th style="text-align:left">Описание</th><th width="60">Кол-во</th><th width="80">Цена</th><th width="80">Стоимость</th></tr>
<?foreach ($orderContent->basket as $item){?>
<tr>
<td><strong><?=$item['name']?></strong><br /><?=$item['description']?>
</td>
<td style="text-align:center"><?=$item['count']?></td>
<td style="text-align:center"><?=number_format($item['price']/$item['count'],2,'.','')?></td>
<td style="text-align:right"><?=$item['price']?> р.</td>
</tr>
<?}?>
<tr><th style="text-align:right">Стоимость заказа</th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->getPrice();?> р.</th></tr>
<?if($orderContent->discount){?>
<tr><th style="text-align:right">Стоимость с учётом скидки <?//=$orderContent->discount;?> </th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->getDiscountPrice()?> р.</th></tr>
<?}?>
<?if($orderContent->delivery){?>
<tr><th style="text-align:right">Доставка</th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->delivery;?> р.</th></tr>
<?}?>

<tr><th style="text-align:right">Итого</th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->getTotalPrice();?> р.</th></tr>
</table>
<?}else{?>
<div class="error">Ваша корзина заказов пуста</div>
<?}?>