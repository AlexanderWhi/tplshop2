<div id="tab">
	<ul style="height:28px">
		<li><a href="#tab1">топ 20</a></li>
		<li><a href="#tab2">Продажи</a></li>
		<li><a href="#tab3">Продажи товаров</a></li>
		<li><a href="#tab4">Продажи брендов</a></li>

	</ul>

<div id="tab1">
<?if($goods_views){?>
<h3>Просмотров</h3>
<table class="grid">
<tr><th></th><th>ID</th><th>Название</th><th>Просмотров</th></tr>
<?$c=0;foreach ($goods_views as $row) {?>
<tr>
<td><?=++$c?></td>
<td><?=$row['id']?></td>

<td><?=$row['name']?></td>
<td><?=$row['views']?></td>
</tr>
<?}?>
</table>
<?}?>

<?if($goods_orders){?>
<h3>Заказов</h3>
<table class="grid">
<tr><th></th><th>ID</th><th>Название</th><th>Заказано</th></tr>
<?$c=0;foreach ($goods_orders as $row) {?>
<tr>
<td><?=++$c?></td>
<td><?=$row['id']?></td>

<td><?=$row['name']?></td>
<td><?=$row['c']?></td>
</tr>
<?}?>
</table>
<?}?>
</div>


<div id="tab2">
<form action="?act=StatSale">

<input name="date_from" class="date" value="<?=dte($date_from)?>">
<input name="date_to" class="date" value="<?=dte($date_to)?>"> 

<input type="radio" name="group" value="month" checked><label>Месяцы</label>
<input type="radio" name="group" value="day"><label>Дни</label>
<button value="show" type="submit">Показать</button>

<div class="res"></div>

</form>

</div>

<div id="tab3">
<form action="?act=StatGoodsSale">

<input name="date_from" class="date" value="<?=dte($date_from)?>">
<input name="date_to" class="date" value="<?=dte($date_to)?>"> 

<button value="show" type="submit">Показать</button>

<div class="res"></div>

</form>
</div>

<div id="tab4">
<form action="?act=StatBrandSale">

<input name="date_from" class="date" value="<?=dte($date_from)?>">
<input name="date_to" class="date" value="<?=dte($date_to)?>"> 

<button value="show" type="submit">Показать</button>

<div class="res"></div>

</form>
</div>



</div>
<script type="text/javascript" src="/modules/shop/shop_statistic.js"></script>