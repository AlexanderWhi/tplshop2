<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table class="grid">
<thead><tr><th>��������</th><th style="width:60px">���-��</th><th style="width:100px;text-align:right">����,���.</th><th style="width:100px;text-align:right">���������,���.</th></tr></thead>
<tbody>
<?foreach ($orderContent->basket as $id=>$item){?>
<tr id="item<?=$id?>" class="tr">
<td>
<div style="float:left">
<a href="#" class="replace" title="��������"><img src="/img/pic/edit_16.gif"></a>
<a href="#" class="delete" title="�������"><img src="/img/pic/del_16.gif"></a>
</div>
<strong><a target="_blank" href="/catalog/goods/<?=$id?>/"><?=$item['name']?></a></strong>
<br /><?=$item['description']?>
</td>
<td style="text-align:center"><input class="count" style="width:30px" name="count[<?=$id?>]" value="<?=$item['count']?>"/><?=$item['unit']?></td>
<td style="text-align:center"><input class="iprice" style="width:60px" name="iprice[<?=$id?>]" value="<?=$item['iprice']?>"></td>
<td style="text-align:center"><input class="price" readonly style="width:60px" name="price[<?=$id?>]" value="<?=$item['price']?>"></td>

</tr>
<?}?>
</tbody>
<tfoot>
<tr>
	<th style="text-align:right">��������� ������</th>
	<th style="text-align:right" colspan="3"><?=$orderContent->getPrice();?> ���.</th>
</tr>
<tr><th style="text-align:right">��������� � ������ ������ <input style="width:40px" name="discount" value="<?=$orderContent->discount;?>"/> %</th><th style="text-align:right" colspan="3"><?=$orderContent->getDiscountPrice()?> ���.</th></tr>
<tr><th style="text-align:right">��������</th>
<th style="text-align:right" colspan="3"> 

<input style="width:40px" name="delivery" value="<?=$orderContent->delivery;?>"/> ���.</th></tr>
<tr><th style="text-align:right">�����</th><th style="text-align:right" colspan="3"><?=$orderContent->getTotalPrice();?> ���.</th></tr>
</tfoot>
</table>
<?}else{?>
<div class="error">���� ������� ������� �����</div>
<?}?>