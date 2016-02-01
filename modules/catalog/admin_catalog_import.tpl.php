<form id="import" method="POST" action="?act=imp">
<strong>Изменение цены</strong>
<div>

PRICE = <input name="price_expr" value="<?=$price_expr?>">
	<br>
	где <strong>PRICE</strong> - текущая стоимость товара
</div>
<button type="submit">Импорт</button>
</form>
<a href="?act=export">Сформировать EXCEL</a>
<script type="text/javascript" src="/modules/catalog/catalog_import.js"></script>