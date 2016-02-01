<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table class="goods">
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
if(!empty($goods['proposalid'])){
	$url="http://".$_SERVER['HTTP_HOST']."/catalog/proposal/".$goods['proposalid']."/";
}
$img=$goods['img'];
?>
<a class="img" style="background-image:url('<?=scaleImg($img,'w65h65')?>')" href="<?=$url?>"  class="title" title=" <?=htmlspecialchars($goods['description'])?>"></a>
</td>
<td>
<a  title=" <?=htmlspecialchars($goods['description'])?>" class="title" href="<?=$url?>"><?=$goods['name']?></a><div style="display:none"><?=$item['description']?></div>
</td>
</td>

<td style="text-align:right"><strong class="price"><?=price($goods['price'],'')?></strong> <small>руб.</small></td>
<td style="text-align:center"><?=$goods['count']?> </td>
<td style="width:120px" ><strong class="price"><?=price($goods['sum'],'')?></strong> <small>руб.</small></td>
</tr>
<?}?>
</tbody>
<tfoot>
<tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Стоимость заказа</td><td style="text-align:right"><strong class="price"><?=price($orderContent->getSum(),'');?></strong> <small>руб.</small></td></tr>
<?if($orderContent->discount){?>
<tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Стоимость с учётом скидки <?=$orderContent->discount;?> %</td><td style="text-align:right"><strong class="price"><?=price($orderContent->getDiscountPrice(),'')?></strong> <small>руб.</small></td></tr>
<?}?>
<?if($orderContent->delivery){?>
<tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Доставка</td><td style="text-align:right"><strong class="price"><?=price($orderContent->delivery,'');?></strong> <small>руб.</small></td></tr>
<?}?>

<?/*tr  class=" <?if($n++%2){?>even<?}?>"><td style="text-align:right" colspan="4">Итого</td><td style="text-align:right"><strong class="total_price"><?=price($orderContent->getTotalSum(),'');?></strong> <small>руб.</small></td></tr*/?>


<tr>
<th>Итого</th><td colspan="2" class="total_price"><strong><?=price($orderContent->getTotalSum(),'');?></strong> руб.</td>
<td style="text-align:right" colspan="2">
<a class="button_long" href="?act=makeBasket&id=<?=$id?>">Повторить заказ</a>
</td>
</tr>
</tfoot>
</table>
<?}else{?>
<div class="error">Ваша корзина заказов пуста</div>
<?}?>