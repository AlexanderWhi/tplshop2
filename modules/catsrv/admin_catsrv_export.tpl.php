<h4>���������</h4>
<form method="POST" action="?act=exp3">
<div>����� ���������� ���������:<strong><?=$last_time?></strong></div>

<label>��</label>
<input name="date_from" value="<?=$date_from?>">
<label>��</label>
<input name="date_to" value="<?=$date_to?>">
<button type="submit">������������ �����</button><button name="clear_history" type="button">������� �������</button>
</form>
<hr>
<h4>����� ��� �����������</h4>
<form method="POST" action="?act=exp1">
<label>������</label>
<input name="percent" value="<?=$disc_pens?>" style="width:20px">%</br>
<input type="checkbox" name="sort_print" value="1" checked><label>������ ����������</label> </br>
<input type="checkbox" name="changed" value="1" checked><label>������ ���������</label>
<label>��</label>
<input name="date_from" value="<?=$date_from?>">
<label>��</label>
<input name="date_to" value="<?=$date_to?>">

<button type="submit">������������ �����</button>
</form>
<hr>
<h4>����� �� �����</h4>
<form method="POST" action="?act=exp2">
<label>������ �����������</label></br>
<input name="pers1" value="8"></br>
<label>���� 2</label></br>
<input name="pers2" value="0"></br>
<label>���� 3</label></br>
<input name="pers3" value="0"></br>
<label>���� 4</label></br>
<input name="pers4" value="0"></br>
<label>���� 5</label></br>
<input name="pers5" value="0"></br>

<button type="submit">������������ �����</button>
</form>
<hr>
<h4>�������</h4>
<form method="POST" action="?act=exp">
<button type="submit">�������</button>
</form>
<script type="text/javascript" src="/modules/catsrv/admin_catsrv_export.js"></script>
