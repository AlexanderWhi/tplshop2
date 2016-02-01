<div id="comments">
<table>
<tr>
<?foreach ($rs as $i=>$row) {?>
<td class="item <?if($row['status']==0){?>hidden<?}?>">

<?if($this->isAdmin()){?>
	<?if($row['status']==1){?>
	<a class="remove_goods_comment title" href="#<?=$row['id']?>" rel="<?=$row['id']?>"  title="Скрыть">[X]</a>
	<?}else{?>
	<a class="show_goods_comment title" href="#<?=$row['id']?>" rel="<?=$row['id']?>"  title="Показать">[+]</a>
	<?}?>
	<a class="title" href="/admin/comment/edit?id=<?=$row['id']?>" rel="<?=$row['id']?>"  title="написать ответ">[A]</a>
<?}?>

	<?if($row['avat']){?>
	<img src="<?=scaleImg($row['avat'],'w160')?>">
	<?}?>

	<a href="/vendor/<?=$row['u_id']?>/#fb" class="header"><?=$row['company']?></a>
	<p class="comment"><?=htmlspecialchars($row['comment'])?></p>
	<a href="/vendor/<?=$row['u_id']?>/#fb">Читать полностью</a>
	
	<?if(trim($row['answer'])){?>
		<p class="answer"><?=htmlspecialchars($row['answer'])?></p>
		<div style="text-align:right"><small><?=fdte($row['time_answer'])?></small></div>
	<?}?>
</td>
<?if(($i+1)%2==0 && $i){?>
</tr><tr>
<?}?>
<?}?>
</tr>
</table>
</div>
<script type="text/javascript" src="/modules/comment/common_comment.js"></script>