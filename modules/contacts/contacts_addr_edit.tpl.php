<div>
<form method="POST" action="?act=saveAddr">
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">


<th>Название</th>
<td>
<input name="name" value="<?=$name?>" class="input-text"/>
</td>
</tr>

<tr>
<th>Город</th>
<td>
<input name="city" value="<?=$city?>" class="input-text"/>
</td>
</tr>




<tr>
<th>Адрес</th>
<td>
<input name="addr" value="<?=$addr?>" class="input-text"/>
</td>
</tr>

<tr>
<th>Код</th>
<td>
<input name="code" value="<?=$code?>" class="input-text"/>
</td>
</tr>

<tr>
<th>Телефон</th>
<td>
<input name="phone" value="<?=$phone?>" class="input-text"/>
</td>
</tr>
<tr>
<th>Факс</th>
<td>
<input name="fax" value="<?=$fax?>" class="input-text"/>
</td>
</tr>

<tr>
<th>E-mail</th>
<td>
<input name="mail" value="<?=$mail?>" class="input-text"/>
</td>
</tr>

<tr>
<th>Точка на карте яндекс</th>
<td>
<input name="point" value="<?=$point?>" class="input-text">
</td>
</tr>
<tr>
<th>Описание</th>
<td>
	<textarea name="description"  class="tiny" style="width:100%;height:300px"><?=$description?></textarea>
</td>
</tr>
<tr>
</table>
<hr>
<input type="submit" name="save" class="button save" value="Сохранить"/> 
<input name="close" type="submit" class="button" value="Закрыть"/>	
</form>
</div>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/contacts/admin_contacts_edit.js"></script>