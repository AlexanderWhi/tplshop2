<?=$this->getText($this->getType())?>

<a href="#feedback-form" class="expand">���������� ����������</a><br>

<form style="display:none" class="common" id="feedback-form" action="/feedback/?act=sendPublic"  enctype="multipart/form-data" target="fr" method="POST">
<div class=" block">
<iframe name="fr" style="display:none"></iframe>
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="type" value="upublic">
<?$name=$this->getUser('name');?>

<label>������� ���: </label><small class="error" id="error-name"></small><br>
<input name="name" class="field" value="<?=$name?>"><br>

<label>������: </label><small class="error" id="error-url"></small><br>
<input name="url" class="field" ><br>

<label>����: </label><small class="error" id="error-url"></small><br>
<input name="file" type="file" id="file"><label for="file">����...</label><br>
<div class="comment">� ������� .pdf,.doc,.docx,.txt</div>


<label>�����������:<span>*</span></label><small class="error" id="error-comment"></small><br>
<textarea name="comment" class="field"></textarea><br>

<input type="checkbox" name="author" id="author" value="1"><label for="author">� �����</label><br>

<?if(defined('IMG_SECURITY')){?>
		<label>������� ���: <span>*</span></label><small class="error" id="error-capture"></small><br>
		<?$this->capture("upublic")?>
	<?}?><br>
	<input type="submit" class="button long" name="send" value="���������">
</div>


</form>
<div class="success"><?=$this->getText('msg_feedback_success')?></div>
<script type="text/javascript" src="/modules/feedback/feedback.js"></script>

<br>
<?if($category_list){?>
<div class="public_category">
<a href="<?=$this->getUri(array('category'=>null))?>" class="<?=$this->getUriIntVal('category')==0?'act':''?>">���</a>
<?foreach ($category_list as $k=>$d){?>
<a href="<?=$this->getUri(array('category'=>"$k"))?>" class="<?=$this->getUriIntVal('category')==$k?'act':''?>"><?=$d?></a>
<?}?>
</div>
<?}?>
<?if ($rs){?>
	<?include('public_list.tpl.php')?>
	<div class="page"><?=$this->displayPageNav($pg)?></div>
<?}else{?>
<div>������ ����</div>
<?}?>
<?include('modules/cabinet/subscribe.tpl.php')?>