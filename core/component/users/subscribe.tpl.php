<form method="POST" action="?act=AddSubscribe">
<table style="width:100%">
		<tr>
		<td><?$pg->display();?></td>
		<td style="text-align:right">��������:<?$pg->displayPageSize($this->cfg('PAGE_SIZE_SELECT'),$this->pageSize);?></td>
		</tr>
		</table>
			<?if($rs->getCount()){?>
<table class="grid">
	<tr>
		
		<th style="white-space:nowrap"><?$this->sort('mail','Mail');?></th>
		
		<th style="white-space:nowrap"><?$this->sort('login','�����');?></th>
		<th style="white-space:nowrap">���</th>
		
		</th>
	</tr>
	<? while($rs->next()){?>
		<tr>
			<td>
				<?=$rs->get("mail");?>	
			</td>
			<td>
				<?=$rs->get("login");?>	
			</td><td>
				<?=$rs->get("type");?>	
			</td>
			
			<td style="white-space:nowrap;text-align:right">
				<a onclick="return confirm('�� ������������� ������ ������� ������?')" href="?act=unsubscribe&mail=<?=$rs->get('mail');?>"><img src="/img/pic/trash_16.gif" title="������� ������" border="0" alt=""/></a>
			</td>
		</tr>	
	<?}?>
</table>
<table style="width:100%">
		<tr>
		<td><?$pg->display();?></td>
		<td style="text-align:right">��������:<?$pg->displayPageSize($this->cfg('PAGE_SIZE_SELECT'),$this->pageSize);?></td>
		</tr>
		</table>
<?}else{?>
<div>������ ����</div>
<?}?>

<label>������� ������ Email</label><br>
<textarea name="mails"></textarea><br>
<?if($this->isSu()){?>
<a href="/admin/enum/subscribe_type/">�������� ��</a>
<?}else{?>
�������� ��
<?}?>
:
<?foreach ($this->enum('subscribe_type') as $k=>$desc) {?>
	<input type="checkbox" name="subscribe[]" value="<?=$k?>" id="subscribe_<?=$k?>"><label for="subscribe_<?=$k?>"><?=$desc?></label>
<?}?>
<br>
<button name="send" class="button" type="submit">��������</button>
</form>