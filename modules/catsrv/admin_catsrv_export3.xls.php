<html>
<head>
<style>
#export{font-size:10pt;border-collapse:collapse;margin:0 -15px}
#export th {font-weight:normal;text-align:center;padding:2px;border:solid .5pt #000}
#export td {padding:5px 2px;color:#000;border:solid .5pt #000}
#export .price {font-weight:bold;text-align:right;color:#F00}
#export .city {color:#06C}
#export .mid {text-align:center;color:#666}
#export tr.odd {background:#EEE;}
#export tr.hover {background:#eff3cd;}
</style>
</head>
<body>
<?if ($rs){$n=0;$cat='';?>
<table id="export">
<tbody>
<?foreach($rs as $item){?>
<?if($item['c_name']!=$cat){$cat=$item['c_name'];?>
<tr>
<td colspan="7">
<h2><?=$item['c_name']?></h2>
</td>
</tr>
<tr>
	<th>№</th>
	<th>Артикул</th>
	<th>Наименование товара, Характеристика</th>
	<th>Цена базовая</th>
	<th>Цена измененная</th>
	<th>Разница, руб</th>
	<th>Разница, %</th>
</tr>
<?}?>
<tr>
	<td><?=++$n?></td>
	
	<td><?=$item['product']?></td>	
	<td><?=$item['i_name']?></td>		
	<td class="price"><?=number_format($item['price_new'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price_old'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price_diff'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['percent'],2,',',' ');?></td>
</tr>
</tbody>
<?}?>
</table>
<?}else{?>
	<div>Каталог пуст</div>
<?}?>
</body>
</html>