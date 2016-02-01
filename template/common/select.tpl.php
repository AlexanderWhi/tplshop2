<?$n=0;?>
<select name="<?=$name?>" >
<?foreach($list as $key=>$desc){?>
<option class="<?=($n++ % 2)?'cl1':''?>" value="<?=$key?>" <?=(string)$key==$value?'selected':''?>><?=$desc?></option>
<?}?>
</select>