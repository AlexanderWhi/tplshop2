<script src="//ulogin.ru/js/ulogin.js"></script>
	<div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=<?=urlencode('http://'.$_SERVER['HTTP_HOST'].'/join/ulogin')?>"></div>
	

<form class="common" id="sign" method="POST" action="?act=sign">
<div>
<?=$this->getText('sign')?>
</div>
<div class="inline_block">
<label class="i">E-mail: <span>*</span><small id="error-mail" class="error"></small></label>

<input name="mail" value="<?=$mail?>" class="field">
<input type="hidden" name="password" value="<?=$password?>">
<br>

<label class="i">�����: </label><input name="address" class="field"/>
			<?/*<input name="street" class="field" title="�����" style="width:160px"/>
			<input name="house" class="field" title="���" style="width:40px"/>
			<input name="flat" class="field" title="�� / ��" style="width:50px"/>
			<input name="porch" class="field" title="�������" style="width:60px"/>
			<input name="floor" class="field" title="����" style="width:40px"/>*/?>
			
<span id="error-address" class="error"></span><br>
</div>		
<div class="inline_block">
<label class="i">���:  <span>*</span><small id="error-name" class="error"></small></label>
<input name="name" class="field"/>
<br>
			
<label class="i">�������: </label><input name="phone" class="field"/>
<span id="error-phone" class="error"></span><br>
</div>
			
		
			<?if(defined('IMG_SECURITY')){?>
					<label class="i">������ �� �����: <span>*</span><small id="error-capture" class="error"></small></label>
					<?$this->capture()?>
					
					<div class="comment">������� ���</div>
			<?}?>
			<br/><br/>
			<input type="submit" value="������������������" name="send" class="button grand">
			
			<br/><br/>	
			<small><?=$this->getText('signup_comment');?></small>
			
			<br>
	<br>
	
	
</form>
<div id="success" style="display:none">
		������� �� �����������! ��������� ��� ������� � <a href="/cabinet/">������ �������</a> ����� ������� �� ��������� ���� E-mail.
</div>
<script type="text/javascript" src="/modules/join/signup.js"></script>