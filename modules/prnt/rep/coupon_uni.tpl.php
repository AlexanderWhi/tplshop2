<html>

	<head>
		<style>
		body{font-size:12px}
		</style>
	</head>

<body>

	<!--Номер сертификата-->
	<div style="position:absolute;left:610px;top:20px">№ <?=$num?></div>
	
	<!--Общее описание-->
	<div style="position:absolute;top:440px;left:220px">
		<h1>Универсальный сертификат "<?=$name?>"</h1>
		<?=$data['html']?>
	</div>
	
	<!--условия-->
	<div style="position:absolute;top:630px;left:100px">
		<?=$data['html2']?>
		<?if($img){?><img src="<?=$img?>"><?}?>
	</div>
	
	<!--Контакты-->
	<div style="position:absolute;top:738px;left:470px">
		<ul>
		<li>
		Для активации необходимо зарегистрироваться, 
		выбрать подарок из каталога <?=$name?>, 
		кликнуть на кнопку активации и указать 
		номер Вашего сертификата и пароль <?=$pass?>
		</li>
		</ul>
		<?=$data['html3']?>
	</div>
	
</body>
</html>
