<table class="grid">
<tr><th>Дата</th><th>сумма руб.</th></tr>	
<? foreach ($rs as $row){?>
<tr>
	<td><?=$row['t']?></td>
	<td><strong><?=$row['s'];?></strong></td>
</tr>
<? }?>
</table>