<form id="import" method="POST" action="?act=imp">
<strong>��������� ����</strong>
<div>
PRICE = <input name="price_expr" value="<?=$price_expr?>"> - ��������� ������
	<br>
	��� <strong>PRICE</strong> - ������� ��������� ������
</div>
<div>
PRICE = <input name="price_expr_act" value="<?=$price_expr_act?>"> - ��������� ������ �� �����
	<br>
	��� <strong>PRICE</strong> - ������� ��������� ������
</div>
<button type="submit">������</button>
</form>
<form id="import_img" method="POST" action="?act=impImg">
<button type="submit">������ ��������</button>
</form>
<form id="empty_log" method="POST" action="?act=emptyLog">
<button type="submit">�������� ���</button>
</form>
<?if($log){?>
<strong>���</strong>
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