<div id="login-box">
			<?if($this->getUserId()){?>
				<div class="cabinet">
					<?if($this->isAdmin()){?>
						<a class="login" href="/cabinet/"><?=$this->getUser('login')?></a>
					<?}else{?>
						<a class="login" href="/shop/"><?=$this->getUser('name')?></a>
					<?}?>
					<a class="exit" href="?act=exit">�����</a>
				</div>
			<?}else{?>
				<div class="join">
					<a class="join" href="/join/">�����</a>
					<div class="join-bar">
						<form action="/join/?act=login" method="POST">
							<input title="�����" name="login" class="i" placeholder="����������� �����">
							<input title="������" name="password" class="i" type="password" placeholder="������">
							
							<br>		
							<!--<a href="/join/signup/">�����������</a>--> <a class="forget" href="/cabinet/unlock/">������ ������?</a>
							<br>	
							<button type="submit" class="login">�����</button> 
						</form>
					</div>
				</div>
				<a class="signup" href="/join/signup/">�����������</a>
					
			<?}?>
</div>