<div id="order">
<h2>���������� ������</h2>
<?if($this->getUserId()==0){//?>
<div class="block" >
<div id="block-logon-change">
	<input type="radio" name="reg" id="chb_logon" value="0"><label for="chb_logon">��������� ���� ��� ������������������ �������������</label></br>
	<!--block-logon-->
	<div id="block-logon" style="display:none">
		
		<!--<strong>���� �� ����������������, ��������� ����.</strong><br>-->
		<div id="error-logon" class="error"></div>
		
		<label>E-mail:</label>
		<input name="login" class="field"/><br>
		
		<label>������:</label>
		<input type="password" name="password" class="field"/><br>
		
		
		<input type="button" class="button long" name="logon" value="��������������"> | <a href="/cabinet/unlock/">������ ������?</a>
		
		<hr>
	</div>
		
		<input checked type="radio" name="reg" value="1" id="reg_1"><label for="reg_1" class="l">������������������ � ���������� ����� (����� ����������� �������� ������ �������)</label><br>
		<!--<input type="radio" name="reg" value="0" id="reg_0"><label for="reg_0" class="l">���������� ����� ��� �����������</label><br><br>-->
		
		<div id="want_reg_tab">
			<?/*label>�����:<span>*</span></label>
			<input name="reg_login" class="field"*/?>
			
			

			
			<input type="checkbox" name="auto_pass" id="auto_pass"><label class="l" for="auto_pass">��������� ������ �������������</label><br><br>
			
		
			<div id="error-mail" class="error"></div>
			
			<div id="error-reg_login" class="error"></div>
			
			
			<div id="auto_pass_tab">
			<label>������:<span>*</span></label>
			<input name="reg_password" class="field" type="password"><span class="example"><?=$this->fieldExample('password')?></span>
			<br>
			<div id="error-reg_password" class="error"></div>
			
			<label>�����������:<span>*</span></label>
			<input name="cpassword" class="field" type="password"><br>
		</div>
	
	</div>
</div>
</div>
<?}?>

<table>
<tr>
<td>
<label>������� :<span>*</span></label>
<input name="phone" class="field" value="<?=$phone?>" maxlength="32" title="<?=$this->fieldExample('phone')?>"><br>
<div id="error-phone" class="error"></div>
</td>

<td>
<label>���:<span>*</span></label>
<input name="name" class="field" value="<?=$name?>" title="<?=$this->fieldExample('name')?>"><br>
<div id="error-name" class="error"></div>
</td>

<td>
<label>���� ����� ��������:<span>*</span></label><br>
		<input name="date" class="date sh" value="<?=$date?>"/> <input name="time" class="field"><br>
		<div id="error-date" class="error"></div>
	<div id="error-time" class="error"></div>
</td>

</tr>

<tr>
<td>
<label>�����:<span>*</span></label>
<input name="city" class="field" value="<?=$city?>">
<div id="error-city" class="error"></div>
</td>
<td>
<label>�����:<span>*</span></label>
<input name="street" class="field" value="<?=$street?>">
<div id="error-street" class="error"></div>
</td>

<td rowspan="3">
<label>����������: </label>
<textarea name="additionally" class="field long"></textarea><br>


</td>

</tr>


<tr>
<td>
<label>�����:</label>
<input name="district" class="field" value="<?=$district?>">

</td>

<td>
<label>��� / ����� ��������:<span>*</span></label><br>
<input name="house" value="<?=$house?>" class="field sh" title="���">
<input name="porch" value="<?=$porch?>" class="field sh" title="�������">
<div id="error-house" class="error"></div>
</td>

</tr>

<tr>

<td>
<label>E-mail:<span>*</span></label>
<input name="mail" class="field" value="<?=$mail?>">
<div id="error-mail" class="error"></div>
</td>


<td>
<label>���� / ��������:<span>*</span></label><br>
<input name="floor" value="<?=$floor?>" class="field sh" title="����">
<input name="flat" value="<?=$flat?>" class="field sh" title="��/��">
<div id="error-flat" class="error"></div>
</td>

</tr>

<tr>
<td colspan="3" class="right">
<button class="button order" name="order" type="submit" alt="����� � ���������" >�������� �����</button>
</td>
</tr>

</table>


<?if(!$this->getUserId()){?>
<div class="order_logon">
	<div class="notice">
	<a href="" class="close"></a>
	<?=$this->getText('order_logon_notice')?>
	<span></span>
	</div>

	<div class="b">
	<a href="javascript:openLoginForm()">�����</a> /
	<a href="javascript:orderSignUp()">������������������</a>
	
	</div>
	<?=$this->getText('order_logon')?>
	</div>

</div>
<?}?>