<select name="page_size">
<?foreach ($list as $val){?>
<option value="<?=$val?>" <?=$current==$val?'selected':''?>>�������� �� <?=$val?></option>
<?}?>
</select>