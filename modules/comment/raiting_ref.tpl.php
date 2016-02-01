<form id="raiting-ref-form" class="admin-form" method="POST" action="?act=SaveRaitingRef">
<table>
<thead>
<tr>
<th>Id</th>
<th>Название</th>
<th>Порядок</th>
<th>от</th>
<th>до</th>
<th>ID каталога</th>
</tr>
</thead>
<tbody>
<?while ($rs->next()) {?>
<tr>
<td><input style="width:30px" name="id[]" value="<?=$rs->get('id')?>"></td>
<td><input style="width:200px" name="name[]" value="<?=$rs->get('name')?>"></td>
<td><input style="width:30px" name="pos[]" value="<?=$rs->get('pos')?>"></td>
<td><input style="width:30px" name="rfrom[]" value="<?=$rs->get('rfrom')?>"></td>
<td><input style="width:30px" name="rto[]" value="<?=$rs->get('rto')?>"></td>
<td><input style="width:30px" name="catid[]" value="<?=$rs->get('catid')?>"></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
<?}?>
</tbody>
<tfoot style="display:none">
<tr>
<td><input style="width:30px" name="id|" value="0"></td>
<td><input style="width:200px" name="name|"></td>
<td><input style="width:30px" name="pos|"></td>
<td><input style="width:30px" name="rfrom|"></td>
<td><input style="width:30px" name="rto|"></td>
<td><input style="width:30px" name="catid|"></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
</tfoot>
</table>
<a href="#" class="add"><img src="/img/pic/add_16.gif"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">Сохранить</button>
</form>
<script type="text/javascript" src="/modules/comment/raiting_ref.js"></script>