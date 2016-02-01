<?
global $n;$n=0;
function displayCatalog($catalog,$deep,$extraid){
	global $n;
	?>
	<?foreach($catalog as $item){?>
		<option style="<?=($n++ % 2)?'background-color:#EEEEEE':''?>" value="<?=$item['id']?>" 
		<?=$item['id']==$extraid?'selected="selected"':''?>>
		<?=str_repeat('&nbsp;&nbsp;&nbsp;|&nbsp;--&nbsp;',$deep)?>
		<?=$item['name']?>
		</option>
		<?if($item['children']){displayCatalog($item['children'],$deep+1,$extraid);	}?>
	<?}?>	
<?}?>

<form id="catalog" method="POST" action="?act=catalogSave" enctype="multipart/form-data" target="fr">
<iframe name="fr" style="display:none"></iframe>
	<input type="hidden" name="MAX_FILE_SIZE" value="<?=$this->cfg('UPLOAD_MAX_FILESIZE')?>">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="parentid" value="<?=$parentid?>">

	<table class="form">
			<tr><th style="width:200px">Название : </th><td><input class="input-text"  name="name" value="<?=$name?>"></td> </tr>
			<tr><th>Описание : </th><td><textarea class="tiny" name="description"><?=$description?></textarea></td> </tr>
			<tr><th>Изображение : </th>
			<td>
				
				<?if($this->isSU()){?>
				<input  name="img" value="<?=$img?>" class="input-text"><br>
				<?}else{?>
				<input type="hidden" name="img" value="<?=$img?>">
				<?}?>
				<img id="img-file" src="<?=$img?$img:'/img/admin/no_image.png'?>"><br>
				<input type="file" name="upload"/> <input type="checkbox" name="clear" value="1">Очистить
			</td>
			</tr>
			
			<?/*tr><th>Дополнительно : 
			<br />
			<small>предложить товары из данной категории</small>
			</th><td>
			<select class="input" name="extraid">
				<option style="<?=($n++ % 2)?'background-color:#EEEEEE':''?>" value="0">[раздел не указан]</option>
				<?displayCatalog($catalog,0,$extraid);?>
			</select>
			<input type="checkbox" name="all" value="all" id="all"/>
			<label for="all">Применить ко всем вложенным каталогам</label>
			</td></tr*/?>
			
	</table>
	<hr/>
	<input type="submit" class="button" name="save" value="Сохранить">
	<input name="close" class="button" type="submit" value="Закрыть">		
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/catalog/catalog_edit.js"></script>