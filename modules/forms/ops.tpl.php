<form id="forms" class="common" action="?act=send" method="POST">
<table>
<tbody>
<tr>

<th colspan="2">
<h3>Индивидуальные сведения Застрахованного лица (соответствует в договоре п. 1, раздел II)</h3>
</th></tr>
<tr><th style="width:150px"><label>Фамилия:<span>*</span></label></th><td><input class="field req" name="last_name"/></td></tr>
<tr><th><label>Имя:<span>*</span></label></th><td><input class="field req" name="first_name"></td></tr>
<tr><th><label>Отчество:<span>*</span></label></th><td><input class="field req" name="middle_name"></td>
</tr>

<tr><th><label>Пол:</label></th>
<td><select name="sex"><option value="1">М</option><option value="2">Ж</option></select></td>
</tr>

<tr>
<th colspan="2">
<h3>Ф.И.О. Застрахованного лица при рождении (соответствует в договоре п. 1, раздел II)</h3>
</th></tr>
<tr><th><label>Фамилия:</label></th>
<td><input class="field" name="last_name_b" /></td>
</tr>
<tr><th><label>Имя:</label></th>
<td><input class="field" name="first_name_b" /></td>
</tr>
<tr><th><label>Отчество:</label></th>
<td><input class="field" name="middle_name_b" /></td>
</tr>
<tr><th><label>Дата Рождения:<span>*</span></label></th>
<td><input class="field req" name="birth_date">[<span>ДД.ММ.ГГГГ</span>]</td>
</tr>
<tr><th><label>Место рождения:<span>*</span></label></th>
<td><input class="field req" name="birth_place"></td>
</tr>
<tr><th><label>Номер Страхового<br>свидетельства обязательного<br>пенсионного страхования:<span>*</span></label></th>
<td><input class="field req" name="nssops"></td>
</tr>

<tr><th colspan="2">
<h3>Паспорт (или иной документ удостоверяющий личность) </h3>
</th></tr>
<tr><th><label>Наименование  :</label></th>
<td>
<select name="pasp_name">
<option value="1">Паспорт Гражданина Российской Федерации</option>
<option value="2">Общегражданский заграничный паспорт</option>
<option value="3">Удостоверение личности военнослужащего</option>
<option value="4">Военный билет</option>
<option value="5">Паспорт моряка</option>
</select>		
</td>
</tr>
<tr><th><label>Номер :<span>*</span></label></th>
<td><input class="field req" name="pas_num"></td>
</tr>
<tr><th><label>Серия :<span>*</span></label></th>
<td><input class="field req" name="pas_ser" req></td>
</tr>
<tr><th><label>Кем выдан:<span>*</span></label></th>
<td><input class="field req" name="pas_who"></td>
</tr>
<tr><th><label>Когда выдан :<span>*</span></label></th>
<td><input class="field req" name="pas_when">[<span>ДД.ММ.ГГГГ</span>]</td>
</tr>

<tr><td></td><td>
Способ получения накопительной части трудовой пенсии<br>
<input value="1" name="get_method" type="radio"/>Почтовым переводом 
<input checked="checked" value="2" name="get_method" type="radio"/>Перечислением на расчетный счет
<input value="3" name="get_method" type="radio"/>Через кассу Фонда
</td></tr>
<tr><th><label>Предыдущий страховщик :<span>*</span></label></th><td><input class="field req" name="prev_str_name"></td>
<tr><th><label>Адрес Регистрации :<span>*</span></label></th><td><input class="field req" name="addr_reg"></td></tr>
<tr><th><label>Почтовый адрес :</label></th><td><input class="field" name="addr_pocht"></td></tr>
<tr><th colspan="2"><h3>Телефон </h3></th></tr>

<tr><th><label>Контактный:<span>*</span></label></th><td><input class="field req" name="contact_phone"></td></tr>
<tr><th><label>Домашний :</label></th><td><input class="field" name="home_phone"></td></tr>

<tr><th colspan="2"><h3>Правоприемники (соответствует в договоре п. 25 а, раздел VII) </h3></th></tr>

<tr><th colspan="2"><h3>Правоприемник  </h3></th></tr>
<tr><th><label>Фамилия :</label></th><td><input class="field" name="pp1_last_name" /></td></tr>
<tr><th><label>Имя : </label></th><td><input class="field" name="pp1_first_name" /></td></tr>
<tr><th><label>Отчество : </label></th><td><input class="field" name="pp1_middle_name" /></td></tr>
<tr><th><label>Доля : </label></th><td><input class="field" name="pp1_path" />[<span>число</span>]</td></tr>

<tr><th colspan="2"><h3>Правоприемник  </h3></th></tr>
<tr><th><label>Фамилия :</label></th><td><input class="field" name="pp2_last_name" /></td></tr>
<tr><th><label>Имя : </label></th><td><input class="field" name="pp2_first_name" /></td></tr>
<tr><th><label>Отчество : </label></th><td><input class="field" name="pp2_middle_name" /></td></tr>
<tr><th><label>Доля : </label></th><td><input class="field" name="pp2_path" />[<span>число</span>]</td></tr>

<tr><th colspan="2"><h3>Правоприемник  </h3></th></tr>
<tr><th><label>Фамилия :</label></th><td><input class="field" name="pp3_last_name" /></td></tr>
<tr><th><label>Имя : </label></th><td><input class="field" name="pp3_first_name" /></td></tr>
<tr><th><label>Отчество : </label></th><td><input class="field" name="pp3_middle_name" /></td></tr>
<tr><th><label>Доля : </label></th><td><input class="field" name="pp3_path" />[<span>число</span>]</td></tr>

<tr><th colspan="2"><h3>Дополнительно </h3></th></tr>
<tr><th><label>Адрес электронной почты  : </label></th><td><input class="field" name="mail" /></td></tr>

<tr><th>&nbsp;</th>
<td><input class="button" type="submit" name="send" value="Отправить" /></td>
</tr>
</tbody>
</table>
</form>