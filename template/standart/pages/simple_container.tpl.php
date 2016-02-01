<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="/js/jquery/jquery-1.6.4.min.js"></script>
<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
var SLIDE_SPEED=<?=intval($this->cfg('SLIDE_SPEED'))?>;
$(function(){	
	$('.coin-text-edit,.coin-place-edit,.coin-banner-edit').fadeTo(0,0.7).hover(function(){$(this).fadeTo(0,1);},function(){$(this).fadeTo(0,0.7);});
});
</script>
<style>
@import url("/css/coin.css");
</style>
</head>
<body >
<div id="wrap"><!--#wrap-->
				<div id="tips"><?=$this->renderExplorer(' / ')?></div>	
				<div id="content" class="bar">
					<?if($this->getUri()!='/'){?><h1><?=$this->getHeader()?></h1><?}?>
					<?=$CONTENT;?>
				</div>
</div><!--/#wrap-->
</body>
</html>