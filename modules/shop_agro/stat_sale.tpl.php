<table class="grid">
<tr><th>����</th><th>����� ���.</th></tr>	
<? foreach ($rs as $row){?>
<tr>
	<td><?=$row['t']?></td>
	<td><strong><?=$row['s'];?></strong></td>
</tr>
<? }?>
</table>