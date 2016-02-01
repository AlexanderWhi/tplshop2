<html>
<head>
<style>
#export{font-size:10pt;border-collapse:collapse;margin:0 -15px}
#export th {font-weight:normal;text-align:center;padding:2px;border:solid .5pt #000}
#export td {padding:5px 2px;color:#000;border:solid .5pt #000}
#export .price {font-weight:bold;text-align:right;color:#F00}
#export .mid {text-align:center;color:#666}
#export tr.odd {background:#eff3cd;}

</style>
</head>
<body>
<h1><?=$this->getTitle()?></h1>
<?if ($rs){?>
<table id="export">
<thead>
<tr>
	<th>�������</th>
	<th>�������</th>
	<th>�����</th>
	<th>������� ����</th>
	<th>����</th>
	<th>�������</th>
</tr>
</thead>
<tbody>
<?
$c=0;
foreach($rs as $item){?>
<tr class="<?=$c++%2?'':'odd'?>">
	<td><?=$item['c_name']?></td>
	<td><?=$item['id']?></td>	
	<td><?=$item['i_name']?></td>		
	<td class="price"><?=number_format($item['price_base'],2,',',' ');?></td>
	<td class="price"><?=number_format($item['price'],2,',',' ');?></td>
	<td class="mid"><?=$item['in_stock']?></td>
</tr>
</tbody>
<?}?>
</table>
<?}else{?>
	<div>������� ����</div>
<?}?>
</body>
</html>