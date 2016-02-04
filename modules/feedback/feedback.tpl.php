<div>
<?=$this->getText($this->mod_alias)?>
<a class="fb_send" href="#feedback-form"><span>Отправить отзыв</span></a><br><br>
</div>
<div id="feedback">
	<?foreach($rs as $row){?>
	<div class="item">
		<div class="autor"><?=$row['name']?>  <span class="date"><?=fdte($row['time'])?></span></div>
		<div class="description">
		<?=$row['comment']?>
		</div>
		
		<?if(trim($row['answer'])){?>
		<div class="answer">
		<?=$row['answer']?>
		</div>
		<?}?>
	</div>	
	<?}?>
	</div>
	<div class="page"><?$pg->display()?></div>

<?if($FB_CAN_SEND_MSG){?>

<form class="common" id="feedback-form" action="?act=send"  enctype="multipart/form-data" target="fr" method="POST">
<iframe name="fr" style="display:none"></iframe>
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="type" value="feedback">
<input type="hidden" name="url" value="<?=$this->uri?>">
<h2>Оставить свой отзыв:</h2>
<div class="block">

<?$name=$this->getUser('name');
if(!$name){
	$name=trim("{$this->getUser('last_name')} {$this->getUser('first_name')} {$this->getUser('middle_name')}");
}?>

<div class="error" id="error-name"></div>
<div class="error" id="error-comment"></div>
<div class="error" id="error-capture"></div>


<div class="inline-block">

<input  placeholder="Имя *" name="name" class="field top" value="<?=$name?>"><br>
<input  placeholder="Телефон" name="phone" class="field bottom"><br>
<div>
<?if(defined('IMG_SECURITY')){?>
		
		<?$this->capture("feedback")?><label>Введите код</label>
<?}?>
</div>
</div>

<div class="inline-block m">
<textarea name="comment" class="field " placeholder="Сообщение"></textarea><br>

	<input type="submit" class="button" name="send" value="Отправить отзыв">
</div>
</div>


</form>
<div class="success"><?=$this->getText('msg_feedback_success')?></div>
<?}?>
<script type="text/javascript" src="/modules/feedback/feedback.js"></script>