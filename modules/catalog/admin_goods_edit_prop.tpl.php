<!--<input type="checkbox" name="show_all_props" id="show_all_props" value="1" <?if(!empty($show_all_props)){?>checked<?}?> ><label for="show_all_props">Показать все свойства</label>-->
<table id="prop" class="grid" style="width:auto">
<thead>
<tr>
<?if(!empty($popup_mode)){?><th></th><?}?>
<th>Название</th>
<th>Значение</th>
</tr>

</thead>
<tbody>
<?
$i=0;
$pgrp_desc='';

foreach($propList as $k=>$row){?>

<?if($row['pgrp_desc']!=$pgrp_desc){$pgrp_desc=$row['pgrp_desc']?>
<tr>
<?if(!empty($popup_mode)){?><th></th><?}?>
<th colspan="2">
<?=$row['pgrp_desc']?>
</th>
</tr>
<?}?>
<tr>

<?if(!empty($popup_mode)){?><th><input type="checkbox" name="remove[]" value="<?=$k?>"></th><?}?>

<td><?=$row['name']?></td>
<td>
<?if(in_array($row['type'],array(1))){?>
<input type="checkbox" name="pvalue[<?=$k?>]" value="1" <?if($row['value']){?>checked<?}?>>

<?}elseif(in_array($row['type'],array(4))){?>
<select name="pvalue[<?=$k?>]">
<option value="">--не выбрано</option>
<?foreach ($this->enum('sh_prop_list_'.$k) as $key=>$val) {?>
	<option value="<?=$val?>" <?if($val==$row['value']){?>selected<?}?>><?=$val?></option>
<?}?>
</select>
<?}else{?>
<input style="width:250px" name="pvalue[<?=$k?>]" value="<?=$row['value']?>" class="pvalue">
<?}?>
</td>
</tr>
<?$i++;}?>
</tbody>
</table>