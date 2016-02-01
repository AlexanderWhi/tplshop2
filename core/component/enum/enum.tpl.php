<form id="enum-form" class="admin-form" method="POST" action="?act=save">
<input type="hidden" name="autoval" value="<?=$autoval?"true":"false"?>">
<input style="<?if(!in_array('name',$mode) || $hidename){?>display:none<?}?>" name="field_name" value="<?=$field_name?>">

<table>
<thead>
<tr>
<th style="<?if(!in_array('value',$mode) || $autoval){?>display:none<?}?>">Значение</th>
<th style="<?if(!in_array('pos',$mode)){?>display:none<?}?>">Позиция</th>
<th style="<?if(!in_array('desc',$mode)){?>display:none<?}?>">Описание</th>
</tr>
</thead>
<tbody>
<?while ($rs->next()) {?>
<tr>
<td style="<?if(!in_array('value',$mode) || $autoval){?>display:none<?}?>"><input style="width:200px" name="field_value[]" value="<?=$rs->get('field_value')?>"></td>
<td style="<?if(!in_array('pos',$mode)){?>display:none<?}?>"><input style="width:30px" name="position[]" value="<?=$rs->get('position')?>"></td>
<td style="<?if(!in_array('desc',$mode)){?>display:none<?}?>"><input style="width:400px" name="value_desc[]" value="<?=$rs->get('value_desc')?>"></td>
<td style="<?if(!in_array('add',$mode)){?>display:none<?}?>"><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
<?}?>
</tbody>
<tfoot style="display:none">
<tr>
<td style="<?if(!in_array('value',$mode) || $autoval){?>display:none<?}?>"><input style="width:200px" name="field_value_"></td>
<td style="<?if(!in_array('pos',$mode)){?>display:none<?}?>"><input style="width:30px" name="position_"></td>
<td style="<?if(!in_array('desc',$mode)){?>display:none<?}?>"><input style="width:400px" name="value_desc_"></td>
<td style="<?if(!in_array('add',$mode)){?>display:none<?}?>"><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td>
</tr>
</tfoot>
</table>
<?if(in_array('add',$mode)){?>
<a href="#" class="add"><img src="/img/pic/add_16.gif"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?}?>

<button type="submit">Сохранить</button><button type="button" name="close">Закрыть</button>
</form>
<script type="text/javascript" src="/core/component/enum/enum.js"></script>