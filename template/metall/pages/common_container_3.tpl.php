<!DOCTYPE html>
<html>
<head>
<?include('header.tpl.php')?>
</head>
<body>
<?include('modules/ceo/ceo.tpl.php')?>

<div id="wrap">
<?include('header_site.tpl.php')?>	
<!--#wrap-->	
	
	
	
	<div id="middle" class="border">
	
			
	<div class="wrapper">
	<div id="tips"><?=$this->renderExplorer(' / ')?></div>
	<div id="left-bar" class="side-bar">

		<a href="javascript:showCalc()" id="calc">������<br> ������� �������</a>
		<a href="javascript:showEditor()" id="editor">������������<br> �������</a>
	<br>
	<?$this->bar('vote',1)?>
	
		
	
	</div>
	<div id="right-bar" class="side-bar">
	
	
	
	<?if($newslist=$this->getNews('action',2)){?>
	<h2>����� � ������</h2>
		<div class="side-blk">
		<?foreach ($newslist as $row) {?>
		<?/*if($row['img']){?>
		<img src="<?=scaleImg($row['img'],'w180h120')?>">
		<?}*/?>
		<a href="/action/<?=$row['id']?>/"><?=$row['title']?></a>
			<?=$row['description']?>
		<?}?>
		<a href="/action/" class="more">��� �����</a>

		</div>
	<?}?>
	
	
	<?if($faq=$this->getFaq('faq')){?>
	<h2>���������� �������</h2>
	<div class="side-blk">
	<?foreach ($faq as $g) {?>
		<a href="/faq/#faq<?=$g['id']?>">
		
		<?=$g['question']?>
		</a>
		<?=strlen($g['answer'])>100?substr($g['answer'],0,100)."...":$g['answer']?>
		<a class="more" href="/faq/#faq<?=$g['id']?>">��������� �</a>
	<?}?>
	</div>
	<?}?>
	
	
	<?if($our_works=$this->getGallery('our_works')){?>
	<h2>����� ������</h2>
	<div class="side-blk">
	<?foreach ($our_works as $g) {?>
		<a href="/our_works/<?=$g['id']?>/">
		<img src="<?=scaleImg($g['img'],'w180')?>">
		<?=$g['name']?>
		</a>
		<?=$g['description']?>
		
	<?}?>
	<a class="more" href="/our_works/">������� ������� �</a>
	</div>
	<?}?>
	
	</div>
	
	<div id="center-bar" class="border">
	
	<div id="content" class="bar <?=str_replace('/','_',trim($this->getUri(),'/'))?>">
		
	<?//=$this->getCeoText('1')?>
	<h1><?=$this->getHeader()?></h1>
	<?=$CONTENT;?>
	<?//=$this->getCeoText('2')?>
	
	<?/*div style="padding-top:30px;color:#aaa">
	������� ����������? ������ ������� - �������� � ������� Ctrl+Enter
	</div*/?>
	</div>

	</div>
	
	
	
	</div>
	</div>
	
	
	
	
	<?include('footer_site.tpl.php')?>
</div>
<!--/#wrap-->


<div id="debug"></div>

</body>
</html>
