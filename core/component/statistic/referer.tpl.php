<table class="grid">
<tr><th>���������� ������, � ������� �������� ������������</th><th width="50">���������</th></tr>	
<? foreach ($rs as $key=>$val){?>
<tr>
	<td><a class="ref" target="_blank" href="http://<?=$key;?>"><?=$key;?></a></td>
	<td><strong><?=$val['all'];?></strong></td>
</tr>
<? }?>
</table>