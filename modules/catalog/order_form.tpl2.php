<div id="order">
<h2>Оформление заказа</h2>
<?if($this->getUserId()==0){//?>
<div class="block" >
<div id="block-logon-change">
	<input type="radio" name="reg" id="chb_logon" value="0"><label for="chb_logon">Выполнить вход для зарегистрированных пользователей</label></br>
	<!--block-logon-->
	<div id="block-logon" style="display:none">
		
		<!--<strong>Если Вы зарегистрированы, выполните вход.</strong><br>-->
		<div id="error-logon" class="error"></div>
		
		<label>E-mail:</label>
		<input name="login" class="field"/><br>
		
		<label>Пароль:</label>
		<input type="password" name="password" class="field"/><br>
		
		
		<input type="button" class="button long" name="logon" value="Авторизоваться"> | <a href="/cabinet/unlock/">Забыли пароль?</a>
		
		<hr>
	</div>
		
		<input checked type="radio" name="reg" value="1" id="reg_1"><label for="reg_1" class="l">Зарегистрироваться и разместить заказ (после регистрации доступен личный кабинет)</label><br>
		<!--<input type="radio" name="reg" value="0" id="reg_0"><label for="reg_0" class="l">Разместить заказ без регистрации</label><br><br>-->
		
		<div id="want_reg_tab">
			<?/*label>Логин:<span>*</span></label>
			<input name="reg_login" class="field"*/?>
			
			

			
			<input type="checkbox" name="auto_pass" id="auto_pass"><label class="l" for="auto_pass">Назначить пароль автоматически</label><br><br>
			
		
			<div id="error-mail" class="error"></div>
			
			<div id="error-reg_login" class="error"></div>
			
			
			<div id="auto_pass_tab">
			<label>Пароль:<span>*</span></label>
			<input name="reg_password" class="field" type="password"><span class="example"><?=$this->fieldExample('password')?></span>
			<br>
			<div id="error-reg_password" class="error"></div>
			
			<label>Подтвердить:<span>*</span></label>
			<input name="cpassword" class="field" type="password"><br>
		</div>
	
	</div>
</div>
</div>
<?}?>

<table>
<tr>
<td>
<label>Телефон :<span>*</span></label>
<input name="phone" class="field" value="<?=$phone?>" maxlength="32" title="<?=$this->fieldExample('phone')?>"><br>
<div id="error-phone" class="error"></div>
</td>

<td>
<label>ФИО:<span>*</span></label>
<input name="name" class="field" value="<?=$name?>" title="<?=$this->fieldExample('name')?>"><br>
<div id="error-name" class="error"></div>
</td>

<td>
<label>Дата время доставки:<span>*</span></label><br>
		<input name="date" class="date sh" value="<?=$date?>"/> <input name="time" class="field"><br>
		<div id="error-date" class="error"></div>
	<div id="error-time" class="error"></div>
</td>

</tr>

<tr>
<td>
<label>Город:<span>*</span></label>
<input name="city" class="field" value="<?=$city?>">
<div id="error-city" class="error"></div>
</td>
<td>
<label>Улица:<span>*</span></label>
<input name="street" class="field" value="<?=$street?>">
<div id="error-street" class="error"></div>
</td>

<td rowspan="3">
<label>Коментарии: </label>
<textarea name="additionally" class="field long"></textarea><br>


</td>

</tr>


<tr>
<td>
<label>Район:</label>
<input name="district" class="field" value="<?=$district?>">

</td>

<td>
<label>Дом / Номер подъезда:<span>*</span></label><br>
<input name="house" value="<?=$house?>" class="field sh" title="Дом">
<input name="porch" value="<?=$porch?>" class="field sh" title="подъезд">
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
<label>Этаж / Квартира:<span>*</span></label><br>
<input name="floor" value="<?=$floor?>" class="field sh" title="этаж">
<input name="flat" value="<?=$flat?>" class="field sh" title="кв/оф">
<div id="error-flat" class="error"></div>
</td>

</tr>

<tr>
<td colspan="3" class="right">
<button class="button order" name="order" type="submit" alt="Заказ в обработке" >Оформить заказ</button>
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
	<a href="javascript:openLoginForm()">Войти</a> /
	<a href="javascript:orderSignUp()">Зарегистрироваться</a>
	
	</div>
	<?=$this->getText('order_logon')?>
	</div>

</div>
<?}?>