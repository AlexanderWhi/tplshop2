<form class="common_form" id="claim_form" action="/contacts/?act=send" >
<a class="close_popup_form" href="#">Закрыть</a>
<input type="hidden" name="type" value="claim">
<input type="hidden" name="url" value="<?=$this->mod_uri?>">
<h1>ПРИЁМ ЗАЯВОК НА ВЗЫСКАНИЕ ЗАДОЛЖЕННОСТИ</h1>
<table>
<tr><th><label>ФИО:<span>*</span></label></th><td><input type="text" name="name" class="field"/></td></tr>
<tr><th><label>Телефон:</label></th><td><input name="phone" class="field"/></td></tr>
<tr><th><label>E-mail:</label></th><td><input name="mail" class="field"/></td></tr>
<tr><th><label>Сообщение:<span>*</span></label></th><td><textarea name="comment" class="field"></textarea></td></tr>
<?if(defined('IMG_SECURITY')){?>
<tr><th><label>Введите код:<span>*</span></label></th><td><?$this->capture("claim")?></td></tr>
<?}?>
<tr><th></th><td><input type="submit" class="button" name="send" value="Отправить сообщение"/></td></tr>
</table>
<div id="claim_form_success" class="success"><?=$this->getText('msg_claim_success')?></div>
</form>
