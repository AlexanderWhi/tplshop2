<?if($rs){?>
<table class="grid">
	<tr>
		<th>�</th>
		<th>�����</th>
		<th>����/����� ��������</th>
		<th>����������</th>
		<th>����</th>
		
		<th>���������</th>
	</tr>
	<?foreach($rs as $k=>$item){?>
	<tr>
		<td><?=$item['sort'];?></td>
		<td>�<?=$item['id'];?></td>
		<td><strong><?=dte($item['date']);?></strong><br /><i><?=$item['time']?></i></td>
		<td><i><?=$item['fullname'];?></i> <strong><?=$item['phone'];?></strong></td>
		<td><i><?=$item['address'];?></i></td>
		<td><?=$item['total_price']?> �.</td>
	</tr>
	<?}?>
</table>
<?}else{?>
<div>������ ����</div>
<?}?>