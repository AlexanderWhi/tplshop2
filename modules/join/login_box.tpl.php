<div id="login-box">
			<?if($this->getUserId()){?>
				<div class="cabinet">
					<?if($this->isAdmin()){?>
						<a class="login" href="/cabinet/"><?=$this->getUser('login')?></a>
					<?}else{?>
						<a class="login" href="/shop/"><?=$this->getUser('name')?></a>
					<?}?>
					<a class="exit" href="?act=exit">Выход</a>
				</div>
			<?}else{?>
				<div class="join">
					<a class="join" href="/join/">Войти</a>
					<div class="join-bar">
						<form action="/join/?act=login" method="POST">
							<input title="Логин" name="login" class="i" placeholder="Электронная почта">
							<input title="Пароль" name="password" class="i" type="password" placeholder="Пароль">
							
							<br>		
							<!--<a href="/join/signup/">Регистрация</a>--> <a class="forget" href="/cabinet/unlock/">Забыли пароль?</a>
							<br>	
							<button type="submit" class="login">Войти</button> 
						</form>
					</div>
				</div>
				<a class="signup" href="/join/signup/">Регистрация</a>
					
			<?}?>
</div>