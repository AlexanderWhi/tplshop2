<?global $get;?>
<form class="common" method="POST" action="?act=login">
<input type="hidden" name="ref" value="<?=$get->get('ref')?>">
	<?if($error){?>
		 <div class="err">Неверный логин или пароль!</div>
	<?}?>
	<label>Логин:</label><br/>
	<input name="login" class="field"/><br/>
	<label>Пароль:</label><br/>
	<input name="password" class="field" type="password"/><br/>
	
	<div style="float:left">
	<a href="/join/signup/">Регистрация</a> | <a href="/cabinet/unlock/">Забыли?</a> | 
	<input type="checkbox" name="save" value="1" id="save" checked> <label for="save">Запомнить</label>
	</div>
	<br><br>
	<input class="button" type="submit" value="Войти">
	<br>
	<br>
	
	<script src="//ulogin.ru/js/ulogin.js"></script>
	<div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=<?=urlencode('http://'.$_SERVER['HTTP_HOST'].'/join/ulogin')?>"></div>
			
</form>