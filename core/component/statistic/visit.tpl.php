<table class="grid">
<tr><th>���������� ������������ �������</th><th style="width:100px">�����</th><th style="width:100px">����������</th></tr>
<?foreach ($rs as $key=>$val){?>
<tr>
<td><a target="_blank" href="<?=$key;?>"><?=$key;?></a></td>
<td><strong><?=$val['all'];?></strong></td><td><strong><?=$val['all_v'];?></strong></td>
</tr>
<?}?>
</table>