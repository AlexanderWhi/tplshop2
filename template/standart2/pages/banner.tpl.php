<?if(true){?>
<div id="top-banner" class="sc-desktop">
<!--banner-->
			<div class="wrapper" >
			<?if(false && $adv=$this->adv('main_top')){?>
			<div class="main_top">
			<?=$adv?>
			</div>
			<?}?>
			<?$banner=$this->getBannerPlace();?>
			<?if($banner){?>
			<div class="slider">
				<ul>
				<?foreach ($banner as $row) {
					if($adv=$this->adv($row['id'])){?>
						<li>
				<?=$adv?>
				</li>
					<?}?>
					
				<?}?>
				</ul>
			</div>
			<?}?>
			</div>
<!--/banner-->
</div>
<?}?>