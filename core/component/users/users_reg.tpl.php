<form method="POST" action="?act=SendReg">
<table class="grid">
	<?foreach ($rs as $row){?>
		<tr>
			<td><?=$row['mail']?></a></td>
		</tr>	
	<?}?>
</table>
<label>������� ������ Email</label><br>
<textarea name="mails"></textarea><br>
<button name="send" class="button" type="submit">���������</button>
</form>