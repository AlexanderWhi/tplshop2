<?if(empty($no_designer)){?>		

<h2>��������</h2>
		<table class="form">
		<?if($userid ){?>
		<tr><th style="width:200px">���</th><td>
		<a href="/admin/users/edit/?id=<?=$userid?>"><?=$name?><?if($company){?><?=$company?><?}?></a>
		</td></tr>
		<?}?>
		<tr><th>�����</th><td>
		<?if(!empty($edit)){?>
		<?/*input name="country" value="<?=$country?>" class="input-text">
		<input name="region" value="<?=$region?>" class="input-text"*/?>
		<input name="city" value="<?=$city?>" class="input-text"><br>
		<input name="address" value="<?=$address?>" class="input-text">
		<?}else{?>
		<?=$country?> <?=$region?> <?=$city?> <?=$address?>
		<?}?>
		</td></tr>
		
		<tr><th>�������</th><td><?if(!empty($edit)){?><input name="phone" value="<?=$phone?>" class="input-text"><?}else{?><?=$phone?><?}?></td></tr>
		<?if(!empty($mail)){?><tr><th>E-Mail</th><td><?=$mail?></td></tr><?}?>
		<tr><th>����������� ����������</th><td>
		<?if(!empty($edit) && false){?><input name="additionally" value="<?=$additionally?>" class="input-text"><?}else{?><?=$additionally?><?}?>
		</td></tr>
		</table>		
	<?}?>	
		<?/*h2>����������</h2>
		<table class="form">
		
		<tr><th style="width:200px">���</th><td>
		<?if(!empty($edit)){?><input name="fullname" value="<?=$fullname?>" class="input-text"><?}else{?><?=$fullname?><?}?>
		
		</td></tr>
		<tr><th>�����</th><td>
		<?if(!empty($edit)){?>
		
		<input name="city" value="<?=$city?>" class="input-text"><br>
		<input name="address" value="<?=$address?>" class="input-text">
		<?}else{?>
		<?=$country?> <?=$region?> <?=$city?> <?=$address?>
		<?}?>
		
		</td></tr>
		<tr><th>�������</th><td>
		<?if(!empty($edit)){?><input name="phone" value="<?=$phone?>" class="input-text"><?}else{?><?=$phone?><?}?>
		</td></tr>
		
		<tr><th>�������������</th><td>
		<?if(!empty($edit)){?><input name="additionally" value="<?=$additionally?>" class="input-text"><?}else{?><?=$additionally?><?}?>
		</td></tr>
		
		
		
		</table*/?>
		
		<?/*h2>�������� ������</h2>
		<table style="width:100%" class="form2 grid">			
		<tr><th style="width:200px">���� ��������</th><td><?=dte($date)?></td></tr>
		<tr><th>��� ��������</th><td><?$dt=array(1=>'�������',2=>"��������",3=>'���������');echo $dt[$delivery_type]?></td></tr>
		<tr><th>����� ��������</th><td><?=$time?></td></tr>
		<tr><th>�������������</th><td><?=$additionally?></td></tr>
		<tr><th>���-�� ������:</th><td><?=$person?></td></tr>
		</table*/?>
		<br />
		
		<h2>������� ������</h2>
		<table class="form">
		
		
		<?if(!empty($cause)){?><tr><th>�����</th><td><?=$cause?></td></tr><?}?>
		<?if(!empty($postcard)){?><tr><th>����� �� ��������</th><td><?=$postcard?></td></tr><?}?>
			
		<tr><th style="width:200px">���� ��������</th><td>
		<?if(!empty($edit)){?><input name="date" value="<?=dte($date)?>" class="date">
		<?}else{?><?=dte($date)?><?}?>
		
		</td></tr>
		<tr><th>����� ��������</th><td>
		<?if(!empty($edit)){?><input name="time" value="<?=$time?>" class="input-text">
		<?}else{?><?=$time?><?}?>
		
		</td></tr>
		<?if(!empty($anonymous)){?><tr><th>�������� ��� �����������</th><td><?=$anonymous?></td></tr><?}?>
		
		
		<?/*tr><th>��� ��������</th><td>
		<select>
		<?foreach ($deliveryTypeList as $v=>$desc) {?>
			<option value="<?=$v?>" <?if($delivery_type==$v){?>selected<?}?>><?=$desc?></option>
		<?}?>
		</select>
		</td></tr*/?>
		
		<?if(empty($no_designer)){?>
			<tr>
			<th>������
			<?if(isset($pay_system_list[$pay_system])){?>
			<small style="color:green">(<?=$pay_system_list[$pay_system]?>)</small>
			<?}?>
			</th>
			<td>
			
			<?if(!empty($edit)){?>
				<select name="pay_status">
				<option value="">--�� �������</option><?foreach ($pay_status_list as $k=>$desc) {?>
					<option value="<?=$k?>" <?if($k==$pay_status){?>selected<?}?>><?=$desc?></option>
				<?}?>
				</select>
				<?/*input name="pay_system" value="<?=$pay_system?>"*/?>
			
			<?}else{?>
				<?if($pay_system==3){?>
				<?if(empty($pay_status)){?>
					<strong style="color:red">�� ������� <?=dte($pay_time,'d.m.Y H:i')?></strong>
				<?}else{?>
					<strong style="color:green">������� <?=dte($pay_time,'d.m.Y H:i')?></strong>
				<?}?>
				<?}elseif($pay_system==2){?>
				<?if(!empty($data['pay_account_jur'])){?>
				<?=parsJur($data['pay_account_jur'])?>
				<?}?>
					
					<div>
					<a href="/prnt/SHET/?id=<?=$id?>">������ �����</a> | 
					<a href="/prnt/ACT/?id=<?=$id?>">������ ����</a>
					</div>
				<?}?>
			
			<?}?>
			
				
		
			</td>
			</tr>
		<?}?>
		</table>
		<br />	