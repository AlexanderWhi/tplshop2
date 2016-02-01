<div id="tabs">
<?include('modules/cabinet/menu.tpl.php')?>
<div id="t1" class="tabs">
<form class="common" id="shopnote-form" action="?act=MakeBasket" method="POST">

<div class="comment">
Создайте списки необходимых продуктов на неделю или к празднику. <br>
Списки продуктов помогут не забыть купить самое необходимое.
</div>

<table>
<td>
<h3>Все списки</h3>
<div class="list">
<div class="list_content">
<?include('list.tpl.php')?>
</div>

<input name="addname" class="field sh1 inptitle" title="Новый список">

<button class="button add" name="add"></button>
</div>

</td>
<td>
<h3>Создать список продуктов</h3>
<label>Название списка</label><br>
<input name="name" class="field w480">
<div class="list_result"></div>

</td>
<td>
<h3>Добавить товар</h3>
<label>Введите наименование товара</label><br>
<input name="search" class="field w340">
<div class="search_result"></div>

</td>



</table>


</form>



<a href="?act=exit" class="exit">Выйти из личного кабинета</a>
</div>
</div>
<script type="text/javascript" src="/modules/shopnote/shopnote.js"></script>