<?=$this->getText($this->type)?>

<a href="#faq-form" class="button">Задать вопрос</a><br>

<?if ($rs){?>
	<div class="faq">
	<?foreach($rs as $row){?>
	<div class="question" id="quest<?=$row['id']?>">					
		<a class="question expand" href="#ans<?=$row['id']?>"><?=$row['question']?></a>	
<!--		<span class="name"><?=$row['name']?>&nbsp;&nbsp;&nbsp;<?=fdte($row['time'])?></span>-->
	</div>
	<div class="answer" id="ans<?=$row['id']?>"><?=$row['answer']?></div>
	<?}?>
	</div>
	<div class="page"><?$pg->display()?></div>
<?}else{?>
<div>Список пуст</div>
<?}?>
<?if($this->cfg('FAQ_FORM')=='true'){?>
<br>
<form  class="common" id="faq-form" action="?act=send" >

<input type="hidden" name="type" value="contacts">
<input type="hidden" name="url" value="<?=$this->uri?>">

<div class="block">
<h2>Задать вопрос</h2>

<?if(false && $theme_list){?>
<div>
<label>Тема:</label><br>
<select name="theme">
<?foreach ($theme_list as $theme) {?>
	<option><?=$theme?></option>
<?}?>

</select></div>

<?}?>


<div><label>ФИО: <span>*</span></label><br><input type="text" name="name" class="field"/></div>
<div><label>Телефон:</label><br><input name="phone" class="field"/></div>
<div><label>E-mail:</label><br><input name="mail" class="field"/></div>
<div><label>Вопрос: <span>*</span></label><br><textarea name="question" class="field extralong"></textarea></div>
<?if(defined('IMG_SECURITY')){?>
<div><label>Введите код:<span>*</span></label><br><?$this->capture("faq")?></div>
<?}?>
<input type="submit" class="button long" name="send" value="Задать вопрос">
</div>

</form>
<div class="success"><?=$this->getText('msg_faq_success')?></div>
<?}?>
<script type="text/javascript" src="/modules/faq/faq.js"></script>