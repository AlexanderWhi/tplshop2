<div id="order">
<h2>Оформление заказа</h2>
<?if($this->getUserId()==0){//?>

<div id="block-logon-change" class="block">
	<input type="radio" name="reg" id="reg_-1" value="-1" checked><label for="reg_-1">Без регистрации</label></br>
	<input type="radio" name="reg" id="chb_logon" value="0"><label for="chb_logon">Выполнить вход для зарегистрированных пользователей</label></br>
	<!--block-logon-->
	<div id="block-logon" style="display:none">
		
		<!--<strong>Если Вы зарегистрированы, выполните вход.</strong><br>-->
		<div id="error-logon" class="error"></div>
		
		<label class="i">E-mail:</label>
		<input name="login" class="field"/><br>
		
		<label class="i">Пароль:</label>
		<input type="password" name="password" class="field"/><br>
		
		
		<input type="button" class="button long" name="logon" value="Авторизоваться"> | <a href="/cabinet/unlock/">Забыли пароль?</a>
		
		<hr>
	</div>
		
		<input type="radio" name="reg" value="1" id="reg_1"><label for="reg_1" class="l">Зарегистрироваться и разместить заказ (после регистрации доступен личный кабинет)</label><br>
		<!--<input type="radio" name="reg" value="0" id="reg_0"><label for="reg_0" class="l">Разместить заказ без регистрации</label><br><br>-->
		
		<div id="want_reg_tab">
			<?/*label class="i">Логин:<span>*</span></label>
			<input name="reg_login" class="field"*/?>
			
			<label class="i">E-mail: <span>*</span></label>
			<input name="mail" class="field" value="<?=$mail?>">

			<span id="auto_pass_lab" style="display:none">
				<input type="checkbox" name="auto_pass" id="auto_pass" value="1" checked><label class="l" for="auto_pass">Назначить пароль автоматически</label><br><br>
			</span>
		
			<div id="error-mail" class="error"></div>
			
			<div id="error-reg_login" class="error"></div>
			
			
			<div id="auto_pass_tab" style="display:none">
			<label class="i">Пароль:<span>*</span></label>
			<input name="reg_password" class="field" type="password"><span class="example"><?=$this->fieldExample('password')?></span>
			<br>
			<div id="error-reg_password" class="error"></div>
			
			<label class="i">Подтвердить:<span>*</span></label>
			<input name="cpassword" class="field" type="password"><br>
		</div>
	
	</div>
</div>

<?}?>
<div class="inline_block" >
<label class="i">Представьтесь: <span>*</span></label>
<input name="name" class="field" value="<?=$name?>"><span class="example"><?=$this->fieldExample('name')?></span><br>
<div id="error-name" class="error"></div>

<label class="i">Ваш телефон : <span>*</span></label>
<input name="phone" class="field" value="<?=$phone?>" maxlength="32"><span class="example"><?=$this->fieldExample('phone')?></span><br>
<div id="error-phone" class="error"></div>


<label class="i">Ваш адрес: <span>*</span></label>
<input name="address" class="field" value="<?=$address?>"><br>
		<?/*<input name="street" value="<?=$street?>" class="field title" title="Улица" style="width:180px">
		<input name="house" value="<?=$house?>" class="field title" title="Дом" style="width:40px">
		<input name="flat" value="<?=$flat?>" class="field title" title="кв/оф" style="width:30px">
		<input name="porch" value="<?=$porch?>" class="field title" title="подъезд" style="width:20px">
		<input name="floor" value="<?=$floor?>" class="field title" title="этаж" style="width:20px">
		<div id="addrbk"><?=$this->addrBk()?></div>*/?>
		
		<div id="error-address" class="error"></div>	

		
		<?/*label class="i">Дата доставки:<span>*</span></label>
		<input name="date" class="date" value="<?=$date?>"/><br>
		<div id="error-date" class="error"></div*/?>
		
		<?/*label class="i">Время:</label>
		<input name="time" class="field" maxlength="64"/>
		<div id="error-time" class="error"></div*/?>
		
	<?/*label class="i">Время доставки:</label>
	<input name="time" class="field"><br>
	<div id="error-time" class="error"></div*/?>
</div>

<div class="inline_block">

<label class="i">Комментарий: </label>
<textarea name="additionally" class="field" style="height:190px"></textarea><br>
</div>
<div class="block">
<input type="checkbox" class="checkbox" name="is_receiver" value="1" id="is_receiver" checked><label for="is_receiver">Адрес доставки совпадает с адресом плательщика</label>

<div id="receiver" style="display:none">
<h2>Адрес доставки</h2>
	<label class="i">ФИО: <span>*</span></label>
	<input name="to_name" class="field" ><br>
	<div id="error-to_name" class="error"></div>
	
	<label class="i">Город: <span>*</span></label>
	<input name="to_city" class="field" ><br>
	<div id="error-to_city" class="error"></div>	
	
	<label class="i">Адрес: <span>*</span></label>
	<input name="to_address" class="field" ><br>
	<div id="error-to_address" class="error"></div>	

</div>


</div>





<div class="block">
<?if($this->isAdmin()){?><a class="coin-edit" href="/admin/enum/sh_pay_system/">Редактировать способы оплаты</a><?}?>
<?if(!empty($pay_system_list)){?>
<h2>Способ оплаты</h2>

<?foreach ($pay_system_list as $k=>$desc) {?>
	<input name="pay_system" type="radio" class="radio" value="<?=$k?>" id="pay_system<?=$k?>" <?if($k==1){?>checked<?}?>>
	<label for="pay_system<?=$k?>"><?=$desc?></label>
<?}?>
<?}?>

</div>
<div class="block">
<?if($this->isAdmin()){?><a class="coin-edit" href="/admin/enum/sh_delivery_type/">Редактировать способы доставки</a><?}?>
<?if(!empty($delivery_type_list)){?>
<h2>Способ доставки</h2>

<?foreach ($delivery_type_list as $k=>$desc) {?>
	<input name="delivery_type" type="radio" class="radio" value="<?=$k?>" id="delivery_type<?=$k?>" <?if($k==1){?>checked<?}?>>
	<label for="delivery_type<?=$k?>"><?=$desc?></label>
<?}?>
<?}?>

</div>
<br>
<br>
<button class="button order" name="order" type="submit" alt="Заказ в обработке" >Оформить заказ</button>
</div>