<form id="content-form" method="post" action="?act=save">
<input type="hidden" name="c_id" value="<?=$c_id?>"/>
	<table class="form">
	<tr><th>Название : </th><td><input class="input-text" type="text" name="c_name" value="<?=$c_name?>"/></td> </tr>
	<tr>
		<td colspan="2">
			<textarea class="tiny" name="c_text"  style="width:100%;height:350px"><?=$c_text;?></textarea>
		</td>
	</tr>
	</table>
	<br/>
	<input class="button" type="submit" value="Сохранить"/> 
	<input class="button" name="close" type="button" value="Закрыть"/>
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/core/component/content/content_edit.js"></script>