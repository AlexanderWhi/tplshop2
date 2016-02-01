<?if($orderContent instanceof Basket && $orderContent->basket){?>
<table width="100%" class="grid">
<tr><th></th><th style="text-align:left">��������</th><th width="60">���-��</th><th width="80">����</th><th width="80">���������</th><th>�����������</th></tr>
<?foreach ($orderContent->basket as $item){?>
<tr>
<td class="item" id="item<?=$item['itemid']?>">
<?if(!($this instanceof OrderPrint)){?>
<a href="/admin/catalog/goodsEdit/?id=<?=$item['itemid']?>">
	<?if($item['img']){?>
		<?if(isset($perf)){?>
		<img src="http://<?=$_SERVER['HTTP_HOST']?><?=scaleImg($item['img'],'w300st')?>"/>
		<?}else{?>
		<img src="http://<?=$_SERVER['HTTP_HOST']?><?=scaleImg($item['img'],'w60h60')?>"/>
		<?}?>
		
	<?}else{?>
		<img src="http://<?=$_SERVER['HTTP_HOST']?>/img/admin/no_image.png"/>
	<?}?>
</a>
<?}?>
</td>
<td><strong><a href="/admin/catalog/goodsEdit/?id=<?=$item['itemid']?>"><?=$item['name']?></a></strong>
<?//=$item['description']?><br>


<?if($edit){?>
<div>
<a href="javascript:replace(<?=$item['id']?>)">��������</a><br>
<?/*a href="javascript:nmn(<?=$item['id']?>)">�������� ������</a><br*/?>
</div>

<?}?>

</td>
<td style="text-align:center">
<?if($edit){?>
<input name="count[<?=$item['id']?>]" value="<?=$item['count']?>" style="width:50px">
<?}else{?>
<?=$item['count']?>
<?}?>
</td>
<td style="text-align:right">
<?if($edit){?>
<input name="price[<?=$item['id']?>]" value="<?=$item['price']?>" style="width:50px">
<?}else{?>
<?=price($item['price'])?>
<?}?>
�.</td>
<td style="text-align:right"><?=price2($item['sum'])?></td>
<td><?=htmlspecialchars($item['comment'])?></td>

<?if($edit){?>
<td style="width:50px">


<a href="javascript:remove(<?=$item['id']?>)">�������</a><br>


</td>
<?}?>
</tr>
<?}?>
<tr><th></th><th style="text-align:right">��������� ������</th><th></th><th></th><th style="text-align:right"><?=price2($orderContent->getSum());?><input type="hidden" name="order_price" value="<?=$orderContent->getSum();?>"></th></tr>

<tr><th></th><th style="text-align:right">��������� � ������ ������ 
<?if($edit){?>
<input name="discount" value="<?=$orderContent->discount;?>" style="width:50px">
<?}else{?>
<?=$orderContent->discount;?>
<?}?>
%</th><th></th><th></th><th style="text-align:right"><?=price2($orderContent->getDiscountSum())?></th></tr>


<tr><th></th><th style="text-align:right">��������</th><th width="60"></th><td></td><th style="text-align:right">
<?if($edit){?>
<input name="delivery" value="<?=$orderContent->delivery;?>" style="width:50px">
<?}else{?>
<?=$orderContent->delivery;?>
<?}?>
�.</th></tr>


<tr><th></th><th style="text-align:right">�����</th><th width="60"></th><td></td><th style="text-align:right"><?=price2($orderContent->getTotalPrice());?><input type="hidden" name="total_price" value="<?=$orderContent->getTotalPrice();?>"></th></tr>
</table>

<?if($edit){?>
<div>
<a href="javascript:replace(0)">��������</a><br>
</div>
<?}?>

<?}else{?>
<div class="error">���� ������� ������� �����</div>
<?}?>