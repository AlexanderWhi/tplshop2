<form id="user-edit" method="POST" enctype="multipart/form-data" target="fr" action="?act=save">
<iframe name="fr" style="display:none"></iframe>
		<input type="hidden" name="u_id" value="<?=$u_id?>"/>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?=$this->cfg('UPLOAD_MAX_FILESIZE')?>"/>
			<h1>������������</h1>
			<table class="form">
			<tr><th>����� : </th><td><input class="input-text" name="login" value="<?=$login?>"/></td> </tr>
			<?/*if(!$this->id){*/?>
			<tr><th>������ : </th><td><input type="password" class="input-text" name="password"/></td> </tr>
			<tr><th>������ : </th><td>
			<?foreach($this->status as $k=>$desc){?>
			<input type="radio" name="status" value="<?=$k?>" id="status<?=$k?>" <?=$status==$k?'checked':''?>><label for="status<?=$k?>"><?=$desc?></label>
			<?}?>
			</td> </tr>
			<?/*}*/?>
			
			
			<tr><th>��� : </th><td><input class="input-text" name="name" value="<?=$name?>"/></td> </tr>
			
			<tr><th>����������� : </th><td><input class="input-text" name="company" value="<?=$company?>"/></td> </tr>
			
			<tr><th>����������� : </th>
			<td>
				<img id="img-file" src="<?=$avat?scaleImg($avat,'w200'):'/img/no_image.png'?>"/><br/>
				<input type="file" name="upload"> <input type="checkbox" name="clear"  value="1"><label>�������</label>
			</td>
			</tr>
			
			<tr><th>����� : </th><td><input class="input-text" name="city" value="<?=$city?>"/></td> </tr>
			<tr><th>����� : </th><td><input class="input-text" name="address" value="<?=$address?>"/></td> </tr>
			<tr><th>������� : </th><td><input class="input-text" name="phone" value="<?=$phone?>"/></td> </tr>
			<tr><th>����� : </th><td><input class="input-text" name="mail" value="<?=$mail?>"/></td> </tr>
			<tr><th>������ : </th><td><input class="input-text" name="balance" value="<?=$balance?>"/> </td> </tr>
			<?/*tr><th>����� : </th><td><input class="input-text" name="bonus" value="<?=$bonus?>"/> </td> </tr*/?>
			<tr><th>������% : </th><td><input class="input-text" name="discount" value="<?=$discount?>"/> </td> </tr>
			
			<tr><th>������ : </th>
				<td>
				<select name="type">
			<?foreach($groups as $group=>$desc){?>
				<option value="<?=$group?>" <?=$group==$type?'selected':''?>><?=$desc?></option>
			<?}?>
			</select>
				</td>
			</tr>		
			</table>
			<br/>
			<?if($u_id==0){?>
				<input type="submit" class="button save" name="save" value="��������"/>
			<?}else{?>
				<input type="submit" class="button save" name="save" value="���������"/>
			<?}?>
			<input name="close" class="button" type="submit" value="�������"/>
		
		</form>
		<script type="text/javascript" src="/core/component/users/users.js"></script>
		