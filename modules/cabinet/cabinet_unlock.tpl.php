<form class="common common_block" id="cabinet-edit" method="POST" action="?act=sendUnlock">
		¬ведите e-mail, указанный при регистрации!<br/><br/>
		<label class="i">Ёлектронна€ почта:*</label><input name="mail"  class="field"/>
		<span id="error-mail" class="error"></span><br/><br/>
		<input class="button long" type="submit" value="ќтправить"/>
</form>
<div id="success" style="display:none">
	<?=$this->getText('msg_unlock')?>
</div>
<script type="text/javascript" src="/modules/cabinet/cabinet_unlock.js"></script>