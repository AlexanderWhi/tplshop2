<dl id="guestbook">
<?=$this->getText($this->mod_uri)?>
<div>
<a href="#guestbook-form" onclick="document.getElementById('guestbook-form').style.display='block';">�������� ���������</a>
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
<a class="button_long" style="cursor:pointer" onclick="document.getElementById('guestbook-form').style.display='block';return false">�������� �����</a>
<form style="display:none" method="POST" id="guestbook-form">

<label>���:<span>*</span></label>
<input type="text" name="name" class="field"/>

<label>�������:</label>
<input name="phone" class="field"/>

<label>����������� �����:</label>
<input name="mail" class="field"/>

<label>����� ������: <small>���� ����</small></label>
<input name="order_num" class="field"/>

<label>���� ���������: </label>
<input name="theme" class="field"/>

<label>���������:<span>*</span></label>
<textarea name="comment" class="field" style="height:100px"></textarea><br />

<?if(false &&!empty($score)){?>
<label>������� �� ��������� ����:</label>
<?foreach ($score as $item) {?>
	<input type="radio" name="score" value="<?=$item['field_value']?>"/> <?=$item['value_desc']?><br />
<?}?> 
<?}?>
<?if(defined('IMG_SECURITY')){?>
<div style="float:left">
	������� ��� : <span style="color: red">*</span><br/>
	<img align="absmiddle" src="/capture/capture.jpg" onclick="this.src='/capture/capture.jpg?'+Math.random()" alt="" title="������ ����� ��������"/>
	<input name="capture" maxlength="4"/>
</div>
	<?}?>
	<input type="submit" class="button" name="send" value="���������"/>
</form>

<div id="success">���������� �� �����!</div>

<script type="text/javascript" src="/modules/guestbook/guestbook.js"></script>