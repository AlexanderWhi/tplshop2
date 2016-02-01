
<!--<a href="/admin/content/edit/?name=delivery_info">Пояснение к доставке</a><br>
<a href="/admin/content/edit/?name=delivery_info_pickup">Пояснение к доставке при самовывозе</a><br>-->

<form id="deliv-form" class="admin-form" method="POST" action="?act=saveDeliv">
<table class="grid">
<thead>
<tr>
<th>Стоимость покупки свыше</th>
<th>стоимость доставки</th>
<th></th>
</tr>
</thead>
<tbody>
<?while ($rs->next()) {?>
<tr>
<td><input style="width:50px" name="summ[]" value="<?=$rs->get('summ')?>"></td>
<td><input style="width:50px" name="price[]" value="<?=$rs->get('price')?>"></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
<?}?>
</tbody>
<tfoot style="display:none">
<tr>
<td><input style="width:50px" name="summ_"></td>
<td><input style="width:50px" name="price_"></td>
<td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
</tfoot>
</table>
<a href="#" class="add"><img src="/img/pic/add_16.gif"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit">Сохранить</button>
</form>
<script type="text/javascript" src="/modules/shop/admin_deliv.js"></script>