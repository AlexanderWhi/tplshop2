<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table class="order_goods">
<thead>
<tr>
<th class="first">Товары</th>
<th></th>

<th style="text-align:right;">Стоимость</th>
<th style="text-align:center">Кол-во</th>
<th style="" class="last">Сумма</th>

</tr>
</thead>
<tbody>
<?
$n=0;
foreach ($orderContent->basket as $goods){?>
<tr class="<?if($n++%2){?>even<?}?>">
<td>
<?$url="http://".$_SERVER['HTTP_HOST']."/catalog/goods/".$goods['id']."/";
$img=$goods['img'];
?>
<a class="img" style="background-image:url('<?=scaleImg($img,'w90h90')?>')" href="<?=$url?>"  class="title" title=" <?=htmlspecialchars($goods['description'])?>"></a>
</td>
<td>
<a  title=" <?=htmlspecialchars($goods['description'])?>" class="title" href="http://<?=$_SERVER['HTTP_HOST']?>/catalog/goods/<?=$goods['id']?>/"><?=$goods['name']?></a><div style="display:none"><?=$item['description']?></div>
</td>
</td>

<td><strong class="price"><?=price($goods['price'],'')?></strong> <small>руб.</small></td>
<td><?=$goods['count']?> шт.</td>
<td ><strong class="price"><?=price($goods['sum'],'')?></strong> <small>руб.</small></td>
</tr>
<?}?>
</tbody>
<tfoot>
<tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Стоимость заказа</td><td><strong class="price"><?=price($orderContent->getSum(),'');?></strong> <small>руб.</small></td></tr>
<?if($orderContent->discount){?>
<tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Стоимость с учётом скидки <?=$orderContent->discount;?> %</td><td style=""><strong class="price"><?=price($orderContent->getDiscountPrice(),'')?></strong> <small>руб.</small></td></tr>
<?}?>
<?if($orderContent->delivery){?>
<tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Доставка</td><td style=""><strong class="price"><?=price($orderContent->delivery,'');?></strong> <small>руб.</small></td></tr>
<?}?>

<?/*tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Итого</td><td style="text-align:right"><strong class="total_price"><?=price($orderContent->getTotalSum(),'');?></strong> <small>руб.</small></td></tr*/?>


<tr>
<th colspan="1" style="">Сумма заказа</th><td style="font-size:18px"><strong><?=price($orderContent->getTotalSum(),'');?></strong> руб.</td>
<td style="text-align:right" colspan="2">
<a class="button_long" href="?act=makeBasket&id=<?=$id?>">Повторить заказ</a>
<!--<a class="button_long" href="?act=makeNote&id=<?=$id?>">Добавить в шаблоны</a>-->
</td>
</tr>
</tfoot>
</table>
<?}else{?>
<div class="error">Ваша корзина заказов пуста</div>
<?}?>