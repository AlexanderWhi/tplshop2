<form id="enum-form" class="admin-form" method="POST" action="?act=saveItem">
<table class="form">
<tr><th>��������</th><td><input style="width:200px" name="field_name" value="<?=$field_name?>" readonly></td></tr>
<tr><th>��������</th><td><input style="width:200px" name="field_value" value="<?=$field_value?>" readonly></td>
<tr><th>�������</th><td><input style="width:20px" name="position" value="<?=$position?>"></td></tr>
<tr><th>��������</th><td><input style="width:400px" name="value_desc" value="<?=$value_desc?>"></td></tr>

</table>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">���������</button>
</form>
<script type="text/javascript" src="/core/component/enum/enum.js"></script>