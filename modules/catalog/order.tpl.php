<div id="order-report" style="display:none">

<h3>��� ����� <strong id="order-num1">{order_num1}</strong> </h3>
<?=$this->getText('order_report')?>
</div>



<div class="order-content" id="order-content-bar">


<div id="g-tabs-bar">
<?include('modules/cabinet/menu.tpl.php')?>
</div>

<div id="order-basket">
<div class="basket_content">
<?include('basket_content.tpl.php')?>
</div>
</div>

<div><span id="error-basket" class="error"></span></div>

</div>

<div class="order-form">
<form class="common" id="order-form" method="post" action="?act=sendOrder" onsubmit="return false">


<div id="delivery_type">
<a href="#3" class="<?if($delivery_type==3){?>act<?}?>">���������</a>
<a href="#1" class="<?if($delivery_type==1){?>act<?}?>">���������</a>
<a href="#2" class="<?if($delivery_type==2){?>act<?}?>">��������</a>

<input type="hidden" name="delivery_type" value="<?=$delivery_type?>" >
</div>


<!---->

<div id="block_delivery_type_1" class="block_delivery_type"><!---->
<h3>��������</h3>
	<div class="comment tab"><?=$this->getText('delivery_comment_1')?></div>
</div>	
<div id="block_delivery_type_2" class="block_delivery_type"><!---->
<h3>��������</h3>
	<div class="comment tab"><?=$this->getText('delivery_comment_2')?></div>

	<label class="i">����������<span>*</span></label>
	<input name="fullname" value="<?=$fullname?>" class="field" title="����������"><a href="#" id="open-address-list">�������</a>
	<div id="address-list" style="display:none"></div>
</div>
<div id="block_delivery_type_3" class="block_delivery_type"><!---->
	
	<div class="comment tab"><?=$this->getText('delivery_comment_3')?></div>
</div>
<div id="no_pickup">
		<label class="i">�����:<span>*</span></label>
		��.<input name="street" value="<?=$street?>" class="field" title="�����" style="width:110px">
		�.<input name="house" value="<?=$house?>" class="field" title="���" style="width:40px">
		��.<input name="flat" value="<?=$flat?>" class="field" title="��/��" style="width:30px">
		���.<input name="porch" value="<?=$porch?>" class="field" title="�������" style="width:20px">
		��.<input name="floor" value="<?=$floor?>" class="field" title="����" style="width:20px">
		<div id="addrbk"><?=$this->addrBk()?></div>
		
		<div id="error-address" class="error"></div>	

		<br>
		<label class="i">���� ��������:<span>*</span></label>
		<input name="date" class="field date" value="<?=$date?>"/><br>
		<div id="error-date" class="error"></div>
		
		<?/*label class="i">�����:</label>
		<input name="time" class="field" maxlength="64"/>
		<div id="error-time" class="error"></div*/?>
	<label class="i">����� ��������:</label>
	<select name="time" class="field">
	<?foreach ($time_list as $key=>$desc) {?>
		<option value="<?=$key?>"><?=$desc?></option>
	<?}?>
	</select>
	<div id="error-time" class="error"></div>

</div>

<?if($service){?>
<div id="additional-service">



<h3>�������������� �������</h3>

<?foreach ($service as $s) {?>
<input type="checkbox" name="service[<?=$s['id']?>]" value="1"><!-- style="display:none"-->
	<a href="#<?=$s['id']?>" title="<?=$s['name']?>" class="title service"><img src="<?=$s['img']?>"></a>
<?}?>
<?foreach ($service as $s) {?>
	<div id="service-item-<?=$s['img']?>">
	<?if(!empty($s['serviceList'])){?>
		<?foreach ($s['serviceList'] as $i_id=>$i_desc) {?>
		<input type="radio" name="service_item[<?=$s['id']?>]" value="<?=$i_id?>">
			<a href="#<?=$i_id?>"><?=$i_desc?></a>
		<?}?>
	<?}?>
	<?if(!empty($s['is_text'])){?>
		<textarea name="text"></textarea>
	<?}?>
	
	</div>
<?}?>


</div>
<?}?>

<?if($this->getUserId()==0){//?>
<div id="block-logon-change">
	<input type="radio" name="reg" id="chb_logon" value="0"><label for="chb_logon">��������� ���� ��� ������������������ �������������</label></br>
	<!--block-logon-->
	<div id="block-logon" style="display:none">
		
		<!--<strong>���� �� ����������������, ��������� ����.</strong><br>-->
		<div id="error-logon" class="error"></div>
		
		<label class="i">E-mail:</label>
		<input name="login" class="field"/><br>
		
		<label class="i">������:</label>
		<input type="password" name="password" class="field"/><br>
		
		<div class="btn">
		<input type="button" class="button long" name="logon" value="��������������"> | <a href="/cabinet/unlock/">������ ������?</a>
		</div>
		<hr>
	</div>
		
		<input checked type="radio" name="reg" value="1" id="reg_1"><label for="reg_1" class="l">������������������ � ���������� ����� (����� ����������� �������� ������ �������)</label><br>
		<!--<input type="radio" name="reg" value="0" id="reg_0"><label for="reg_0" class="l">���������� ����� ��� �����������</label><br><br>-->
		
		<div id="want_reg_tab">
			<?/*label class="i">�����:<span>*</span></label>
			<input name="reg_login" class="field"*/?>
			
			<label class="i">E-mail:<span>*</span></label>
			<input name="mail" class="field" value="<?=$mail?>">

			
			<input type="checkbox" name="auto_pass" id="auto_pass"><label class="l" for="auto_pass">��������� ������ �������������</label><br><br>
			
		
			<div id="error-mail" class="error"></div>
			
			<div id="error-reg_login" class="error"></div>
			
			
			<div id="auto_pass_tab">
			<label class="i">������:<span>*</span></label>
			<input name="password" class="field" type="password"><span class="example"><?=$this->fieldExample('password')?></span>
			<br>
			<div id="error-password" class="error"></div>
			
			<label class="i">�����������:<span>*</span></label>
			<input name="cpassword" class="field" type="password"><br>
		</div>
	
	</div>
</div>
<?}?>
<h3>��������</h3>
<label class="i">���:<span>*</span></label>
<input name="name" class="field" value="<?=$name?>"><span class="example"><?=$this->fieldExample('name')?></span><br>
<div id="error-name" class="error"></div>



<label class="i">������� :<span>*</span></label>
<input name="phone" class="field" value="<?=$phone?>" maxlength="32"><span class="example"><?=$this->fieldExample('phone')?></span><br>
<div id="error-phone" class="error"></div>


<label class="i">����������: </label>
<textarea name="additionally" class="field"></textarea><br>
<div id="error-additionally" class="error"></div>
		

<h3>������ ������</h3>

<div id="pay_type_blk">

<?if($bonus=$this->getUser('bonus')){?>
<div id="pay_bonus_blk">
<input type="checkbox" name="pay_bonus" value="1" id="pay_bonus"><label for="pay_bonus" class="l">������������ ������</label>
<a href="">� ��� <strong><?=$bonus?></strong> �������</a>
</div>
<?}?>

<div id="pay_system_1_blk">
<input type="radio" name="pay_system" value="1" id="pay_system_1" checked><label for="pay_system_1" class="l"><?=$pay_system_list[1]?></label>
<div class="comment tab"><?=$this->getText('pay_system_comment_1')?></div>
</div>

<div id="pay_system_2_blk">

	<div id="blk-account-jur">
		<a id="blk-account-jur-name" href="#">��������</a>
		
		<div id="blk-account-jur-form">
		<div id="blk-account-jur-form-list" style="display:none">
		<label class="i">�������:</label>
		<select id="blk-account-jur-form-select" class="field"></select>
		</div>
		<label class="i">��������:</label><input name="jur[name]" class="field"><br>
		<label class="i">��.�����:</label><input name="jur[addr_jur]" class="field"><br>
		<label class="i">����.�����:</label><input name="jur[addr]" class="field"><br>
		<label class="i">���:</label><input name="jur[inn]" class="field"><br>
		<label class="i">���:</label><input name="jur[kpp]" class="field"><br>
		<label class="i">����:</label><input name="jur[ogrn]" class="field"><br>
		<button class="short">�������</button>
	</div>

</div>
<input type="radio" name="pay_system" value="2" id="pay_system_2" ><label for="pay_system_2" class="l"><?=$pay_system_list[2]?></label>
<div class="comment tab"><?=$this->getText('pay_system_comment_2')?></div>
</div>
<div id="pay_system_3_blk">
<input type="radio" name="pay_system" value="3" id="pay_system_3"><label for="pay_system_3" class="l"><?=$pay_system_list[3]?></label>
<div class="comment tab"><?=$this->getText('pay_system_comment_3')?></div>
</div>

</div><!--pay_type_blk-->


<div class="btn">
<input class="button long" name="order" type="submit" alt="����� � ���������" value="�������� �����">
</div>

</form>
</div>
<script type="text/javascript">
var ADDRESS_LIST=<?=$data['address_list']?>;
var JUR_LIST=<?=$data['jur_list']?>;
var NOW=<?=time()?>
</script>
<script type="text/javascript" src="/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="/modules/catalog/order.js"></script>