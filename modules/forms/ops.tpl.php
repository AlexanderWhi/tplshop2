<form id="forms" class="common" action="?act=send" method="POST">
<table>
<tbody>
<tr>

<th colspan="2">
<h3>�������������� �������� ��������������� ���� (������������� � �������� �. 1, ������ II)</h3>
</th></tr>
<tr><th style="width:150px"><label>�������:<span>*</span></label></th><td><input class="field req" name="last_name"/></td></tr>
<tr><th><label>���:<span>*</span></label></th><td><input class="field req" name="first_name"></td></tr>
<tr><th><label>��������:<span>*</span></label></th><td><input class="field req" name="middle_name"></td>
</tr>

<tr><th><label>���:</label></th>
<td><select name="sex"><option value="1">�</option><option value="2">�</option></select></td>
</tr>

<tr>
<th colspan="2">
<h3>�.�.�. ��������������� ���� ��� �������� (������������� � �������� �. 1, ������ II)</h3>
</th></tr>
<tr><th><label>�������:</label></th>
<td><input class="field" name="last_name_b" /></td>
</tr>
<tr><th><label>���:</label></th>
<td><input class="field" name="first_name_b" /></td>
</tr>
<tr><th><label>��������:</label></th>
<td><input class="field" name="middle_name_b" /></td>
</tr>
<tr><th><label>���� ��������:<span>*</span></label></th>
<td><input class="field req" name="birth_date">[<span>��.��.����</span>]</td>
</tr>
<tr><th><label>����� ��������:<span>*</span></label></th>
<td><input class="field req" name="birth_place"></td>
</tr>
<tr><th><label>����� ����������<br>������������� �������������<br>����������� �����������:<span>*</span></label></th>
<td><input class="field req" name="nssops"></td>
</tr>

<tr><th colspan="2">
<h3>������� (��� ���� �������� �������������� ��������) </h3>
</th></tr>
<tr><th><label>������������  :</label></th>
<td>
<select name="pasp_name">
<option value="1">������� ���������� ���������� ���������</option>
<option value="2">��������������� ����������� �������</option>
<option value="3">������������� �������� ���������������</option>
<option value="4">������� �����</option>
<option value="5">������� ������</option>
</select>		
</td>
</tr>
<tr><th><label>����� :<span>*</span></label></th>
<td><input class="field req" name="pas_num"></td>
</tr>
<tr><th><label>����� :<span>*</span></label></th>
<td><input class="field req" name="pas_ser" req></td>
</tr>
<tr><th><label>��� �����:<span>*</span></label></th>
<td><input class="field req" name="pas_who"></td>
</tr>
<tr><th><label>����� ����� :<span>*</span></label></th>
<td><input class="field req" name="pas_when">[<span>��.��.����</span>]</td>
</tr>

<tr><td></td><td>
������ ��������� ������������� ����� �������� ������<br>
<input value="1" name="get_method" type="radio"/>�������� ��������� 
<input checked="checked" value="2" name="get_method" type="radio"/>������������� �� ��������� ����
<input value="3" name="get_method" type="radio"/>����� ����� �����
</td></tr>
<tr><th><label>���������� ���������� :<span>*</span></label></th><td><input class="field req" name="prev_str_name"></td>
<tr><th><label>����� ����������� :<span>*</span></label></th><td><input class="field req" name="addr_reg"></td></tr>
<tr><th><label>�������� ����� :</label></th><td><input class="field" name="addr_pocht"></td></tr>
<tr><th colspan="2"><h3>������� </h3></th></tr>

<tr><th><label>����������:<span>*</span></label></th><td><input class="field req" name="contact_phone"></td></tr>
<tr><th><label>�������� :</label></th><td><input class="field" name="home_phone"></td></tr>

<tr><th colspan="2"><h3>�������������� (������������� � �������� �. 25 �, ������ VII) </h3></th></tr>

<tr><th colspan="2"><h3>�������������  </h3></th></tr>
<tr><th><label>������� :</label></th><td><input class="field" name="pp1_last_name" /></td></tr>
<tr><th><label>��� : </label></th><td><input class="field" name="pp1_first_name" /></td></tr>
<tr><th><label>�������� : </label></th><td><input class="field" name="pp1_middle_name" /></td></tr>
<tr><th><label>���� : </label></th><td><input class="field" name="pp1_path" />[<span>�����</span>]</td></tr>

<tr><th colspan="2"><h3>�������������  </h3></th></tr>
<tr><th><label>������� :</label></th><td><input class="field" name="pp2_last_name" /></td></tr>
<tr><th><label>��� : </label></th><td><input class="field" name="pp2_first_name" /></td></tr>
<tr><th><label>�������� : </label></th><td><input class="field" name="pp2_middle_name" /></td></tr>
<tr><th><label>���� : </label></th><td><input class="field" name="pp2_path" />[<span>�����</span>]</td></tr>

<tr><th colspan="2"><h3>�������������  </h3></th></tr>
<tr><th><label>������� :</label></th><td><input class="field" name="pp3_last_name" /></td></tr>
<tr><th><label>��� : </label></th><td><input class="field" name="pp3_first_name" /></td></tr>
<tr><th><label>�������� : </label></th><td><input class="field" name="pp3_middle_name" /></td></tr>
<tr><th><label>���� : </label></th><td><input class="field" name="pp3_path" />[<span>�����</span>]</td></tr>

<tr><th colspan="2"><h3>������������� </h3></th></tr>
<tr><th><label>����� ����������� �����  : </label></th><td><input class="field" name="mail" /></td></tr>

<tr><th>&nbsp;</th>
<td><input class="button" type="submit" name="send" value="���������" /></td>
</tr>
</tbody>
</table>
</form>