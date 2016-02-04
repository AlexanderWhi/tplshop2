<div class="contacts">

<div id="print-content" class="block">


<?=$this->getText($this->mod_alias)?>
<?include('addr.tpl.php')?>


	
	
</div>

<!--<a href="/contacts/print/" class="print no-print" target="_blank">Печать страницы</a>-->
<br>
<div class="common_block no-print" >


<form  class="common no-print" id="contacts-form" action="/contacts/?act=send" >
<input type="hidden" name="type" value="<?=$type?>">
<input type="hidden" name="url" value="<?=$this->uri?>">

<div class="block">
<h2>Обратная связь</h2>
<?=$this->getText('contacts_comment')?>

<div class="inline-block">
	
	<div>
	<label>Текст сообщение: <span>*</span></label><small class="error" id="error-comment"></small><br>
	<textarea name="comment" class="field" ></textarea>
	</div>
	
	<div>
	<?if(defined('IMG_SECURITY')){?>
		<label>Введите код: <span>*</span></label><small class="error" id="error-capture"></small><br>
		<?$this->capture($type)?>
	<?}?>
	
	</div>
	
</div>

<div class="inline-block m">
	<div>
	<label>Представьтесь: <span>*</span></label><small class="error" id="error-name"></small><br>
	<input type="text" name="name" class="field sh" value="<?=$this->getUser('name')?>">
	</div>
	
	<div>
	<label>Контактный телефон:</label><br>
	<input name="phone" class="field sh" value="<?=$this->getUser('phone')?>">
	
	</div>
	<div>
	<label>E-mail: <span>*</span></label><small class="error" id="error-mail"></small><br>
	<input name="mail" class="field sh" value="<?=$this->getUser('mail')?>">
	</div>

	<input type="submit" class="button" name="send" value="Отправить">
	
	
	
	
</div>


</div>

</form>
<div class="success no-print"><?=$this->getText('msg_contacts_success')?></div>
</div>

</div>
<script type="text/javascript" src="/modules/contacts/contacts.js"></script>