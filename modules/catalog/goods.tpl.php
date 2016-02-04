<?
function propListGrp($id,$propGrp,$onlyPSort=false){
	$out='';
	foreach ($propGrp as $item) {?>
		<tr><td><?=$item['name']?></td><td><?=$item['value']?></td></tr>
	<?}
}

function propList($prop,$lim=0,$list=null){
	if($prop){?>
		<?if($list){?>
			<?foreach ($list as $id){
				if(isset($prop[$id]) && $propGrp=$prop[$id]){?>
					<?propListGrp($id,$propGrp,true)?>	
				<?}?>
			<?}?>
		<?}else{?>
			<?$n=0;foreach ($prop as $id=>$propGrp){?>
			<h3><?=$propGrp['name']?></h3>
			<table class="prop-list">
				<?propListGrp($id,$propGrp['p'])?>	
				</table>
			<?
			if($lim && ++$n>=$lim){break;}	
		}?>
		<?}?>
		<?
	}
}?>
<div id="goods">
<?if($this->isAdmin()){
	echo '<a href="/admin/catalog/goodsEdit/?id='.$data['id'].'" class="coin-text-edit" title="Редактировать"></a><br>';
}?>


<?if($comment_rait || $comment_count){?>
<div class="rait_blk">
<div class="rait r<?=$comment_rait?>"></div>
<a class="comment_count" href="javascript:comments()"><?=$comment_count?> <?=morph($comment_count,'отзыв','отзыва','отзывов')?></a>
</div>
<br>
<?}?>

<div class="left_bar">
	<div class="img_list">
	<?$img=($img?$img:$this->cfg('NO_IMG'))?>
		<a rel="gallery" href="<?=scaleImg($img,'w800')?>" style="background-image:url('<?=(scaleImg($img,'w380h320'))?>')"></a>
	</div>
	
	
	<?if(($rs=$imgList) && count($rs)>1){?>
	<div id="preview" class="">
	
	<a href="javascript:previewScrool(-160);" class="left" ></a>
	<a href="javascript:previewScrool(160);" class="right"></a>
	<ul>
	<?foreach ($rs as $n=>$item) {?>
		<li>
		<a href="<?=scaleImg($item,'w800')?>" rel='<?if($n){?>gallery<?}?>' rel2="<?=scaleImg($item,'w400')?>" rel2="" style="background-image:url(<?=scaleImg($item,'h80')?>)"></a>
		</li>
	<?}?>
	</ul>
	</div>
	<?}?>
	
	
	
	
	
	
</div><!--



--><div class="right_bar">
<?=$description?>
<?if($product){?><div>Артикул: <?=$product?></div><?}?>
<?if($m_name){?><div>Производитель: <?=$m_name?></div><?}?>

<!--Свойства товаров-->

<?/*div>Наличие: <?if($in_stock){?><span class="in_stock">Есть в наличии</span><?}else{?><span class="no_in_stock">НЕТ в наличии</span><?}?></div*/?>
<br>
<br>
<?if(false){?>
Кол-во: 
<!--<a class="down"></a>--><input id="count" class="field count <?if($weight_flg){?>weight<?}?>" value="1"><!--<a class="up"></a>-->
<br>
<a class="add title" href="javascript:shop.add(<?=$id?>,$('#count').val())" title=" В корзину">В корзину</a>
<?}else{?>

<a class="add title" href="javascript:shop.add(<?=$id?>,'+1')" title=" В корзину">В корзину</a><br>
<?}?>
<strong class="price"><?=price($price,$weight_flg) ?></strong><br>



<br>

<?/*if(isset($rel10)){?>
<a class="present" href="/catalog/<?=$this->cfg('CAT_IS_PRESENT')?>/rel/<?=$id?>,10/">Подарок</a>
<?}*/?>


</br>
<div class="discount"><?=$this->getText('msg_discount')?></div>
<script type="text/javascript">(function() {
          if (window.pluso)if (typeof window.pluso.start == "function") return;
          var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
          s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
          s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
          var h=d[g]('head')[0] || d[g]('body')[0];
          h.appendChild(s);
          })();</script>
        <div class="pluso" data-options="small,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir" data-background="transparent"></div>




</div>

<br>
<br>



<div id="tabs">
<ul class="tabs">
<?if(!empty($data['html'])){?><li><a href="#t1">Описание</a></li><?}?>
<?if(!empty($prop)){?><li><a href="#t2">Характеристики</a></li><?}?>
<?if(!empty($rel1)){?><li><a href="#t11">Аналоги</a></li><?}?>
<?if(!empty($rel2)){?><li><a href="#t12">Составные товары</a></li><?}?>
<?if(!empty($rel3)){?><li><a href="#t13">Комплектующие</a></li><?}?>

<li><a href="#t4">Отзывы <span><?=$comment_count?></span></a></li>
</ul>


<?if(!empty($data['html'])){?>
<div id="t1" class="tabs">
<?=$data['html']?>
</div>
<?}?>

<div id="t2" class="tabs">
<?propList($prop);?>
</div>

<?if(!empty($rel1)){?>
<div id="t11" class="tabs">
<?=$this->render(array('catalog'=>$rel1),dirname(__FILE__)."/catalog_view_table.tpl.php")?>
</div>
<?}?>
<?if(!empty($rel2)){?>
<div id="t12" class="tabs">
<?=$this->render(array('catalog'=>$rel2),dirname(__FILE__)."/catalog_view_table.tpl.php")?>
</div>
<?}?>
<?if(!empty($rel3)){?>
<div id="t13" class="tabs">
<?=$this->render(array('catalog'=>$rel3),dirname(__FILE__)."/catalog_view_table.tpl.php")?>
</div>
<?}?>
<?/*div id="t3" class="tabs"><?=$data['html3']?></div*/?>
<div id="t4" class="tabs goods_comment">
<?include('comment.tpl.php')?>
</div>

</div>



<?/*if(isset($rel1)){?>
	<h2>Смотрите также:</h2>
	<?=$this->render(array('catalog'=>$rel1),dirname(__FILE__)."/catalog_view_table.tpl.php")?>
	
<?}*/?>

<script src="/colorbox1.5.13/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="/modules/catalog/goods.js"></script>
<script type="text/javascript"> 
$(function(){
	$('a[rel="gallery"]').colorbox();
});
</script>