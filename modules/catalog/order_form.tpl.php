<div id="order">


<h2>�������� �����</h2>

<?if(!$this->getUserId()){?>
<?=$this->getText('order_guest_comment')?>
<?}?>

<table>
<tr>
<td style="width:400px">
<h3>������� ����� ��������:</h3>



</td>

<td style="width:400px">
<h3>���� � ����� ��������:
<div class="quest">
<?=$this->getText('quest_date_time')?>

</div>
</h3>


</td>

<td rowspan="2">



<?if(false && !empty($pay_system_list)){?>


<h3>������</h3>
<?if($this->isAdmin()){?><a class="coin-edit" href="/admin/enum/sh_pay_system/mode/desc/">������������� ������� ������</a><br><?}?>
<?
$i=0;
foreach ($pay_system_list as $k=>$desc) {?>
	<input name="pay_system" type="radio" class="radio" value="<?=$k?>" id="pay_system<?=$k?>" <?if(false && $k==2){?>checked<?}?>>
	<label for="pay_system<?=$k?>"><?=$desc?></label><br>
<?}?>
<div id="error-pay_system" class="error"></div>
<?}?>


</td>
</tr>

<tr>
<td>

<div>
<label>��������� ����� ��� �����<span>*</span></label><br>
<?if(false && $city_list){?>

<select name="city" class="select">
<?foreach ($city_list as $t=>$d) {?>
	<option value="<?=$d?>" <?if($city==$d){?>selected<?}?>><?=$d?></option>
<?}?>
</select>
<?}else{?>
<input name="city" class="field" value="<?=$city?>">
<?}?>



</div>

<br>

</td>
<td>
<label>&nbsp;</label><br>
<input name="date" class="date" value="<?=$date?>"/>

<?if($time_list){?>

<select name="time" class="select">
<?foreach ($time_list as $t=>$d) {?>
	<option value="<?=$t?>"><?=$d?></option>
<?}?>
</select>
<?}else{?>
<input name="time" class="field">
<?}?>
<div id="error-time" class="error"></div>

<input type="checkbox" class="checkbox" name="delivery_type" id="delivery_type2" value="2"><label for="delivery_type2">���������</label>
</td>

</tr>

<tr>
<td colspan="2">
<label>�����, � ����, � ��������, � ��������, ����, ����� ������: <span>*</span></label><br>
<input name="address" class="field extralong" value="<?=$address?>" style="width:670px">
<div class="comment" id="comment-address"></div>
<input name="delivery_zone" type="hidden">
<div id="error-address" class="error"></div>
<?if($addr_list){?>
<div style="padding:10px">
<?foreach ($addr_list as $addr) {?>
	<a class="addr" href="javascript:addr('<?=$addr?>')"><?=$addr?></a><br>
<?}?>
</div>
<?}?>
</td>


</tr>

<tr>
<td>
<label>�������: <span>*</span></label><br>
<input name="phone" class="field" value="<?=@$phone?>">
<div id="error-phone" class="error"></div>
<br>
</td>
<td>
<label>E-mail: <span>*</span></label><br>
<input name="mail" class="field" value="<?=@$mail?>" style="width:270px">
<div id="error-mail" class="error"></div>
</td>
</tr>

</table>
<label>����������� � ������: </label><br>
<textarea name="additionally" class="field extralong" style="width:670px"><?=$additionally?></textarea>

<br>

<span class="total">�����: <strong id="basket_total_summ_1"><?=price($price);?></strong></span>

<br>
<br>
<button class="button order silver" name="order" type="submit" alt="����� � ���������" >�������� �����</button>
</div>