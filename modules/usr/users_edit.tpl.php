<form method="post" enctype="multipart/form-data">
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
			<tr><th>������� : </th><td><input class="input-text" name="last_name" value="<?=$last_name?>"/></td> </tr>
			<tr><th>��� : </th><td><input class="input-text" name="first_name" value="<?=$first_name?>"/></td> </tr>
			<tr><th>�������� : </th><td><input class="input-text" name="middle_name" value="<?=$middle_name?>"/></td> </tr>
			
			<tr><th>����������� : </th><td><input class="input-text" name="company" value="<?=$company?>"/></td> </tr>
			<tr><th>��� : </th><td><input class="input-text" name="inn" value="<?=@$inn?>"/></td> </tr>
			
			<?/*tr><th>������ : </th><td>
			<input type="hidden" name="avat_path" value=""/>
			<img id="img-avat" name="avat" src="<?=$avat?$avat:'/img/admin/no_image.png'?>"/>
			
			<input class="button" type="button" name="clear" value="������"/><br />
			
			</td> </tr*/?>
			
			<tr><th>����� : </th><td><input class="input-text" name="city" value="<?=$city?>"/></td> </tr>
			<tr><th>����� : </th><td><input class="input-text" name="address" value="<?=$address?>"/></td> </tr>
			<?/*tr><th>����� : </th><td><input class="input-text" name="street" value="<?=$street?>"/></td> </tr>
			<tr><th>��� : </th><td><input class="input-text" name="house" value="<?=$house?>"/></td> </tr>
			<tr><th>��/�� : </th><td><input class="input-text" name="flat" value="<?=$flat?>"/></td> </tr>
			<tr><th>������� : </th><td><input class="input-text" name="porch" value="<?=$porch?>"/></td> </tr>
			<tr><th>���� : </th><td><input class="input-text" name="floor" value="<?=$floor?>"/></td> </tr*/?>
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
			
			
			
			<?if(in_array($type,array('partner'))){ /*!empty($ank) */{?>
				
				<tr><th>��� ��������� ������ * : </th><td><input name="ank[director_name]" value="<?=htmlspecialchars(@$ank['director_name'])?>" class="input-text"></td> </tr>
				<tr><th>��� ��������������  * : </th><td><input name="ank[admin_name]" value="<?=htmlspecialchars(@$ank['admin_name'])?>" class="input-text"></td> </tr>
				<tr><th>��� ��������, �������������� �� �����, ����������, ������, ������������ ����, �.�. * : </th><td><input name="ank[resp_name]" value="<?=htmlspecialchars(@$ank['resp_name'])?>" class="input-text"></td> </tr>
				<tr><th>���������� (���.) ������� �������������� * : </th><td><input name="ank[resp_phone]" value="<?=htmlspecialchars(@$ank['resp_phone'])?>" class="input-text"></td> </tr>
				
				<tr><th>��� ����� ����������� ����� * : </th><td><input name="ank[mail_check_period]" value="<?=htmlspecialchars(@$ank['mail_check_period'])?>" class="input-text"></td> </tr>
				<tr><th>�/� ��� ������ �� ������ * : </th><td><textarea name="ank[account]" class="field"><?=htmlspecialchars(@$ank['account'])?></textarea></td> </tr>
				<tr><th><input type="checkbox" name="ank[poss_add_deliv]" value="1" <?if(@$ank['poss_add_deliv']){?>checked<?}?> ></th><td>����������� �������� � ������� ���. �������� (������� �����������, ������� ������, ������ �������, ����, ������� � �����, ������� � ��������)</td> </tr>
				<tr><th><input type="checkbox" name="ank[poss_photo]" value="1" <?if(@$ank['poss_photo']){?>checked<?}?> ></th><td>����������� ������� �������� ���� ���������� � ������ �������� ������ � ��������� ��� �� ����������� �����</td> </tr>
				
				
				<tr><th>��������� �������� � �������� ������ (�� - �� ���.) : </th><td><input name="ank[delivery_price]" value="<?=htmlspecialchars(@$ank['delivery_price'])?>" class="input-text"></td> </tr>
				<tr><th>����������� ���������� ������, � ������� �������� �����������.  ��������, ��������� (�� ������ 800 ���.) : </th><td><textarea name="ank[delivery_settlements]" class="field"><?=htmlspecialchars(@$ank['delivery_settlements'])?></textarea></td> </tr>
				<tr><th>�������� ����� (���. ������) : </th><td><textarea name="ank[post_address]" class="field"><?=htmlspecialchars(@$ank['post_address'])?></textarea></td> </tr>
				<tr><th>��� ����� �������� ��������� �����, ���������� �����\��������)(���� ����), �� �������� � ���������� ����������  : </th><td><textarea name="ank[ext_info]" class="field"><?=htmlspecialchars(@$ank['ext_info'])?></textarea></td> </tr>
				<tr><th>���� ������ � �������������� � ����������� �������� �������� ������ (���. ��������)  : </th><td><textarea name="ank[experience]" class="field"><?=htmlspecialchars(@$ank['experience'])?></textarea></td> </tr>
				
				
				<tr>
				<td>
				������� ������ � �� ���� (� ������������� ������)
				</td>
				
				<td>
				<table class="grid" style="width:auto">
				<tr>
				
				<?$i=0;
				foreach ($ank_flowers as $nme=>$desc) {
					if(($i++%2)==0){?></tr><tr><?}
					
					?>
					<td style="text-align:right"><?=$desc?> <input style="width:50px" name="ank[flowers][<?=$nme?>]" value="<?=@$ank['flowers'][$nme]?>"> </td>
				<?}?>
				
				</tr>
				
				</table>
				
				
				</td>
				</tr>
				
				
				
				<tr><th>���������� ��������������  : </th><td><textarea name="ank[comment]" class="field"><?=htmlspecialchars(@$ank['comment'])?></textarea></td> </tr>

			
			<?}}?>
			
			
			
			</table>
			<br/>
			<?if($u_id==0){?>
				<input type="submit" class="button save" name="save" value="��������"/>
			<?}else{?>
				<input type="submit" class="button save" name="save" value="���������">
			<?}?>
			<input type="submit" class="button save" name="activate" value="������������">
			<input name="close" class="button" type="submit" value="�������"/>
		
		</form>
		<script type="text/javascript" src="/js/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="/modules/usr/users.js"></script>
		