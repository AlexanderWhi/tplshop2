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
<h1><?=$this->getTitle()?></h1>
<?if ($rs){?>
<table id="export">
<thead>
<tr>
	<th>Каталог</th>
	<th>Артикул</th>
	<th>Товар</th>
	<th>Цена</th>
	<th>Наличие</th>
</tr>
</thead>
<tbody>
<?foreach($rs as $item){?>
<tr>
	<td><?=$item['c_name']?></td>
	<td><?=$item['product']?></td>	
	<td><?=$item['i_name']?></td>		
	<td class="price"><?=number_format($item['price'],2,',',' ');?></td>
	<td class="mid"><?=$item['in_stock']?></td>
</tr>
</tbody>
<?}?>
</table>
<?}else{?>
	<div>Каталог пуст</div>
<?}?>
</body>
</html>