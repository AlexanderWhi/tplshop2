<form class="common">
<h3>�� ������: <?=$this->search?></h3>
<input class="field" value="<?=$this->search?>" name="search">
<button type="submit" class="button short">�����</button>
</form>


<div class="article">	<?if($type_search){?>
	<h3><?=$this->getTitle()?>: <?=$title?></h3>
	<?if($type_search=='catalog'){$catalog=$rs;?>	
			
		<?include('modules/catalog/catalog_view_list.tpl.php')?>
		<br clear="all">
	<?}else{?>
	
		<div id="basic_news">
		<h3><?=$this->getTitle()?>: <?=$title?></h3>
			<?if (isset($rs)){?>
				<?foreach($rs as $res){?>
					<a class="header" href="<?=$href?><?=$res['id']?>/" title="���������..."><?=$res['title']?></a><br/>
					<?=$res['description']?><br/><br/>
				<?}?>
				<div class="page"><?$pg->display()?></div>
			<?}else{?>
				��� ������ ������ ������ ���������. ���������� �������� ��������� ������.
			<?}?>
		
	</div>
	<?}?>
	

	<?}else{
		$clear=true;
		?>
	
		<?foreach ($this->result as $key=>$result){?>
			<?if (isset($rs[$key]['result'])){$clear=false;?>
			
			<?if($key=='catalog' && $catalog=$rs[$key]['result']){?>	
			
				<?include('modules/catalog/catalog_view_list.tpl.php')?>
	
				<a href="/search/<?=$key?>/?search=<?=urlencode($this->search)?>">����� <?=$rs[$key]['count']?></a>
				<br>
				<br>
			<?}else{?>
			
			
				<h3><?=$this->getTitle()?>: <?=$rs[$key]['title']?></h3>
				<div class="public">
				<?foreach($rs[$key]['result'] as $res){?>
					<div class="item">
						<a class="header" href="<?=$rs[$key]['href']?><?=rtrim($res['id'],'/')?>/" title="���������..."><?=$res['title']?></a>
						<?=$res['description']?>
					</div>
				<?}?>
				<a href="/search/<?=$key?>/?search=<?=urlencode($this->search)?>">����� <?=$rs[$key]['count']?></a>
				<br>
				<br>
				</div>
			
			<?}?>
			
			
			<?}?>
			
			
		<?}?>
		<?if($clear){?>
		��� ������ ������ ������ ���������. ���������� �������� ��������� ������.
		<?}?>
	<?}?>
	</div>
<!--	<script type="text/javascript" src="/modules/catalog/catalog.js"></script>-->