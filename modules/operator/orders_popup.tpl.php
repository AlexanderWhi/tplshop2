<form id="orders_popup_form" method="POST">		
<?$pg->display();?>
<?if($rs){?>
<table class="grid data">
	<tr>
		<th><input type="checkbox" name="all"/></th>
		<th>�����</th>
		<th>����/����� ��������</th>
		<th>����</th>
		<th>���������</th>
		<th>������</th>
		<th>��������</th>
		<th>����</th>
	</tr>
	<?foreach($rs as $k=>$item){?>
		<tr>
<td><input class="sel_item" type="checkbox" name="sel_item[]" value="<?=$item["id"];?>"/></td>
<td>�<strong style="font-size:14px"><?=$item['id'];?></strong>
<br /><?=$item["create_time"]?>
</td>
<td style="white-space:nowrap">
<strong><?=dte($item['date']);?></strong><br />
<i><?=$item['time']?></i>
</td>
<td><i><?=$item['address'];?></i></td>
<td><strong><?=$item['price']?> �.</strong></td>
<td><strong><?=$item['discount']?> �.</strong></td>
<td><strong><?=$item['delivery']?> �.</strong></td>
<td><strong><?=$item['total_price']?> �.</strong></td>
</tr>
	<?}?>
</table>
<?$pg->display();?>
<?}else{?>
<div>������ ����</div>
<?}?>
</form>
<script type="text/javascript" src="/modules/operator/orders_popup.js"></script>