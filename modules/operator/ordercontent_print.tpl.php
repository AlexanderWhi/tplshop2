<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table width="100%" class="grid">
<tr><th style="text-align:left">��������</th><th width="60">���-��</th><th width="80">����</th><th width="80">���������</th></tr>
<?foreach ($orderContent->basket as $item){?>
<tr>
<td><strong><?=$item['name']?></strong><br /><?=$item['description']?>
</td>
<td style="text-align:center"><?=$item['count']?></td>
<td style="text-align:center"><?=number_format($item['price']/$item['count'],2,'.','')?></td>
<td style="text-align:right"><?=$item['price']?> �.</td>
</tr>
<?}?>
<tr><th style="text-align:right">��������� ������</th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->getPrice();?> �.</th></tr>
<?if($orderContent->discount){?>
<tr><th style="text-align:right">��������� � ������ ������ <?//=$orderContent->discount;?> </th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->getDiscountPrice()?> �.</th></tr>
<?}?>
<?if($orderContent->delivery){?>
<tr><th style="text-align:right">��������</th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->delivery;?> �.</th></tr>
<?}?>

<tr><th style="text-align:right">�����</th><th width="60"></th><th width="60"></th><th style="text-align:right"><?=$orderContent->getTotalPrice();?> �.</th></tr>
</table>
<?}else{?>
<div class="error">���� ������� ������� �����</div>
<?}?>