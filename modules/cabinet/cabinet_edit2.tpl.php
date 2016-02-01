<div id="tabs">
<?include('menu.tpl.php')?>
<div id="t1" class="tabs">

<form class="common common_block" id="cabinet-edit" method="POST" action="?act=save">
			<label class="i">Логин : *</label><strong><?=$login?></strong>
			<br/><br/>
		
			<label class="i">ФИО: *</label><input name="name" class="field" value="<?=$name?>"/><br/>
			<span id="error-name" class="error"></span>
			
			<label class="i">Адрес: *</label><input name="address" class="field" value="<?=$address?>"/>
			
			<?/*input name="street" value="<?=$street?>" class="field" title="Улица" style="width:150px"/>
			<input name="house" value="<?=$house?>" class="field" title="Дом" style="width:50px"/>
			<input name="flat" value="<?=$flat?>" class="field" title="кв/оф" style="width:50px"/>
			<input name="porch" value="<?=$porch?>" class="field" title="подъезд" style="width:100px"/>
			<input name="floor" value="<?=$floor?>" class="field" title="этаж" style="width:50px"/*/?>
			
			<span id="error-address" class="error"></span><br/>
				
			<label class="i">Телефон: *</label><input name="phone" class="field" value="<?=$phone?>"/>
			<span id="error-phone" class="error"></span><br/>
					
			<label class="i">Электронная почта: *</label><input name="mail" class="field" value="<?=$mail?>"/>
			<span id="error-mail" class="error"></span><br/><br/>
			
			
			
			<input type="submit" value="Сохранить" name="send" class="button long"/>	
		
</form>
<div id="success" style="display:none">
		Данные приняты
</div>

</div>

</div>



<script type="text/javascript" src="/modules/cabinet/cabinet_edit.js"></script>