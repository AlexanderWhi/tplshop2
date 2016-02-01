<table>
<tr>
<?foreach ($comment as $i=>$row) {?>
<td class="item <?if($row['status']==0){?>hidden<?}?>">

<?if($this->isAdmin()){?>
	<?if($row['status']==1){?>
	<a class="remove_goods_comment title" href="#<?=$row['id']?>" rel="<?=$row['id']?>"  title="Скрыть">[X]</a>
	<?}else{?>
	<a class="show_goods_comment title" href="#<?=$row['id']?>" rel="<?=$row['id']?>"  title="Показать">[+]</a>
	<?}?>
	<a class="title" href="/admin/comment/edit?id=<?=$row['id']?>" rel="<?=$row['id']?>"  title="написать ответ">[A]</a>
<?}?>

	
	<p class="comment"><?=htmlspecialchars($row['comment'])?></p>
	
	<strong><?=htmlspecialchars($row['name'])?>,</strong> <small><?=fdte($row['time'])?></small>
	
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

<?if($can_comment){?>
<?=$this->getText('goods_fb_comment')?>

<form class="common" id="goods-fb-form" action="/comment/?act=sendComment">
<input type="hidden" name="itemid" value="<?=$itemid?>">
<input type="hidden" name="type" value="<?=$type?>">
<div class="inline_block">
<label class="i">Ваше имя:  <span>*</span></label>
<input name="name" class="field"><br>
<label class="i">Mail <small>(не публикуется)</small></label>
<input name="mail" class="field">
<?if(defined('IMG_SECURITY')){?>
<label class="i">Введите код: <span>*</span></label><?$this->capture("gfb")?>
<?}?>

</div>
<div class="inline_block">
<label class="i">Текст отзыва:  <span>*</span></label>
<textarea name="comment" class="field"></textarea><br>
<button type="submit" class="button long right">Оставить отзыв</button>
</div>



<br>


</form>
<?}?>

</div>
<script type="text/javascript" src="/modules/comment/common_comment.js"></script>