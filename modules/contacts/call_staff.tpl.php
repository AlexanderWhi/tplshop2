<form class="common" id="call_staff_form" action="/contacts/?act=send" >
<a class="close_popup_form" href="#">�������</a>
<input type="hidden" name="type" value="call_staff">
<input type="hidden" name="url" value="<?=$this->mod_uri?>">
<h1>������� �������������</h1>
<table>
<tr><th><label>���:<span>*</span></label></th><td><input type="text" name="name" class="field"/></td></tr>
<tr><th><label>�������:</label></th><td><input name="phone" class="field"/></td></tr>
<tr><th><label>E-mail:</label></th><td><input name="mail" class="field"/></td></tr>
<tr><th><label>���������:<span>*</span></label></th><td><textarea name="comment" class="field"></textarea></td></tr>
<?if(defined('IMG_SECURITY')){?>
<tr><th><label>������� ���:<span>*</span></label></th><td><?$this->capture("call_staff")?></td></tr>
<?}?>
<tr><th></th><td><input type="submit" class="button" name="send" value="��������� ���������"/></td></tr>
</table>
<div class="success"><?=$this->getText('msg_ask_quest_success')?></div>
</form>