<div>

<form id="form" method="POST">
<table>
<tr>
<td><label>�����:</label></td>
<td><strong><?=$this->getUser('login')?></strong></td>
</tr>

<tr>
<td><label>������:</label></td>
<td><input type="password" name="password"/></td>
</tr>

<tr>
<td><label>�������������:</label></td>
<td><input type="password" name="cpassword"/></td>
</tr>
<tr>
<td></td>
<td><input name="change" type="submit" value="�������" class="button"/></td>
</tr>

</table>
</form>
</div>
<script type="text/javascript" src="/core/component/users/password.js">
		
</script>