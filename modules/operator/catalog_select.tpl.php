<?function displayCatalog($catalog,$selected,$deep=0,$n=0){
		foreach($catalog as $item){?>
			<option style="<?=($n++ % 2)?'background-color:#EEEEEE':''?>" value="<?=$item['id']?>" 
			<?=$item['id']==$selected?'selected="selected"':''?>>
			<?=str_repeat('&nbsp;&nbsp;&nbsp;|&nbsp;--&nbsp;',$deep)?>
			<?=$item['name']?>
			</option>
			<?if($item['children']){displayCatalog($item['children'],$selected,$deep+1,$n);	}?>
		<?}?>	
<?}
$n=0;
?>
<select class="input" name="catalog">
<option style="<?=(($n++ % 2)?'background-color:#EEEEEE':'')?>" value="0">[раздел не указан]</option>
<?displayCatalog($catalog,$selected,0,$n);?>
</select>