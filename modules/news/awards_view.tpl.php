<?/*if($img!=''){?>
	<img src="<?=$img?>?m_w=150" align="left"/>
<?}*/?>
<?if($this->isAdmin()){
	echo '<a href="/admin/'.$this->type.'/?act=edit&id='.$data['id'].'" class="coin-text-edit" title="Редактировать"></a>';
}?>

<?/*if($this->type!='awards'){?>
<div class="date"><?=date('d.m.Y',$nws_date)?></div>
<?}*/?>

<?if(false && trim($img)){?>
	<img src="<?=scaleImg($img,'w100')?>" align="left" style="margin-right:10px">
<?}?>
<?/*strong style="display:block;font-size:13pt;color:#3399ff"><?=$data['title']?></strong*/?>
<?=$data['content']?>
<div style="margin-top:10px"><strong>Просмотров: <?=$view?></strong> <a href="/<?=$this->type?>/">перейти к другим отзывам</a><?/*if($this->type=='news' && $topic_id>0){?><a href="/forum/topic/<?=$topic_id?>/">Обсудить</a><?}*/?></div>

