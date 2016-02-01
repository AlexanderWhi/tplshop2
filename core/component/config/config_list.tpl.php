<?foreach ($rs as $row) {
			?><tr>
			<td>
			<input name="item[]" value="<?=$row['name']?>" type="checkbox">
			</td>
			<td><?=$row['name']?></td>
			<td>
			<?if(isset($enum[$row['name']])){?>
			<?if(is_array($value_list=$enum[$row['name']])){
				?><select name="<?=strtolower($row['name'])?>"><?
				foreach ($value_list as $v=>$d) {
					?><option value="<?=htmlspecialchars($v)?>" <?if($row['value']==$v){?>selected<?}?>><?=$d?></option><?	
				}	
			}?>

			<?}else{?>
			<input class="input-text" name="<?=strtolower($row['name'])?>" value="<?=htmlspecialchars($row['value'])?>">
			<?}?>
			</td>
			<td><small><?=$row['description']?></small></td>
			
			<td>
			<a href="/admin/enum/CFG_<?=strtoupper($row['name'])?>">Списком</a>
			</td>
			<td>
			<a  href="javascript:remove('<?=$row['name']?>')">Удалить</a>
			</td>
			</tr><?
		}?>