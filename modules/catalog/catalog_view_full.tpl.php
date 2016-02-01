<table class="goods_full">
<tbody>
<?foreach ($catalog as $item) {?>

<colgroup>
<tr class="<?if(isset($in_basket[$item['id']])){?>changed<?}?>">

<td class="img">
<?if($item['img']){?>
<img src="<?=scaleImg($item['img'],'w40')?>" alt=" <?=htmlspecialchars($item['name'])?>"/>
<?}else{?>
<!--	<img src="/template/mandarin/img/no_img.jpg"/>-->
<?}?>
</td>
<td class="name_desc">
Артикул:<strong><?=$item['product']?></strong>
<a class="name" title=" <?=$item['name']?>" href="/catalog/goods/<?=$item['id'] ?>/"><?=isset($_GET['search'])?preg_replace('='.preg_quote($_GET['search']).'=i','<span style="color:red">\0</span>',$item['name']):$item['name']?></a>
<?=$item['description']?>
</td>

<td class="price">

<span class="label">Цена за штуку:</span> <span class="price"><?=number_format($item['price'],2,',',' ')?> р.</span><br/>
<?if(!empty($item['price_pack'])){?>
<span class="label">Цена за упаковку:</span> <span class="price"><?=number_format($item['price_pack'],2,',',' ')?> р.</span>
<?}?>


</td>
<td>
<input class="count1" name="count[<?=$item['id']?>]" value="<?=!empty($in_basket[$item['id']])?$in_basket[$item['id']]:'1'?>" <?=$item['weight_flg']?'class="weight"':'' ?>>
<br />
<select name="unit_sale[<?=$item['id']?>]">
<?foreach ($unit_sale as $val=>$desc) {?>
	<option value="<?=$val?>"><?=$desc?></option>
<?
if(empty($item['price_pack']))break;//если не продаётся по упаковкам то показываем только штуки
}?>
</select>

</td>
</tr>

<tr class="item_foot">
<td colspan="2" class="in_stock_compare">
<div>
<!--<input type="checkbox" name="item[]" value="<?=$item['id']?>">-->

<?in_stock($item['in_stock']);?>
</div>
</td>

<td colspan="2"><a class="add" href="#" rel="<?=$item['id'] ?>">Добавить в корзину</a></td>
</tr>
</colgroup>
<?}?>
</tbody>
</table>