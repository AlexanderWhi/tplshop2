<div id="tabs">
<?include('modules/cabinet/menu.tpl.php')?>
<div id="t1" class="tabs">
<form class="common" id="shopnote-form" action="?act=MakeBasket" method="POST">

<div class="comment">
�������� ������ ����������� ��������� �� ������ ��� � ���������. <br>
������ ��������� ������� �� ������ ������ ����� �����������.
</div>

<table>
<td>
<h3>��� ������</h3>
<div class="list">
<div class="list_content">
<?include('list.tpl.php')?>
</div>

<input name="addname" class="field sh1 inptitle" title="����� ������">

<button class="button add" name="add"></button>
</div>

</td>
<td>
<h3>������� ������ ���������</h3>
<label>�������� ������</label><br>
<input name="name" class="field w480">
<div class="list_result"></div>

</td>
<td>
<h3>�������� �����</h3>
<label>������� ������������ ������</label><br>
<input name="search" class="field w340">
<div class="search_result"></div>

</td>



</table>


</form>



<a href="?act=exit" class="exit">����� �� ������� ��������</a>
</div>
</div>
<script type="text/javascript" src="/modules/shopnote/shopnote.js"></script>