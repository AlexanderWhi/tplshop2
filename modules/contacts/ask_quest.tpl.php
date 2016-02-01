<form class="common" id="ask_quest_form" action="/contacts/?act=send" >
<a class="close_popup_form" href="#"></a>
<input type="hidden" name="type" value="ask">
<input type="hidden" name="url" value="<?=$_SERVER['REQUEST_URI']?>">
<span class="h1">ЗАДАТЬ ВОПРОС</span>
<table>
<tr><th><label>ФИО: <span>*</span></label></th><td><input name="name" class="field"/></td></tr>
<tr><th><label>Телефон: </label></th><td><input name="phone" class="field"/></td></tr>
<tr><th><label>E-mail: <span>*</span></label></th><td><input name="mail" class="field"/></td></tr>
<tr><th><label>Сообщение: <span>*</span></label></th><td><textarea name="comment" class="field"></textarea></td></tr>
<?if(defined('IMG_SECURITY')){?>
<tr><th><label>Введите код: <span>*</span></label></th><td><?$this->capture("ask")?></td></tr>
<?}?>
<tr><th></th><td><input type="submit" class="button grand" name="send" value="Отправить сообщение"/></td></tr>
</table>
<div class="success"><?=$this->getText('msg_ask_quest_success')?></div>
</form>