<?if($rs){$i=0;foreach ($rs as $item){?>
<div class="<?=++$i!=count($rs)?'pagebreak':''?>" style="width:600px">
<h1>Путевой лист №<?=$item['id']?></h1>
<h2>Водитель</h2>
<?=$item['name']?> [<?=$item['car']?>]
________________________
		
<h2>Заказы</h2>
<?=$item['waybillOrders']?>
</div>
<hr />
<?}}else{?>
Ничего не найдено
<?}?>