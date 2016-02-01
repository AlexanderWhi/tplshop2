<form id="zone-form" class="admin-form" method="POST" action="?act=saveZone">

<table>
<thead>
<tr>
<th>ID</th>
<th>Название</th>
<th>Цвет</th>
<th>Стоимость (через запятую <сумма>:<доставка>)</th>
</tr>
</thead>
<tbody>
<?foreach ($rs as $row)  {?>
<tr>
<td><input style="width:30px" name="id[]" value="<?=$row['id']?>"></td>
<td><input style="width:200px" name="name[]" value="<?=$row['name']?>"></td>
<td><input style="width:60px" name="color[]" value="<?=$row['color']?>"></td>
<td><input style="width:400px" name="smm_cfg[]" value="<?=$row['smm_cfg']?>"></td>

<td><a href="#" class="edit"><img src="/img/pic/edit_16.gif"></a></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
<?}?>
</tbody>
<tfoot style="display:none">
<tr>
<td><input style="width:30px" name="id_"></td>
<td><input style="width:200px" name="name_"></td>
<td><input style="width:60px" name="color_"></td>
<td><input style="width:400px" name="smm_cfg_"></td>

<td><a href="#" class="edit"><img src="/img/pic/edit_16.gif"></a></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
</tfoot>
</table>
<a href="#" class="add"><img src="/img/pic/add_16.gif"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<button type="submit">Сохранить</button><button type="button" name="close">Закрыть</button>
</form>
<script type="text/javascript" src="/modules/ymap/admin_delivery_zone.js"></script>