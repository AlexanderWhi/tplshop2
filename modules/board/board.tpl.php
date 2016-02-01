<?=$this->getText('/board/')?>

 <?if($this->getUserId()){?>&nbsp;&nbsp;&nbsp;<a style="float:right" href="<?=$this->getUri(array('my'=>true))?>">��� ����������</a><?}?>
<?if($this->getUserId()){?>
<a href="#board-form" class="button">��������</a> <br>
<?}?>
<?if ($rs){?>
	<?include('board_common_list.tpl.php')?>
	<div class="page"><?$pg->display()?></div>
<?}else{?>
<div>������ ����</div>
<?}?>
<?if($this->getUserId()){?>
<br>
<form class="common" id="board-form" action="?act=send" >

<div class="block">
<h2>�������� ����������</h2>

<?if($catalog_tree){?>
<div>
<label>������:</label><br>

<?
function _select_tree($arr,$value,$deep=0){
	foreach ($arr as $row) {
		?><option value="<?=$row['id']?>" <?if($value==$row['id']){?>selected<?}?>><?=str_repeat('&nbsp;',$deep*3)?><?=$row['name']?></option><?
		if(!empty($row['children'])){
			_select_tree($row['children'],$value,$deep+1);
		}
	}
}?>


<select name="category">

<?_select_tree($catalog_tree,$category)?>

</select></div>

<?}?>


<div><label>��������: <span>*</span></label><br><textarea name="description" class="field"></textarea></div>
<div><label>������ �����: <span>*</span></label><br><textarea name="text" class="field"></textarea></div>
<div><label>����� ��������� ��:</label><br><input name="date_to" class="date" value="<?=dte($date_to)?>"></div>

<input type="submit" class="button long" name="send" value="���������">
</div>

</form>
<div class="success"><?=$this->getText('msg_board_success')?></div>
<?}else{?>
���������� ��������� <a href="/join/">����</a> ��� <a href="/join/signup/">������������������</a>
<?}?>
<script type="text/javascript" src="/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="/modules/board/board.js"></script>