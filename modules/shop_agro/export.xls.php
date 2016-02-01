<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
		<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name=ProgId content=Excel.Sheet>
		<meta name=Generator content="Microsoft Excel 12">
		<style>

		table{font-size:10px}
		/*table{ border-bottom:  1px solid; border-right:  1px solid;}*/
		/*table td,table th{padding: 1px; border-left:  1px solid;border-top:  1px solid;font-size:10px}*/
		table th{text-align: center; font-weight: bold;}
		body{
		font-size:9px;
		}
		.xl65
	{mso-style-parent:style0;
	border:.5pt solid windowtext;}
</style></head>
<body>

<table border=0 cellpadding=0 cellspacing=0 style="border-collapse:collapse;table-layout:fixed;">
<thead>
<tr>
<th class="xl65">Заказ №</th>
<th class="xl65">Дата заказа</th>
<th class="xl65">Клиент</th>
<th class="xl65">Контактный телефон</th>
<th class="xl65">Область</th>
<th class="xl65">Город</th>
<th class="xl65">Улица</th>
<th class="xl65">Заказываемые товары/услуги</th>
<th class="xl65">Способ доставки</th>
<th class="xl65">Способ оплаты</th>
<th class="xl65">Стоимость заказа</th>
<th class="xl65">Доставка</th>
<th class="xl65">Итог</th>
<th class="xl65">Дата доставки</th>
<th class="xl65">Статус</th>

<th class="xl65">Комментарий</th>
</tr>
</thead>
<tbody>
<?
$price=0;
$delivery=0;
$total=0;
foreach ($rs as $row) {
	$r=@unserialize($row['address']);
	$price+=$row['price'];
	$delivery+=$row['delivery'];
	$total+=$row['total_price'];
	
	?>
<tr>
<td class="xl65"><?=$row['id']?></td>
<td class="xl65"><?=dte($row['create_time'],'d.m.Y H:i')?></td>
<td class="xl65"><?=$row['fullname']?></td><?/*a:7:{s:6:"region";s:20:"Свердловская область";s:4:"city";s:15:"г. Екатеринбург";s:6:"street";s:15:"Карла Либкнехта";s:5:"house";s:2:"22";s:4:"flat";s:1:"1";s:5:"porch";s:1:"2";s:5:"floor";s:1:"1";}*/?>
<td class="xl65"><?=$row['phone']?></td>
<td class="xl65"><?=@$r['region']?></td>
<td class="xl65"><?=@$r['city']?></td>
<td class="xl65"><?=@$r['street']?> <?=@$r['house']?> <?=@$r['flat']?> </td>
<td class="xl65">
<?if(!empty($oi[$row['id']])){?>
<?=implode(', ',$oi[$row['id']])?>
<?}?>
</td>
<td class="xl65"><?=$row['delivery_desc']?></td>
<td class="xl65"><?=$row['pay_system_desc']?></td>
<td class="xl65"><?=$row['price']?></td>
<td class="xl65"><?=$row['delivery']?></td>
<td class="xl65"><?=$row['total_price']?></td>
<td class="xl65"><?=dte($row['date'],'d.m.Y')?></td>
<td class="xl65"><?=$row['order_status_desc']?></td>

<td class="xl65"><?=$row['comment']?></td>
</tr>
<?}?>
</tbody>
<tfoot>
<tr>
<td colspan="10"></td>
<th class="xl65"><?=$price?></th>
<th class="xl65"><?=$delivery?></th>
<th class="xl65"><?=$total?></th>
</tr>
</tfoot>
</table>
</body>
</html>
