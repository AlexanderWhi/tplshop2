<html>
<head>
<style>
#export{font-size:10pt;border-collapse:collapse;margin:0 -15px}
#export th {font-weight:normal;text-align:center;padding:2px;border:solid .5pt #000}
#export td {padding:5px 2px;color:#000;border:solid .5pt #000}
#export .price {font-weight:bold;text-align:right;color:#F00}
</style>
</head>
<body>
<?if ($rs){$n=0;$cat='';?>
<table id="export">
<?foreach($rs as $item){?>
<?if($item['c_name']!=$cat){$cat=$item['c_name'];?>
<tr>
<td colspan="9">
<h2><?=$item['c_name']?></h2>
</td>
</tr>
<tr>
	<th>№</th>
	<th>Артикул</th>
	<th>Наименование товара, Характеристика</th>
	<th>Цена базовая</th>
	<th>Цена для пенсионеров</th>
	<th>Цена 2</th>
	<th>Цена 3</th>
	<th>Цена 4</th>
	<th>Цена 5</th>
</tr>
<?}?>
<tr>
	<td><?=++$n?></td>
	
	<td><?=$item['product']?></td>	
	<td><?=$item['i_name']?></td>		
	<td class="price"><?=number_format($item['price'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price1'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price2'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price3'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price4'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price4'],2,',',' ');?></td>
</tr>

<?}?>
</table>
<?}else{?>
	<div>Каталог пуст</div>
<?}?>
</body>
</html>