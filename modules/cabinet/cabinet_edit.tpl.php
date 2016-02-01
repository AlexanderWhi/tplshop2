<div>
<?//include('menu.tpl.php')?>
<div id="t1" class="tabs">

<form class="common common_block" id="cabinet-edit" method="POST" action="?act=save">
<!--			<label class="i">Логин : *</label><strong><?=$login?></strong>-->
		<div class="inline_block">
			<strong>Контактные данные:</strong><br><br>
			<label class="i">ФИО: *</label><input name="name" class="field" value="<?=$name?>"/>
			<div id="error-name" class="error"></div>
			<br/>
			
			<label class="i">Телефон: * <small class="example">(+7 904 986 XXXX)</small></label><input name="phone" class="field" value="<?=$phone?>"/>
			
			<div id="error-phone" class="error"></div><br/>
					
			<label class="i">Email: *</label><input name="mail" class="field" value="<?=$mail?>"/>
			<div id="error-mail" class="error"></div><br/>
			
		</div>

		
		<div class="inline_block m">
		<strong>Изменить пароль:</strong><br><br>
		<div class="comment">Заполняйте только если хотите поменять пароль</div><br>
		<label class="i">Пароль: *</label><input name="password" type="password" class="field"/><br/>
		<div id="error-password" class="error"></div>
			
		<label class="i">Подтвердить: *</label><input name="cpassword" type="password" class="field"/><br/><br/>
		</div>
		<div>	
		<input type="submit" value="Сохранить" name="send" class="button">	
		</div>
</form>
<div id="success" style="display:none">
		Данные приняты
</div>

<a href="?act=exit" class="exit">Выйти из личного кабинета</a>


</div>

</div>



<script type="text/javascript" src="/modules/cabinet/cabinet_edit.js"></script>