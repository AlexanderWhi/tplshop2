<?=$this->getText($this->getType())?>
<?if ($rs){?>
	<table id="action">
	<tr>
	<?$i=0;foreach($rs as $row){?>
	<td>
	<?$row['img']=($row['img']?$row['img']:$this->cfg('NO_IMG'))?>
	<?if(trim($row['img'])){?><a href="/catalog/action/<?=$row['id']?>/"><img src="<?=scaleImg($row['img'],'w580')?>"></a><?}?>
	<div class="item">					
		
		<a class="header" href="/catalog/action/<?=$row['id']?>/" title="���������..."><?=$row['title']?></a>
		<div class="date">� <?=fdte1($row['date'])?> �� <?=fdte1($row['date_to'])?></div>
		<strong>
		<?$days=round((strtotime($row['date_to'])-time())/(3600*24) ) ?>
		<?if($days>-1){?>
		�������� 
		
		<?=$days?> <?=morph($days,'����','���','����')?>
		<?}?>
		</strong>
	</div>
	<?}?>
	</td>
	</tr></table>
	<?$this->displayPageNav($pg)?>
<?}else{?>
<div>������ ����</div>
<?}?>