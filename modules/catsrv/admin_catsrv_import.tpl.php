<form id="import" method="POST" action="?act=imp">
<strong>Изменение цены</strong>
<div>
PRICE = <input name="price_expr" value="<?=$price_expr?>"> - стоимость товара
	<br>
	где <strong>PRICE</strong> - текущая стоимость товара
</div>
<div>
PRICE = <input name="price_expr_act" value="<?=$price_expr_act?>"> - стоимость товара ПО АКЦИИ
	<br>
	где <strong>PRICE</strong> - текущая стоимость товара
</div>
<button type="submit">Импорт</button>
</form>
<form id="import_img" method="POST" action="?act=impImg">
<button type="submit">Импорт картинок</button>
</form>
<form id="empty_log" method="POST" action="?act=emptyLog">
<button type="submit">Очистить лог</button>
</form>
<?if($log){?>
<strong>Лог</strong>
<table class="grid">
<?foreach ($log as $row){?>
<tr>
<td>
<?=$row?>
</td>
</tr>
<?}?>
</table>
<?}?>
<script type="text/javascript" src="/modules/catsrv/catsrv_import.js"></script>