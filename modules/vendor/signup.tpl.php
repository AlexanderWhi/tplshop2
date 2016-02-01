<form class="common" id="sign" method="POST" action="?act=sign">
<div>
<?=$this->getText('vendor_sign')?>
</div>
			
<div class="inline_block">
<label class="i">Организация:  <span>*</span><small id="error-company" class="error"></small></label><input name="company" class="field"/>

<div class="comment">Как называется ваше фермерство</div>
<br>
<label class="i">Населённый пункт: </label><input name="city" class="field"/>
<span id="error-city" class="error"></span><br>
<label class="i">E-mail: <span>*</span><small id="error-mail" class="error"></small></label><input name="mail" value="<?=$mail?>" class="field">
<input type="hidden" name="password" value="<?=$password?>">
<br>
</div>

<div class="inline_block">
<label class="i">ФИО: <span>*</span><small id="error-name" class="error"></small></label><input name="name" class="field"/>
<div class="comment">С кем можно связываться по всем вопросам</div><br>
<label class="i">Адрес: </label><input name="address" class="field"/>
<span id="error-address" class="error"></span><br>
<label class="i">Телефон: <span>*</span><small id="error-phone" class="error"></small></label><input name="phone" class="field"/>
<br>
</div>

			
<label class="i">Описание продукции: </label><textarea name="info" class="field long"></textarea>
<span id="error-info" class="error"></span>
<div class="comment">
Напишите, пожалуйста, несколько слов о вашей продукции: 
где находится фермерское хозяйство, 
какие виды продукции вы производите, какие мощности имеются
</div>

<br>
			
<label class="i">Комментарий: </label><textarea name="comment" class="field long"></textarea>
<span id="error-comment" class="error"></span><br>
			
			
					
			<?if(defined('IMG_SECURITY')){?>
					<label class="i">Защита от спама: <span>*</span><small id="error-capture" class="error"></small></label>
					<?$this->capture()?>
					
					<div class="comment">Введите код</div>
			<?}?>
			<br/><br/>
			<input type="submit" value="Зарегистрироваться" name="send" class="button grand">
			
			<br/><br/>	
			<small><?=$this->getText('signup_comment');?></small>
</form>
<div id="success" style="display:none">
		Спасибо за регистрацию! Реквизиты для доступа в <a href="/cabinet/">личный кабинет</a> будут высланы на указанный вами E-mail.
</div>
<script type="text/javascript" src="/modules/vendor/signup.js"></script>