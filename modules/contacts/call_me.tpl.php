<form class="common" id="call_me_form" action="/contacts/?act=send" >
<input type="hidden" name="type" value="call">
<?/*input type="hidden" name="url" value="<?=$this->mod_uri?>"*/?>
<input type="hidden" name="url" value="<?=$_SERVER['REQUEST_URI']?>">
<a class="close_popup_form" href="#"></a>
<span class="h1">Перезвоните мне</span>
<table>
<tr><th><label>ФИО:<span>*</span></label></th><td><input name="name" class="field"/></td></tr>
<tr><th><label>Телефон:<span>*</span></label></th><td><input name="phone" class="field"/></td></tr>
<?/*tr><th><label>E-mail:</label></th><td><input name="mail" class="field"/></td></tr*/?>
<tr><th><label>Вопрос:<span>*</span></label></th><td><textarea name="comment" class="field"></textarea></td></tr>
<?if(defined('IMG_SECURITY')){?>
<tr><th><label>Введите код:<span>*</span></label></th><td><?$this->capture("call")?></td></tr>
<?}?>
<tr><th></th><td><input type="submit" class="button" name="send" value="Отправить"/></td></tr>
</table>
<div id="call_me_form_success" class="success"><?=$this->getText('msg_call_me_success')?></div>
</form>
