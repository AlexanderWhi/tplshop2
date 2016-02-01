<form id="collect" class="common">
<div class="menu">
<a href="/catalog/collect/simple/">Для начинающих пользователей</a> <span>Для профессионалов</span>
</div>
<?=$this->getText($this->uri)?>
<input type="hidden" name="type" value="custom_collect">
<table class="grid" style="width:100%">
<tr>
<th>Наименование</th>
<th>Кол-во</th>
<th>Стоимость</th>
</tr>
<?foreach ($collect as $id=>$itm) {?>
<tr>
<td>
<span class="select">
<select name="goods[<?=$id?>]" >
<option value=""><?=$itm['name']?></option>
<?foreach ($itm['item'] as $gid=>$goods) {?>
	<option value="<?=$goods['id']?>" price="<?=$goods['price']?>"><?=$goods['name']?></option>
<?}?>

</select>
</span>

</td>
<td>
<input name="count[<?=$id?>]" class="count" value="1">
</td>
<td class="price">

</td>
</tr>
<?}?>
<tfoot>
<tr>
<td></td>
<td></td>
<td class="total">0</td>
</tr>

</tfoot>



</table>
<div class="error" id="error-collect"></div>

<div class="block">
<label class="i">Фио</label><input class="field" name="name"><br>
<div class="error" id="error-name"></div>
<label class="i">Email</label><input class="field" name="mail"><br>
<div class="error" id="error-mail"></div>
<label class="i">Телефон</label><input class="field" name="phone"><br>
<div class="error" id="error-phone"></div>
</div>

<button name="send" class="button long">Отправить</button> 
</form>
<div class="success">
<?=$this->getText('msg_collect')?>

</div>

<script type="text/javascript" src="/modules/<?=$this->mod_module_name?>/collect.js"></script>