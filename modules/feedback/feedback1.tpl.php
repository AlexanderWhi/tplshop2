<div>
<?=$this->getText($this->mod_alias)?>
<a class="button" href="#feedback-form">Отправить отзыв</a><br>
</div>
<div id="feedback">
	<?foreach($rs as $row){?>
	
	<div class="item">
	
		
		<div class="description">
		<?=htmlspecialchars($row['comment'])?>
		</div>
		<div class="autor"><?=$row['name']?> <?=$row['phone']?> <span class="date"><?=dte($row['time'])?></span></div>
		
		<?if($row['answer']){?>
		<div class="answer"><?=$row['answer']?></div>	
		<?}?>	
	</div>	
	<?}?>
	</div>
	<div class="page"><?$pg->display()?></div>

<?if($FB_CAN_SEND_MSG){?>

<form class="common" id="feedback-form" action="?act=send"  enctype="multipart/form-data" target="fr" method="POST">
<div class=" block">
<iframe name="fr" style="display:none"></iframe>
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="type" value="feedback">
<input type="hidden" name="url" value="<?=$this->uri?>">
<h2>Написать отзыв</h2>
<?$name=$this->getUser('name');
if(!$name){
	$name=trim("{$this->getUser('last_name')} {$this->getUser('first_name')} {$this->getUser('middle_name')}");
}?>

<div class="inline-block">
<label>Фамилия имя: <span>*</span></label><small class="error" id="error-name"></small><br>
<input type="text" name="name" class="field" value="<?=$name?>"><br>
</div>
<div class="inline-block m">
<label>Город:</label><br>
<input name="city" class="field" value="<?=$this->getUser('city')?>"><br>

</div>

<div class="inline-block">
<label>Сообщение:<span>*</span></label><small class="error" id="error-comment"></small><br>
<textarea name="comment" class="field long"></textarea><br>
<?if(defined('IMG_SECURITY')){?>
		<label>Введите код: <span>*</span></label><small class="error" id="error-capture"></small><br>
		<?$this->capture("feedback")?>
	<?}?>
	<input type="submit" class="button long" name="send" value="Отправить отзыв">
</div>

</div>
</form>
<div class="success"><?=$this->getText('msg_feedback_success')?></div>
<?}?>
<script type="text/javascript" src="/modules/feedback/feedback.js"></script>