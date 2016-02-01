<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="u_id" value="<?=$u_id?>"/>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?=$this->cfg('UPLOAD_MAX_FILESIZE')?>"/>
			<h1>Пользователь</h1>
			

			<table class="form">
			<tr><th>Логин : </th><td><input class="input-text" name="login" value="<?=$login?>"/></td> </tr>
			<?/*if(!$this->id){*/?>
			<tr><th>Пароль : </th><td><input type="password" class="input-text" name="password"/></td> </tr>
			<tr><th>Статус : </th><td>
			<?foreach($this->status as $k=>$desc){?>
			<input type="radio" name="status" value="<?=$k?>" id="status<?=$k?>" <?=$status==$k?'checked':''?>><label for="status<?=$k?>"><?=$desc?></label>
			<?}?>
			</td> </tr>
			<?/*}*/?>
			
			
			<tr><th>Фио : </th><td><input class="input-text" name="name" value="<?=$name?>"/></td> </tr>
			<tr><th>Фамилия : </th><td><input class="input-text" name="last_name" value="<?=$last_name?>"/></td> </tr>
			<tr><th>Имя : </th><td><input class="input-text" name="first_name" value="<?=$first_name?>"/></td> </tr>
			<tr><th>Отчество : </th><td><input class="input-text" name="middle_name" value="<?=$middle_name?>"/></td> </tr>
			
			<tr><th>Организация : </th><td><input class="input-text" name="company" value="<?=$company?>"/></td> </tr>
			<tr><th>ИНН : </th><td><input class="input-text" name="inn" value="<?=@$inn?>"/></td> </tr>
			
			<?/*tr><th>Аватар : </th><td>
			<input type="hidden" name="avat_path" value=""/>
			<img id="img-avat" name="avat" src="<?=$avat?$avat:'/img/admin/no_image.png'?>"/>
			
			<input class="button" type="button" name="clear" value="Убрать"/><br />
			
			</td> </tr*/?>
			
			<tr><th>Город : </th><td><input class="input-text" name="city" value="<?=$city?>"/></td> </tr>
			<tr><th>Адрес : </th><td><input class="input-text" name="address" value="<?=$address?>"/></td> </tr>
			<?/*tr><th>Улица : </th><td><input class="input-text" name="street" value="<?=$street?>"/></td> </tr>
			<tr><th>Дом : </th><td><input class="input-text" name="house" value="<?=$house?>"/></td> </tr>
			<tr><th>кв/оф : </th><td><input class="input-text" name="flat" value="<?=$flat?>"/></td> </tr>
			<tr><th>подъезд : </th><td><input class="input-text" name="porch" value="<?=$porch?>"/></td> </tr>
			<tr><th>Этаж : </th><td><input class="input-text" name="floor" value="<?=$floor?>"/></td> </tr*/?>
			<tr><th>Телефон : </th><td><input class="input-text" name="phone" value="<?=$phone?>"/></td> </tr>
			<tr><th>Почта : </th><td><input class="input-text" name="mail" value="<?=$mail?>"/></td> </tr>
			<tr><th>Баланс : </th><td><input class="input-text" name="balance" value="<?=$balance?>"/> </td> </tr>
			<?/*tr><th>Бонус : </th><td><input class="input-text" name="bonus" value="<?=$bonus?>"/> </td> </tr*/?>
			<tr><th>Скидка% : </th><td><input class="input-text" name="discount" value="<?=$discount?>"/> </td> </tr>
			
			<tr><th>Группа : </th>
				<td>
				<select name="type">
			<?foreach($groups as $group=>$desc){?>
				<option value="<?=$group?>" <?=$group==$type?'selected':''?>><?=$desc?></option>
			<?}?>
			</select>
				</td>
			</tr>
			
			
			
			<?if(in_array($type,array('partner'))){ /*!empty($ank) */{?>
				
				<tr><th>Имя директора салона * : </th><td><input name="ank[director_name]" value="<?=htmlspecialchars(@$ank['director_name'])?>" class="input-text"></td> </tr>
				<tr><th>Имя администратора  * : </th><td><input name="ank[admin_name]" value="<?=htmlspecialchars(@$ank['admin_name'])?>" class="input-text"></td> </tr>
				<tr><th>Имя человека, ответственного за прием, выполнение, заказа, согласование цены, т.д. * : </th><td><input name="ank[resp_name]" value="<?=htmlspecialchars(@$ank['resp_name'])?>" class="input-text"></td> </tr>
				<tr><th>контактный (моб.) телефон ответственного * : </th><td><input name="ank[resp_phone]" value="<?=htmlspecialchars(@$ank['resp_phone'])?>" class="input-text"></td> </tr>
				
				<tr><th>Как часто проверяется почта * : </th><td><input name="ank[mail_check_period]" value="<?=htmlspecialchars(@$ank['mail_check_period'])?>" class="input-text"></td> </tr>
				<tr><th>Р/с для оплаты за заказы * : </th><td><textarea name="ank[account]" class="field"><?=htmlspecialchars(@$ank['account'])?></textarea></td> </tr>
				<tr><th><input type="checkbox" name="ank[poss_add_deliv]" value="1" <?if(@$ank['poss_add_deliv']){?>checked<?}?> ></th><td>Возможность доставки с цветами доп. подарков (бутылка шампанского, коробка конфет, мягкая игрушка, торт, корзина с сыром, корзина с фруктами)</td> </tr>
				<tr><th><input type="checkbox" name="ank[poss_photo]" value="1" <?if(@$ank['poss_photo']){?>checked<?}?> ></th><td>Возможность сделать цифровое фото получателя в момент доставки заказа и отправить нам по электронной почте</td> </tr>
				
				
				<tr><th>Стоимость доставки в пределах города (от - до руб.) : </th><td><input name="ank[delivery_price]" value="<?=htmlspecialchars(@$ank['delivery_price'])?>" class="input-text"></td> </tr>
				<tr><th>Близлежащие населенные пункты, в которые доставка осуществима.  Названия, стоимость (не дороже 800 руб.) : </th><td><textarea name="ank[delivery_settlements]" class="field"><?=htmlspecialchars(@$ank['delivery_settlements'])?></textarea></td> </tr>
				<tr><th>Почтовый адрес (вкл. индекс) : </th><td><textarea name="ank[post_address]" class="field"><?=htmlspecialchars(@$ank['post_address'])?></textarea></td> </tr>
				<tr><th>Как давно работает цветочный салон, количество точек\филиалов)(если есть), их названия и контактная информация  : </th><td><textarea name="ank[ext_info]" class="field"><?=htmlspecialchars(@$ank['ext_info'])?></textarea></td> </tr>
				<tr><th>Опыт работы с международными и российскими службами доставки цветов (вкл. Название)  : </th><td><textarea name="ank[experience]" class="field"><?=htmlspecialchars(@$ank['experience'])?></textarea></td> </tr>
				
				
				<tr>
				<td>
				Наличие Цветов и их цены (в непраздничный период)
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
				
				
				
				<tr><th>Коментарий администратора  : </th><td><textarea name="ank[comment]" class="field"><?=htmlspecialchars(@$ank['comment'])?></textarea></td> </tr>

			
			<?}}?>
			
			
			
			</table>
			<br/>
			<?if($u_id==0){?>
				<input type="submit" class="button save" name="save" value="Добавить"/>
			<?}else{?>
				<input type="submit" class="button save" name="save" value="Сохранить">
			<?}?>
			<input type="submit" class="button save" name="activate" value="Активировать">
			<input name="close" class="button" type="submit" value="Закрыть"/>
		
		</form>
		<script type="text/javascript" src="/js/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="/modules/usr/users.js"></script>
		