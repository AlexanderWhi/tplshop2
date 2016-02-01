<h4>Изменения</h4>
<form method="POST" action="?act=exp3">
<div>Время последнего изменения:<strong><?=$last_time?></strong></div>

<label>От</label>
<input name="date_from" value="<?=$date_from?>">
<label>До</label>
<input name="date_to" value="<?=$date_to?>">
<button type="submit">Сформировать отчёт</button><button name="clear_history" type="button">Очитить историю</button>
</form>
<hr>
<h4>Прайс для пенсионеров</h4>
<form method="POST" action="?act=exp1">
<label>Скидка</label>
<input name="percent" value="<?=$disc_pens?>" style="width:20px">%</br>
<input type="checkbox" name="sort_print" value="1" checked><label>Только отмеченные</label> </br>
<input type="checkbox" name="changed" value="1" checked><label>Только изменённые</label>
<label>От</label>
<input name="date_from" value="<?=$date_from?>">
<label>До</label>
<input name="date_to" value="<?=$date_to?>">

<button type="submit">Сформировать отчёт</button>
</form>
<hr>
<h4>Прайс по ценам</h4>
<form method="POST" action="?act=exp2">
<label>Скидка пенсионерам</label></br>
<input name="pers1" value="8"></br>
<label>Цена 2</label></br>
<input name="pers2" value="0"></br>
<label>Цена 3</label></br>
<input name="pers3" value="0"></br>
<label>Цена 4</label></br>
<input name="pers4" value="0"></br>
<label>Цена 5</label></br>
<input name="pers5" value="0"></br>

<button type="submit">Сформировать отчёт</button>
</form>
<hr>
<h4>Экспорт</h4>
<form method="POST" action="?act=exp">
<button type="submit">Экспорт</button>
</form>
<script type="text/javascript" src="/modules/catsrv/admin_catsrv_export.js"></script>
