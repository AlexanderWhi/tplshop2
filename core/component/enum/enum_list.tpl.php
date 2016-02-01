<table class="grid">
<?foreach ($rs as $row) {?>
	<tr><td><?=$row['field_name']?></td><td><a href="/admin/enum/<?=$row['field_name']?>/">#</a> <a href="#<?=$row['field_name']?>" class="remove"><img src="/img/pic/trash_16.gif"></a></td></tr>
<?}?>
</table>

<script type="text/javascript" src="/core/component/enum/enum.js"></script>