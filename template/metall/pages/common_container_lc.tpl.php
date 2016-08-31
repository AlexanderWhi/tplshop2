<!DOCTYPE html>
<html>
<head>
<?include('header.tpl.php')?>
<link rel="stylesheet" type="text/css" href="/template/<?=$this->theme()?>/style/left_bar.css?<?=$no_cache?>" />
</head>
<body class="<?=@$class?>">
<?include('modules/ceo/ceo.tpl.php')?>
<?include('popup.tpl.php')?>
<div id="wrap"><!--#wrap-->	
	<?include('header_site.tpl.php')?>	
		
	<div id="middle" class="border">	
		<div class="wrapper">
			<div id="left-bar">
			<?if($this->tplLeftComponent){?>
			<?include($this->tplLeftComponent)?>
			<?}else{?>
			<?include('container_left.tpl.php')?>
			<?}?>
			</div>
			<div id="center-bar" class="border">
				<div id="tips"><?=$this->renderExplorer(' / ')?></div>
				
				<div id="content" class="bar">
						
					<?//=$this->getCeoText('1')?>
					<?if($this->getUri()!='/'){?><h1><?=$this->getHeader()?></h1><?}?>
					<?=$CONTENT;?>
			
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
	
	<?//include('footer_catalog.tpl.php')?>
</div>
<?include('footer_site.tpl.php')?>
<!--/#wrap-->


<div id="debug"></div>

</body>
</html>