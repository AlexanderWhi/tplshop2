<a href="/admin/shop/delivTime/">Настройка времени доставки</a><br>
<a href="/admin/shop/deliv/">Настройка стоимости доставки</a><br>
<form class="admin-form" method="POST" action="?act=saveConfig">
<?while ($rs->next()) {?>
<label><?=$rs->get('description')?></label>	
<input name="<?=$rs->get('name')?>" value="<?=$rs->get('value')?>">
<?}?>
<button type="submit">Сохранить</button>
</form>
<script type="text/javascript" src="/modules/shop/admin_config.js"></script>