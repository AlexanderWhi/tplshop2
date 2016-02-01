<form>
<input name="date_from" class="date" value="<?=$date_from?>">
<input name="date_to" class="date" value="<?=$date_to?>"><br />

<button type="button" name="visitPerDay">Статистика посещений в день</button>
<button type="button" name="visit">Статистика посещений страниц</button>
<button type="button" name="referer">Откуда приходят</button>
<button type="button" name="browsers">Обозреватели</button>
<div id="load_data"></div>
</form>
<script type="text/javascript" src="/core/component/statistic/statistic.js"></script>