<div>
<?=$this->getText($this->mod_alias)?>
</div>





<form  class="common" id="contacts-form" action="/contacts/?act=send" >

<input type="hidden" name="type" value="<?=$this->getType()?>">
<input type="hidden" name="url" value="<?=$uri?>">

<div class="inline">

	<div>
	<label>�������������, ����������: <span>*</span></label><small class="error" id="error-name"></small><br>
	<input name="name" class="field" value="<?=$this->getUser('name')?>" style="width:710px">
	</div>
	
	<div>
	<label>E-mail: <span>*</span></label></label><small class="error" id="error-mail"></small><br>
	<input name="mail" class="field" value="<?=$this->getUser('mail')?>" style="width:332px;margin-right:30px">
	</div>
	
	<div>
	<label>���������� �������:</label><br>
	<input name="phone" class="field" value="<?=$this->getUser('phone')?>"style="width:332px;">
	</div>
	
	
	<div>	<label>���������� ����������: </label><br>	<input name="count" class="field" style="width:190px" >	</div><br>
	<div>	<label>���� ����������: </label><br>	<input name="date" class="field"  style="width:230px;margin-right:20px">	</div>
	<div>	<label>����� ����������: </label><br>	<input name="time" class="field"  style="width:180px;margin-right:20px">	</div>
	<div>	<label>����� ����������:</label><br>	<input name="place" class="field"  style="width:230px">	</div>
	<div>	<label>����� ������:</label><br><br>	
	
	<input name="pay" type="radio" id="r1" value="�������� ������ �� �����"><label for="r1" class="r">�������� ������ �� �����</label>	
	<input name="pay" type="radio" id="r2" value="����������� ������"><label for="r2" class="r">����������� ������</label>	
	
	</div>
	
	
<br>
<br>
	<div>
	<label>���� ���������: <span>*</span></label><small class="error" id="error-comment"></small><br>
	<textarea name="comment" class="field" style="width:710px"><?=$text?></textarea>
	</div>
	
	<?if(defined('IMG_SECURITY')){?>
	<div>
	<label>������� ���: <span>*</span></label><small class="error" id="error-capture"></small><br>
	
	<?$this->capture($this->getType())?>
	
	</div>
	<?}?>
	
	



</div>
<br>

<div style="text-align:center">
<input type="submit" class="button" name="send" value="�������� ���">
</div>

</form>
<div class="success"><?=$this->getText('msg_contacts_success')?></div>

<script type="text/javascript" src="/modules/contacts/contacts.js"></script>