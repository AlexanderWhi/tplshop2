<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table width="100%" class="grid">
<tr><th style="text-align:left">��������</th><th width="60">���-��</th><th width="80">����</th></tr>
<?foreach ($orderContent->basket as $item){?>
<tr>
<td><strong><?=$item['name']?></strong>
</td>
<td style="text-align:center"><?=$item['count']?></td>
<td style="text-align:right"><?=price2($item['price'])?></td>
</tr>
<?}?>
<tr><th style="text-align:right">��������� ������</th><th width="60" style="text-align:center"><?=$orderContent->getCount()?></th><th style="text-align:right"><?=price2($orderContent->getSum());?></th></tr>
<?if($orderContent->discount){?>
<tr><th style="text-align:right">��������� � ������ ������ <?=$orderContent->discount;?> %</th><th width="60"></th><th style="text-align:right"><?=$orderContent->getDiscountPrice()?> �.</th></tr>
<?}?>
<?if($orderContent->delivery){?>
<tr><th style="text-align:right">��������</th><th width="60"></th><th style="text-align:right"><?=$orderContent->delivery;?> �.</th></tr>
<?}?>

<tr><th style="text-align:right">�����</th><th width="60"></th><th style="text-align:right"><?=price2($orderContent->getTotalPrice());?></th></tr>
</table>
<?}else{?>
<div class="error">���� ������� ������� �����</div>
<?}?>