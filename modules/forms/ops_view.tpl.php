<table>
<tbody>
<tr>

<th colspan="2">
<h3>�������������� �������� ��������������� ���� (������������� � �������� �. 1, ������ II)</h3>
</th></tr>
<tr><th style="width:150px"><label>�������:<span>*</span></label></th><td><?=$last_name?></td></tr>
<tr><th><label>���:<span>*</span></label></th><td><?=$first_name?></td></tr>
<tr><th><label>��������:<span>*</span></label></th><td><?=$middle_name?></td>
</tr>

<tr><th><label>���:</label></th>
<td>
<?
$sex_list=array(
'1'=>'�',
'2'=>'�',
);
echo $sex_list[$sex];
?>
</td>
</tr>

<tr>
<th colspan="2">
<h3>�.�.�. ��������������� ���� ��� �������� (������������� � �������� �. 1, ������ II)</h3>
</th></tr>
<tr><th><label>�������:</label></th>
<td><?=$last_name_b?></td>
</tr>
<tr><th><label>���:</label></th>
<td><?=$first_name_b?></td>
</tr>
<tr><th><label>��������:</label></th>
<td><?=$middle_name_b?></td>
</tr>
<tr><th><label>���� ��������:<span>*</span></label></th>
<td><?=$birth_date?></td>
</tr>
<tr><th><label>����� ��������:<span>*</span></label></th>
<td><?=$birth_place?></td>
</tr>
<tr><th><label>����� ����������<br>������������� �������������<br>����������� �����������:<span>*</span></label></th>
<td><?=$nssops?></td>
</tr>

<tr><th colspan="2">
<h3>������� (��� ���� �������� �������������� ��������) </h3>
</th></tr>
<tr><th><label>������������  :</label></th>
<td>

<?
$pasp_name_list=array(
'1'=>'������� ���������� ���������� ���������',
'2'=>'��������������� ����������� �������',
'3'=>'������������� �������� ���������������',
'4'=>'������� �����',
'5'=>'������� ������',
);
echo $pasp_name_list[$pasp_name];
?>		
</td>
</tr>
<tr><th><label>����� :<span>*</span></label></th>
<td><?=$pas_num?></td>
</tr>
<tr><th><label>����� :<span>*</span></label></th>
<td><?=$pas_ser?></td>
</tr>
<tr><th><label>��� �����:<span>*</span></label></th>
<td><?=$pas_who?></td>
</tr>
<tr><th><label>����� ����� :<span>*</span></label></th>
<td><?=$pas_when?></td>
</tr>

<tr><td></td><td>
������ ��������� ������������� ����� �������� ������<br>

<?
$get_method_list=array(
'1'=>'�������� ���������',
'2'=>'������������� �� ��������� ���',
'3'=>'����� ����� �����',
);
echo $get_method_list[$get_method];
?>

</td></tr>
<tr><th><label>���������� ���������� :<span>*</span></label></th><td><?=$prev_str_name?></td>
<tr><th><label>����� ����������� :<span>*</span></label></th><td><?=$addr_reg?></td></tr>
<tr><th><label>�������� ����� :</label></th><td><?=$addr_pocht?></td></tr>
<tr><th colspan="2"><h3>������� </h3></th></tr>

<tr><th><label>����������:<span>*</span></label></th><td><?=$contact_phone?></td></tr>
<tr><th><label>�������� :</label></th><td><?=$home_phone?></td></tr>

<tr><th colspan="2"><h3>�������������� (������������� � �������� �. 25 �, ������ VII) </h3></th></tr>

<tr><th colspan="2"><h3>�������������  </h3></th></tr>
<tr><th><label>������� :</label></th><td><?=$pp1_last_name?></td></tr>
<tr><th><label>��� : </label></th><td><?=$pp1_first_name?></td></tr>
<tr><th><label>�������� : </label></th><td><?=$pp1_middle_name?></td></tr>
<tr><th><label>���� : </label></th><td><?=$pp1_path?></td></tr>

<tr><th colspan="2"><h3>�������������  </h3></th></tr>
<tr><th><label>������� :</label></th><td><?=$pp2_last_name?></td></tr>
<tr><th><label>��� : </label></th><td><?=$pp2_first_name?></td></tr>
<tr><th><label>�������� : </label></th><td><?=$pp2_middle_name?></td></tr>
<tr><th><label>���� : </label></th><td><?=$pp2_path?></td></tr>

<tr><th colspan="2"><h3>�������������  </h3></th></tr>
<tr><th><label>������� :</label></th><td><?=$pp3_last_name?></td></tr>
<tr><th><label>��� : </label></th><td><?=$pp3_first_name?></td></tr>
<tr><th><label>�������� : </label></th><td><?=$pp3_middle_name?></td></tr>
<tr><th><label>���� : </label></th><td><?=$pp3_path?></td></tr>

<tr><th colspan="2"><h3>������������� </h3></th></tr>
<tr><th><label>����� ����������� �����  : </label></th><td><?=$mail?> </td></tr>

</tbody>
</table>