<table>
<tbody>
<tr>

<th colspan="2">
<h3>Индивидуальные сведения Застрахованного лица (соответствует в договоре п. 1, раздел II)</h3>
</th></tr>
<tr><th style="width:150px"><label>Фамилия:<span>*</span></label></th><td><?=$last_name?></td></tr>
<tr><th><label>Имя:<span>*</span></label></th><td><?=$first_name?></td></tr>
<tr><th><label>Отчество:<span>*</span></label></th><td><?=$middle_name?></td>
</tr>

<tr><th><label>Пол:</label></th>
<td>
<?
$sex_list=array(
'1'=>'М',
'2'=>'Ж',
);
echo $sex_list[$sex];
?>
</td>
</tr>

<tr>
<th colspan="2">
<h3>Ф.И.О. Застрахованного лица при рождении (соответствует в договоре п. 1, раздел II)</h3>
</th></tr>
<tr><th><label>Фамилия:</label></th>
<td><?=$last_name_b?></td>
</tr>
<tr><th><label>Имя:</label></th>
<td><?=$first_name_b?></td>
</tr>
<tr><th><label>Отчество:</label></th>
<td><?=$middle_name_b?></td>
</tr>
<tr><th><label>Дата Рождения:<span>*</span></label></th>
<td><?=$birth_date?></td>
</tr>
<tr><th><label>Место рождения:<span>*</span></label></th>
<td><?=$birth_place?></td>
</tr>
<tr><th><label>Номер Страхового<br>свидетельства обязательного<br>пенсионного страхования:<span>*</span></label></th>
<td><?=$nssops?></td>
</tr>

<tr><th colspan="2">
<h3>Паспорт (или иной документ удостоверяющий личность) </h3>
</th></tr>
<tr><th><label>Наименование  :</label></th>
<td>

<?
$pasp_name_list=array(
'1'=>'Паспорт Гражданина Российской Федерации',
'2'=>'Общегражданский заграничный паспорт',
'3'=>'Удостоверение личности военнослужащего',
'4'=>'Военный билет',
'5'=>'Паспорт моряка',
);
echo $pasp_name_list[$pasp_name];
?>		
</td>
</tr>
<tr><th><label>Номер :<span>*</span></label></th>
<td><?=$pas_num?></td>
</tr>
<tr><th><label>Серия :<span>*</span></label></th>
<td><?=$pas_ser?></td>
</tr>
<tr><th><label>Кем выдан:<span>*</span></label></th>
<td><?=$pas_who?></td>
</tr>
<tr><th><label>Когда выдан :<span>*</span></label></th>
<td><?=$pas_when?></td>
</tr>

<tr><td></td><td>
Способ получения накопительной части трудовой пенсии<br>

<?
$get_method_list=array(
'1'=>'Почтовым переводом',
'2'=>'Перечислением на расчетный сче',
'3'=>'Через кассу Фонда',
);
echo $get_method_list[$get_method];
?>

</td></tr>
<tr><th><label>Предыдущий страховщик :<span>*</span></label></th><td><?=$prev_str_name?></td>
<tr><th><label>Адрес Регистрации :<span>*</span></label></th><td><?=$addr_reg?></td></tr>
<tr><th><label>Почтовый адрес :</label></th><td><?=$addr_pocht?></td></tr>
<tr><th colspan="2"><h3>Телефон </h3></th></tr>

<tr><th><label>Контактный:<span>*</span></label></th><td><?=$contact_phone?></td></tr>
<tr><th><label>Домашний :</label></th><td><?=$home_phone?></td></tr>

<tr><th colspan="2"><h3>Правоприемники (соответствует в договоре п. 25 а, раздел VII) </h3></th></tr>

<tr><th colspan="2"><h3>Правоприемник  </h3></th></tr>
<tr><th><label>Фамилия :</label></th><td><?=$pp1_last_name?></td></tr>
<tr><th><label>Имя : </label></th><td><?=$pp1_first_name?></td></tr>
<tr><th><label>Отчество : </label></th><td><?=$pp1_middle_name?></td></tr>
<tr><th><label>Доля : </label></th><td><?=$pp1_path?></td></tr>

<tr><th colspan="2"><h3>Правоприемник  </h3></th></tr>
<tr><th><label>Фамилия :</label></th><td><?=$pp2_last_name?></td></tr>
<tr><th><label>Имя : </label></th><td><?=$pp2_first_name?></td></tr>
<tr><th><label>Отчество : </label></th><td><?=$pp2_middle_name?></td></tr>
<tr><th><label>Доля : </label></th><td><?=$pp2_path?></td></tr>

<tr><th colspan="2"><h3>Правоприемник  </h3></th></tr>
<tr><th><label>Фамилия :</label></th><td><?=$pp3_last_name?></td></tr>
<tr><th><label>Имя : </label></th><td><?=$pp3_first_name?></td></tr>
<tr><th><label>Отчество : </label></th><td><?=$pp3_middle_name?></td></tr>
<tr><th><label>Доля : </label></th><td><?=$pp3_path?></td></tr>

<tr><th colspan="2"><h3>Дополнительно </h3></th></tr>
<tr><th><label>Адрес электронной почты  : </label></th><td><?=$mail?> </td></tr>

</tbody>
</table>