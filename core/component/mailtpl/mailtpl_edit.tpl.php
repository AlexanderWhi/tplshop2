<form method="POST">
<input type="hidden" name="id" value="<?=$id?>"/>
			<h1>��������� ������� ������</h1>
			<table class="form">
			<tr>
			<th>�������� : </th>
			<td><input class="input-text" name="name" value="<?=$name?>"/></td>
			</tr>
			<tr>
			<tr>
			<th>���� : </th>
			<td><input class="input-text" name="theme" value="<?=$theme?>"/></td>
			</tr>
			<tr>
			<td colspan="2">
			<textarea class="tiny" name="body" style="width:100%;height:300px"><?=$body?></textarea>
			</td>
			</tr>
			<tr>
			<th>�������� ����������<br> (����������)</th>
			<td>
			<textarea  name="description" style="height:150px"  class="input-text"><?=$description?></textarea>
			</td>
			</tr>
			</table>
			<br/>

			<input type="submit" class="button save" name="save" value="���������"/>
			<input name="close" class="button" type="submit" value="�������"/>
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/core/component/mailtpl/mailtpl_edit.js"></script>