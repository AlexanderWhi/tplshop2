<?//include('menu.tpl.php')?>
<form  class="common common_block" id="cabinet-edit" method="POST" action="?act=savePassword">
	<label class="i">����� : *</label><strong><?=$login?></strong>
			<br/><br/>
						
	<label class="i">������ : *</label><input name="password" type="password" class="field"/><br/>
	<span id="error-password" class="error"></span>
			
	<label class="i">����������� : *</label><input name="cpassword" type="password" class="field"/><br/><br/>
			
	<input type="submit" value="���������" name="send" class="button long"/>	
</form>
<div id="success" style="display:none">������ ������</div>
<script type="text/javascript" src="/modules/cabinet/cabinet_edit.js"></script>