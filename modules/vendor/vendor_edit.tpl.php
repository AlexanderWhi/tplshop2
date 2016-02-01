<form id="user-edit" method="POST" enctype="multipart/form-data" target="fr" action="?act=save">
<iframe name="fr" style="display:none"></iframe>
		<input type="hidden" name="u_id" value="<?=$u_id?>"/>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?=$this->cfg('UPLOAD_MAX_FILESIZE')?>"/>
			<h1>Пользователь</h1>
			<table class="form">
			<tr><th>Логин: </th><td><input class="input-text" name="login" value="<?=$login?>"/></td> </tr>
			<tr><th> </th><td><a href="/admin/catalog/goods/vendor/<?=$u_id?>/">Предложение</a></td> </tr>

			
			
			<tr><th>Фио: </th><td><input class="input-text" name="name" value="<?=$name?>"/></td> </tr>
			
			<tr><th>Организация: </th><td><input class="input-text" name="company" value="<?=$company?>"/></td> </tr>
			
			<tr><th>Изображение: </th>
			<td>
				<img id="img-file" src="<?=$avat?scaleImg($avat,'w200'):'/img/no_image.png'?>"/><br/>
				<input type="file" name="upload"> <input type="checkbox" name="clear"  value="1"><label>Удалить</label><br>
				<select name="img_format">
				<?foreach ($this->enum('img_format') as $k=>$d) {?>
					<option value="<?=$k?>" <?if($k==$img_format){?>selected<?}?>><?=$d?></option>
				<?}?>
				</select>
			</td>
			</tr>
			
			<tr><th>Город: </th><td><input class="input-text" name="city" value="<?=$city?>"/></td> </tr>
			<tr><th>Адрес: </th><td><input class="input-text" name="address" value="<?=$address?>"/></td> </tr>
			<tr><th>Телефон: </th><td><input class="input-text" name="phone" value="<?=$phone?>"/></td> </tr>
			<tr><th>Почта: </th><td><input class="input-text" name="mail" value="<?=$mail?>"/></td> </tr>
			<tr><th>Баланс: </th><td><input class="input-text" name="balance" value="<?=$balance?>"/> </td> </tr>
			<?/*tr><th>Бонус : </th><td><input class="input-text" name="bonus" value="<?=$bonus?>"/> </td> </tr*/?>
			<tr><th>Скидка%: </th><td><input class="input-text" name="discount" value="<?=$discount?>"/> </td> </tr>
			<tr><th>Информация: </th><td><textarea class="input-text" name="info"><?=$info?></textarea> </td> </tr>
			<tr><th>Комментарий: </th><td><textarea class="input-text" name="comment"><?=$comment?></textarea> </td> </tr>
			<tr><td colspan="2">Описание: <textarea class="tiny" name="html" style="width:100%"><?=$html?></textarea> </td> </tr>
			
			<tr><th>Загрузить изображения: </th><td>
			<div class="img_list">
			<div class="item" style="display:none">
					<input name="pos_">
					<input type="hidden" name="images_" value="">
					<a href="#" class="del"></a>
					<img src="/img/no_image.png">
			</div>
			</div>
			<input name="images_upload[]" type="file" multiple>
			</td> </tr>
			<tr>
			<th>Галлерея</th>
			<td >
			<div class="checkbox_list"></div>
			<?foreach ($gallery_list as $row) {?>
				<div class="item">
				<input <?if(in_array($row['id'],$gallery)){?>checked<?}?> type="checkbox" name="gallery[]" value="<?=$row['id']?>"><label><?=$row['name']?></label>
				</div>
			<?}?>
			
			
			</td>
			
			</tr>
			<tr><th>Комментарий администратора: </th><td><textarea class="input-text" name="adm_comment"><?=$adm_comment?></textarea> </td> </tr>
			</table>
			<br/>
			<input type="submit" class="button save" name="save" value="Сохранить"/>
			
			<input name="close" class="button" type="submit" value="Закрыть"/>
		
		</form>
		
		
<script type="text/javascript">
var IMAGES=<?=printJSON($data['images'])?>;
</script>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/vendor/vendor_edit.js"></script>
		