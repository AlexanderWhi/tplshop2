<?if($orderContent instanceof Basket && $orderContent->basket){?>
<div class="basket_content">
<?if($basket=$orderContent->basket){?>
<table class="goods">
<tbody>
<?
$price=0;
foreach ($basket as $item){
	$price+=$item['sum'];
?>
<tr>
<td rowspan="3" class="img">
	<a href="http://<?=$_SERVER['HTTP_HOST']?>/catalog/goods/<?=$item['id']?>/"  class="title" title=" <?=htmlspecialchars($item['description'])?>">
	<?if($item['img']){?>
		<img src="<?=scaleImg($item['img'],'w150h115')?>">
	<?}?>
	</a>
</td>

<td>
<?/*span class="date"><?=dte($date)?></span*/?>

<?if(isset($item['num'][0]['validity_time']) && $validity_time=$item['num'][0]['validity_time']){?>
<span class="date">
<?=dte($date)?>
<?if(strtotime($validity_time)>time()){?>

<?}else{?>
Время использования окончено
<?}?>
</span>
<?}?>
</td>
</tr><tr>
<td colspan="4">
<a title=" <?=htmlspecialchars($item['description'])?>" class="name title" href="http://<?=$_SERVER['HTTP_HOST']?>/catalog/goods/<?=$item['id']?>/"><?=$item['name']?></a>
<?if(empty($is_letter)){?>
<div style="display:none"><?=$item['description']?></div>
<?}?>
</td>
</tr>
<tr>
<?if(true){?>
<td style="vertical-align:top">Купоны (<?=$item['count']?>):</td>

<td class="list">

<?foreach ($item['num'] as $num) {?>
	<span>№</span><strong><?=$num['num']?></strong> <?if($num['validity_time']){
		if(strtotime($num['validity_time'])>time()){
			?>
			<?if($item['is_universal'] && $this->isAdmin()){?><small>{<?=$num['activate_code']?>}</small><?}?>
			
			
			<?/*span>Годен до</span> <?=dte($num['validity_time'])?*/?>
			<?if($item['is_universal'] && $num['activate_item']>0){?>
			<div><a href="/catalog/goods/<?=$num['activate_item']?>/">Активирован</a></div>
			<?}?>
			
			<?
		}else{
			/*?><span style="color:red">Годен до <?=dte($num['validity_time'])?>. Время использования окончено</span> <?*/
		
		}}?><br>
<?}?>


</td>
<?}else{?>

<td style="white-space:nowrap">
Количество купонов: <strong class="price"><?=$item['count']?></strong>
</td>
<td style="text-align:right;"><strong class="price"><?=number_format($item['price'],0,',',' ')?>&nbsp;р.</strong></td>
<?}?>
<td style="text-align:right;"><strong class="price"><?=number_format($item['sum'],0,',',' ')?>&nbsp;р.</strong></td>
<td>

</tr>

<?}?>
</tbody>
<tfoot>
<?if(!empty($delivery)){
	$price+=$delivery;
	?>
<tr>
	<td>&nbsp;</td>
	
	<td style="text-align:right;" colspan="2">Стоимость доставки:</td>
	<td style="text-align:right;" id="delivery_price"><strong class="price"><?=number_format($delivery,0,',',' ');?>&nbsp;р.</strong></td>
</tr>
<?}?>
<tr>
	<td colspan="1">&nbsp;</td>
	<td colspan="1">
	
	</td>
	<td style="text-align:right;">Итого:</td>
	<td style="text-align:right;"><strong class="price"><?=number_format($price,0,',',' ');?>&nbsp;р.</strong></td>
	<td>&nbsp;</td>
</tr>
</tfoot>
</table>

<?}else{?>
<div>Ваша корзина заказов пуста</div>
<?}?>


</div>
<?}?>