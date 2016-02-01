
	<form id="upload_file" method="POST" enctype="multipart/form-data" action="?act=uploadFile&_cb=_uploadFile" target="fr">
		<input type="hidden" name="file_name" value="<?=$file_name?>"/>
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
		
		<?if($title){?>
		<h3><?=$title?></h3>
		<?}?>
		<table class="form">
		<tr><th>Залить файл : </th>
			<td><input id="userfile" class="input-text multi" type="file" name="userfile">
			</td>
			<td>
			<input type="submit" name="upload" class="button" value="Загрузить">
			</td>
			<td>
			<input type="button" name="close" class="button" value="Закрыть">
			</td>
		</tr>		
		</table>
		<iframe name="fr" style="display:none"></iframe>
	</form>

<script type="text/javascript" src="/core/component/files/upload_file.js"></script>



