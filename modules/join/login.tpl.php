<?global $get;?>
<form class="common" method="POST" action="?act=login">
<input type="hidden" name="ref" value="<?=$get->get('ref')?>">
	<?if($error){?>
		 <div class="err">�������� ����� ��� ������!</div>
	<?}?>
	<label>�����:</label><br/>
	<input name="login" class="field"/><br/>
	<label>������:</label><br/>
	<input name="password" class="field" type="password"/><br/>
	
	<div style="float:left">
	<a href="/join/signup/">�����������</a> | <a href="/cabinet/unlock/">������?</a> | 
	<input type="checkbox" name="save" value="1" id="save" checked> <label for="save">���������</label>
	</div>
	<br><br>
	<input class="button" type="submit" value="�����">
	<br>
	<br>
	
	<script src="//ulogin.ru/js/ulogin.js"></script>
	<div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=<?=urlencode('http://'.$_SERVER['HTTP_HOST'].'/join/ulogin')?>"></div>
			
</form>