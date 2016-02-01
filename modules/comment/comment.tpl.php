<?foreach ($comment as $row) {?>
<div class="item <?if($row['status']==0){?>hidden<?}?>">

<?if($this->isAdmin()){?>
	<?if($row['status']==1){?>
	<a class="remove_goods_comment title" href="#<?=$row['id']?>" rel="<?=$row['id']?>"  title="Скрыть">[X]</a>
	<?}else{?>
	<a class="show_goods_comment title" href="#<?=$row['id']?>" rel="<?=$row['id']?>"  title="Показать">[+]</a>
	<?}?>
	<a class="title" href="/admin/comment/edit?id=<?=$row['id']?>" rel="<?=$row['id']?>"  title="написать ответ">[A]</a>
<?}?>

	
	<p>
	<?=htmlspecialchars($row['comment'])?>
	<?if(isset($row['rait'])){?>
		<?foreach ($row['rait'] as $r){?>
			<div class="rait r<?=$r['rating']?>">
			<?=$r['name']?>
			
			</div>
		<?}?>
	<?}?>
	</p>
	
	
	<strong><?=htmlspecialchars($row['name'])?>,</strong> <small><?=fdte($row['time'])?></small>
	
	<?if(trim($row['answer'])){?>
	<p class="answer">
	
	<?=htmlspecialchars($row['answer'])?>
	</p>
	<div style="text-align:right"><small><?=fdte($row['time_answer'])?></small></div>
	<?}?>
</div>
<?}?>


<?if($can_comment){?>
<?=$this->getText('goods_fb_comment')?>

<form class="common" id="goods-fb-form" action="/comment/?act=sendComment"><h2>Оставить отзыв</h2>
<input type="hidden" name="itemid" value="<?=$itemid?>">
<div class="inline_block">
<label class="i">Ваше имя:  <span>*</span></label>
<input name="name" class="field"><br>
<label class="i">Mail <small>(не публикуется)</small></label>
<input name="mail" class="field">
</div>
<div class="inline_block">
<label class="i">Текст отзыва:  <span>*</span></label>
<textarea name="comment" class="field"></textarea><br>
</div>

<br>
<br>
<?if($this->isAdmin()){?><a class="coin-edit" href="/admin/comment/">Редактировать оценки</a><?}?>
<?if($rait){?>
<h2>Оцените</h2>
<?foreach ($rait as $itm) {?>
<?=$itm['name']?>:
<div>
<?for($i=$itm['from'];$i<=$itm['to'];$i++){?>
<input type="radio" name="rait[<?=$itm['raitid']?>]" value="<?=$i?>" id="rait_<?=$itm['raitid']?>_<?=$i?>"><label for="rait_<?=$itm['raitid']?>_<?=$i?>"><?=$i?></label>
<?}?>
</div>
<?}?>
<?}?>

<br>
<?if(defined('IMG_SECURITY')){?>
<label class="i">Введите код: <span>*</span></label><?$this->capture("gfb")?><br>
<?}?>
<button type="submit" class="button long">Оставить отзыв</button>
</form>
<?}?>

</div>
<script type="text/javascript" src="/modules/comment/common_comment.js"></script>