<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table width="100%" class="grid">
<tr><th rowspan="2">N �/�</th><th rowspan="2">��������</th><th colspan="3">�� ������</th><th colspan="2">�������</th></tr>

<tr><th>���-��</th><th>����</th><th>����������� ��������� � ������</th><th>��/��</th><th>����������� ����������� � ������</th>

<?$i=0;
foreach ($orderContent->basket as $item){?>
<tr>
<td><?=++$i?></td>
<td><strong><?=$item['name']?></strong>
</td>
<td style="text-align:center"><?=$item['count']?></td>
<td style="text-align:right"><?=price2($item['price'])?></td>
<td><?=$item['comment']?></td>
<td></td>
<td></td>

</tr>
<?}?>
<tr><td></td><th style="text-align:right">�����</th><th style="text-align:center"><?//=$orderContent->getCount()?></th><th style="text-align:right"><?//=price2($orderContent->getSum());?></th><td></td><td></td><td></td></tr>
<?/*if($orderContent->discount){?>
<tr><th style="text-align:right">��������� � ������ ������ <?=$orderContent->discount;?> %</th><th width="60"></th><th style="text-align:right"><?=$orderContent->getDiscountPrice()?> �.</th></tr>
<?}*/?>
<?/*if($orderContent->delivery){?>
<tr><th style="text-align:right">��������</th><th width="60"></th><th style="text-align:right"><?=$orderContent->delivery;?> �.</th></tr>
<?}*/?>

<?/*tr><th style="text-align:right">�����</th><th width="60"></th><th style="text-align:right"><?=price2($orderContent->getTotalPrice());?></th></tr*/?>
</table>
<?}else{?>
<div class="error">���� ������� ������� �����</div>
<?}?>