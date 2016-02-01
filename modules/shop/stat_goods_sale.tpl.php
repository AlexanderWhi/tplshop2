<table class="grid">
<tr><th>id</th><th>Название</th><th>Количество</th></tr>	
<? foreach ($rs as $row){?>
<tr>
	<td><?=$row['id']?></td>
	<td><?=$row['name']?></td>
	<td><strong><?=$row['c'];?></strong></td>
</tr>
<? }?>
</table>