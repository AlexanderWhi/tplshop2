<form method="POST" action="?act=save">
<?foreach ($rs as $name=>$item){?>
		<?if(!empty($item['ps']->disabled)){continue;}?>
		<input type="hidden" name="name[]" value="<?=$name?>" >
		<table class="form">
		
		<tr><td colspan="2">
		<input type="radio" name="default" value="<?=$name?>" id="default_<?=$name?>" <?if($name==$this->cfg('PAYSYSTEM')){?>checked<?}?>>
		
		<label for="default_<?=$name?>"><?=$name?></label></td></tr>		
		<?if($item['ps']->config){
			foreach ($item['ps']->config as $config){
				?><tr><th><?=$config?></th><td><input class="input-text" name="config[<?=$name?>][<?=$config?>]" value="<?=$item['ps']->$config?>"/></td></tr><?
			}
		}?>
		<tr><th style="width:200px">��������</th><td><input class="input-text" name="description[<?=$name?>]" value="<?=htmlspecialchars(@$item['description'])?>"/></td></tr>
		<tr><th>�����</th><td><textarea class="input-text" name="text[<?=$name?>]"><?=htmlspecialchars(@$item['text'])?></textarea></td></tr>
		
		</table>
		
		
		<hr/>
<?}?>
<input class="button save" type="submit" name="save" value="���������">
</form>	
<script type="text/javascript" src="/core/component/paysystem/paysystem.js"></script>