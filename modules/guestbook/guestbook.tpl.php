<dl id="guestbook">
<?=$this->getText($this->mod_uri)?>
<div>
<a href="#guestbook-form" onclick="document.getElementById('guestbook-form').style.display='block';">Написать сообщение</a>
</div>
<br>
<br>
<br>
<br>
	<?foreach($rs as $item){?>
		<dt>				
			<?=$item['name']?> <small><?=dte($item['time'])?></small>
		</dt>
		<dd>
			<?=htmlspecialchars($item['comment'])?>
			<?if(trim($item['answer'])){?>
			<div class="answer"><?=$item['answer']?></div>
			<?}?>
		</dd>
	<?}?>
	</dl>

<div class="page"><?$pg->display()?></div>
<a class="button_long" style="cursor:pointer" onclick="document.getElementById('guestbook-form').style.display='block';return false">Оставить отзыв</a>
<form style="display:none" method="POST" id="guestbook-form">

<label>ФИО:<span>*</span></label>
<input type="text" name="name" class="field"/>

<label>Телефон:</label>
<input name="phone" class="field"/>

<label>Электронная почта:</label>
<input name="mail" class="field"/>

<label>Номер заказа: <small>если есть</small></label>
<input name="order_num" class="field"/>

<label>Тема сообщения: </label>
<input name="theme" class="field"/>

<label>Сообщение:<span>*</span></label>
<textarea name="comment" class="field" style="height:100px"></textarea><br />

<?if(false &&!empty($score)){?>
<label>Оцените по выбранной теме:</label>
<?foreach ($score as $item) {?>
	<input type="radio" name="score" value="<?=$item['field_value']?>"/> <?=$item['value_desc']?><br />
<?}?> 
<?}?>
<?if(defined('IMG_SECURITY')){?>
<div style="float:left">
	Введите код : <span style="color: red">*</span><br/>
	<img align="absmiddle" src="/capture/capture.jpg" onclick="this.src='/capture/capture.jpg?'+Math.random()" alt="" title="Кликни чтобы обновить"/>
	<input name="capture" maxlength="4"/>
</div>
	<?}?>
	<input type="submit" class="button" name="send" value="Отправить"/>
</form>

<div id="success">Благодарим за отзыв!</div>

<script type="text/javascript" src="/modules/guestbook/guestbook.js"></script>