<div>
<?//include('menu.tpl.php')?>
<div id="t1" class="tabs">

<form class="common common_block" id="cabinet-edit" method="POST" action="?act=save">
<!--			<label class="i">����� : *</label><strong><?=$login?></strong>-->
		<div class="inline_block">
			<strong>���������� ������:</strong><br><br>
			<label class="i">���: *</label><input name="name" class="field" value="<?=$name?>"/>
			<div id="error-name" class="error"></div>
			<br/>
			
			<label class="i">�������: * <small class="example">(+7 904 986 XXXX)</small></label><input name="phone" class="field" value="<?=$phone?>"/>
			
			<div id="error-phone" class="error"></div><br/>
					
			<label class="i">Email: *</label><input name="mail" class="field" value="<?=$mail?>"/>
			<div id="error-mail" class="error"></div><br/>
			
		</div>

		
		<div class="inline_block m">
		<strong>�������� ������:</strong><br><br>
		<div class="comment">���������� ������ ���� ������ �������� ������</div><br>
		<label class="i">������: *</label><input name="password" type="password" class="field"/><br/>
		<div id="error-password" class="error"></div>
			
		<label class="i">�����������: *</label><input name="cpassword" type="password" class="field"/><br/><br/>
		</div>
		<div>	
		<input type="submit" value="���������" name="send" class="button">	
		</div>
</form>
<div id="success" style="display:none">
		������ �������
</div>

<a href="?act=exit" class="exit">����� �� ������� ��������</a>


</div>

</div>



<script type="text/javascript" src="/modules/cabinet/cabinet_edit.js"></script>