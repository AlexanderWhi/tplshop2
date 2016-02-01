<form method="POST" id="partners-form" action="?act=save" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<tr>
<th style="width:150px">Имя</th>
<td>
<input name="name" value="<?=$name?>" class="input-text"/>
</td>
</tr>
<tr>
<th>Описание</th>
<td>
<textarea name="description" class="input-text"><?=$description?></textarea>
</td>
</tr>

<?/*tr>
<th>Телефон</th>
<td>
<input name="phone" value="<?=$phone?>" class="input-text"/>
</td>
</tr>

<tr>
<th>e-mail</th>
<td>
<input name="mail" value="<?=$mail?>" class="input-text"/>
</td>
</tr>

<tr>
<th>ICQ</th>
<td>
<input name="icq" value="<?=$icq?>" class="input-text"/>
</td>
</tr>

<tr>
<th>Доставка</th>
<td>
Стоимость доставки <input name="delivery" value="<?=$delivery?>"/><br />
Условие бесплатной доставки <input name="delivery_cond" value="<?=$delivery_cond?>"/><br />
Условие доставки заказа <input name="delivery_order_cond" value="<?=$delivery_order_cond?>"/>
</td>
</tr*/?>



<tr>
<th>Ссылка(URL)</th>
<td>
<input name="url" class="input-text" value="<?=$url?>">
</td>
</tr>
<tr>
<th>Изображение</th>
<td>
<table>
<td>
<input type="hidden" name="img" value="<?=$img?>"/>
<img id="img-file" src="<?=$img?$img:'/img/admin/no_image.png'?>"/><br/>
<input type="file" name="upload"/> <input class="button" type="button" name="clear" value="Очистить"/>
</td>
<?/*td>
<input type="hidden" name="img1" value="<?=$img1?>"/>
<img id="img1-file" src="<?=$img1?$img1:'/img/admin/no_image.png'?>"/><br/>
<input type="file" name="upload1"/> <input class="button" type="button" name="clear1" value="Очистить"/>
</td*/?>
</table>


</td>
</tr>

<tr>
<th>Статус</th>
<td>
<?foreach($status as $key=>$desc){?>
	<input type="radio" class="radio" name="state" id="<?=$key?>" value="<?=$key?>" <?=($state == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>

<tr>
<th>Приоритет</th>
<td>
<input name="sort" value="<?=$sort?>" class="input-text" style="width:20px"/>
</td>
</tr>
</table>
<hr/>
<input type="submit" name="save" class="button" value="Сохранить"/> 
<input name="close" type="submit" class="button" value="Закрыть"/>
</form>
<script type="text/javascript" src="/modules/partners/admin_partners_edit.js"></script>