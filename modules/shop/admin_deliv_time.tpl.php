<form id="deliv-time-form" class="admin-form" method="POST" action="?act=saveDelivTime">
<table>
<thead>
<tr>
<!--<th>Начиная с </th>-->
<th>заказ до</th>
<th>Коментарий </th>
</tr>
</thead>
<tbody>
<?while ($rs->next()) {?>
<tr>
<!--<td><input style="width:50px" name="time_from[]" value="<?=$rs->get('time_from')?>"></td>-->
<td><input style="width:50px" name="time_to[]" value="<?=$rs->get('time_to')?>"></td>
<td><input style="width:250px" name="comment[]" value="<?=$rs->get('comment')?>"></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
<?}?>
</tbody>
<tfoot style="display:none">
<tr>
<td><input style="width:50px" name="time_to_"></td>
<td><input style="width:250px" name="comment_"></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
</tfoot>
</table>
<a href="#" class="add"><img src="/img/pic/add_16.gif"></a>
<button type="submit">Сохранить</button>
</form>
<script type="text/javascript" src="/modules/shop/admin_deliv_time.js"></script>