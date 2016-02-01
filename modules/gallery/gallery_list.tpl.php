<form method="POST" action="?act=apply">
<button class="button" name="save" type="submit">���������</button>
<div>
<a href="/admin/enum/gal_<?=$this->getType()?>_cat">�������</a>:
<a href="<?=$this->getUri(array('category'=>null))?>">���</a>
<?foreach ($cat_list as $k=>$d) {?>
	, <a href="<?=$this->getUri(array('category'=>"$k"))?>"><?=$d?></a>
<?}?>
</div>
<div>
<a href="/admin/enum/gal_<?=$this->getType()?>_label">�����</a>:
<a href="<?=$this->getUri(array('label'=>null))?>">���</a>
<?foreach ($label_list as $k=>$d) {?>
	, <a href="<?=$this->getUri(array('label'=>"$k"))?>"><?=$d?></a>
<?}?>
</div>

		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>�����������</th>
			<th><?=$this->sort('name','��������')?></th>
			<th><?=$this->sort('cat','���������')?></th>
			<th>�����</th>
			<th><?=$this->sort('date','����')?></th>
			<th><?=$this->sort('sort','�������')?></th>
			<th><?=$this->sort('sort_main','������� �� �������')?></th>
			<th>��������</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td style="width:110px">
					
						<?if($this->rs->get('img')){?><img src="<?=scaleImg($this->rs->get('img'),'w100')?>" alt="<?=$this->rs->get('title')?>"/><?}else{?><img src="/img/admin/no_image.png"/><?}?>	
					</td>
					<td style="text-align:left">
						<a href="?act=edit&id=<?=$this->rs->get('id')?>"><?=htmlspecialchars($this->rs->get('name'))?></a>
					</td>
					<td>
					<small>
					<?=$this->rs->get('cat_desc')?>
					</small>
					</td>
					
					<td>
					<?if(!empty($labels[$this->rs->get('id')])){?>
					<?foreach ($labels[$this->rs->get('id')] as $l) {?>
						<div>
					<small>
					<?=@$label_list[$l]?>
					</small>
					</div>
					<?}?>
					<?}?>
					</td>
					
					<td>
					<small>
					<?=dte($this->rs->get('date'))?>
					</small>
					</td>
					<?/*td>
						<small>[<?if($this->rs->get('state')=='edit'){?>
							�� ��������������
							<?}elseif ($this->rs->get('state')=='arhiv'){?>
							� ������
							<?}elseif ($this->rs->get('state')=='public'){?>
							������������
							<?}elseif ($this->rs->get('state')=='main'){?>
							�������
						<?}?>]</small>
					</td*/?>
					<td><input class="num" name="sort[<?=$this->rs->getInt('id')?>]" value="<?=$this->rs->getInt('sort')?>"></td>
					
					<td><input class="num" name="sort_main[<?=$this->rs->getInt('id')?>]" value="<?=$this->rs->getInt('sort_main')?>"></td>
					<td style="width:120px">
					
						<a href="?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="�������������" alt=""/></a>
						<a class="remove" id="remove<?=$this->rs->get('id')?>" href="#"><img src="/img/pic/trash_16.gif" title="�������" alt="�������"/></a>
					</td>
				</tr>	
			<?}?>
			</table>
			<?$this->displayPageControl();?>
		<?}else{?>
			<div>������� �� �������</div>
		<?}?>
	</form>
<script type="text/javascript" src="/modules/gallery/admin_gallery.js"></script>