<html>

	<head>
		<style>
		body{font-size:12px}
		</style>
	</head>

<body>

	<!--����� �����������-->
	<div style="position:absolute;left:610px;top:20px">� <?=$num?></div>
	
	<!--����� ��������-->
	<div style="position:absolute;top:440px;left:220px">
		<h1>������������� ���������� "<?=$name?>"</h1>
		<?=$data['html']?>
	</div>
	
	<!--�������-->
	<div style="position:absolute;top:630px;left:100px">
		<?=$data['html2']?>
		<?if($img){?><img src="<?=$img?>"><?}?>
	</div>
	
	<!--��������-->
	<div style="position:absolute;top:738px;left:470px">
		<ul>
		<li>
		��� ��������� ���������� ������������������, 
		������� ������� �� �������� <?=$name?>, 
		�������� �� ������ ��������� � ������� 
		����� ������ ����������� � ������ <?=$pass?>
		</li>
		</ul>
		<?=$data['html3']?>
	</div>
	
</body>
</html>
