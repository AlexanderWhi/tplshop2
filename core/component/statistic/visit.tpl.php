<table class="grid">
<tr><th>Статистика посещаемости страниц</th><th style="width:100px">Всего</th><th style="width:100px">Уникальных</th></tr>
<?foreach ($rs as $key=>$val){?>
<tr>
<td><a target="_blank" href="<?=$key;?>"><?=$key;?></a></td>
<td><strong><?=$val['all'];?></strong></td><td><strong><?=$val['all_v'];?></strong></td>
</tr>
<?}?>
</table>