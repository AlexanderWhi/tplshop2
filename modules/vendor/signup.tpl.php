<form class="common" id="sign" method="POST" action="?act=sign">
<div>
<?=$this->getText('vendor_sign')?>
</div>
			
<div class="inline_block">
<label class="i">�����������:  <span>*</span><small id="error-company" class="error"></small></label><input name="company" class="field"/>

<div class="comment">��� ���������� ���� ����������</div>
<br>
<label class="i">��������� �����: </label><input name="city" class="field"/>
<span id="error-city" class="error"></span><br>
<label class="i">E-mail: <span>*</span><small id="error-mail" class="error"></small></label><input name="mail" value="<?=$mail?>" class="field">
<input type="hidden" name="password" value="<?=$password?>">
<br>
</div>

<div class="inline_block">
<label class="i">���: <span>*</span><small id="error-name" class="error"></small></label><input name="name" class="field"/>
<div class="comment">� ��� ����� ����������� �� ���� ��������</div><br>
<label class="i">�����: </label><input name="address" class="field"/>
<span id="error-address" class="error"></span><br>
<label class="i">�������: <span>*</span><small id="error-phone" class="error"></small></label><input name="phone" class="field"/>
<br>
</div>

			
<label class="i">�������� ���������: </label><textarea name="info" class="field long"></textarea>
<span id="error-info" class="error"></span>
<div class="comment">
��������, ����������, ��������� ���� � ����� ���������: 
��� ��������� ���������� ���������, 
����� ���� ��������� �� �����������, ����� �������� �������
</div>

<br>
			
<label class="i">�����������: </label><textarea name="comment" class="field long"></textarea>
<span id="error-comment" class="error"></span><br>
			
			
					
			<?if(defined('IMG_SECURITY')){?>
					<label class="i">������ �� �����: <span>*</span><small id="error-capture" class="error"></small></label>
					<?$this->capture()?>
					
					<div class="comment">������� ���</div>
			<?}?>
			<br/><br/>
			<input type="submit" value="������������������" name="send" class="button grand">
			
			<br/><br/>	
			<small><?=$this->getText('signup_comment');?></small>
</form>
<div id="success" style="display:none">
		������� �� �����������! ��������� ��� ������� � <a href="/cabinet/">������ �������</a> ����� ������� �� ��������� ���� E-mail.
</div>
<script type="text/javascript" src="/modules/vendor/signup.js"></script>