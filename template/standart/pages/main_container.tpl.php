<!DOCTYPE html>
<html>
<head>
<?include('header.tpl.php')?>
<link rel="stylesheet" type="text/css" href="/template/<?=$this->theme()?>/style/left_bar.css?<?=$no_cache?>" />
</head>
<body>
<?include('modules/ceo/ceo.tpl.php')?>
<?include('popup.tpl.php')?>
<div id="wrap" >
<?include('header_site.tpl.php')?>
			

<?include('banner.tpl.php')?>

	<div id="middle" class="border">
	
	
	
		<?//=$this->getCeoText('1')?>
		<?=$CONTENT;?>
		<?//=$this->getCeoText('2')?>
		
		<?/*div style="padding-top:30px;color:#aaa">
		������� ����������? ������ ������� - �������� � ������� Ctrl+Enter
		</div*/?>
		
	</div><!--middle-->
</div>
<!--/#wrap-->

<?include('footer_site.tpl.php')?>

<div id="notice"></div>
<div id="debug"></div>
</body>
</html>
