<?
global $get;
function displayModule($module,$location,$component,$deep=0){
		$c=0;
		foreach ($module as $key=>$item){
			?><tr id="module<?=$item['mod_id']?>" class="modules-item <?if($item['hide']==1){?>hidden<?}?>" parent="<?=$item['mod_parent_id']?>">
			<td>
			<input type="checkbox" name="item[]" value="<?=$item['mod_id']?>">
			
			</td>
			<td style="padding-left:<?=$deep*20?>px;">
			<?if($item['mod_type']==0){?>
			<img style="vertical-align:middle" src="/img/pic/apps_16.gif"  alt="Приложение"/>
			<?}elseif($item['mod_type']==1){?>
			<img style="vertical-align:middle" src="/img/pic/docs_16.gif"  alt="Текст"/>
			<?}elseif($item['mod_type']==2){?>
			<img style="vertical-align:middle" src="/img/pic/web_16.png"  alt="ссылка"/>
			
			<?}?>
			
			<?if($c>0 &&$deep==0){?>
				<a  href="?id=<?=$item['mod_id']?>"><?=$item['mod_name']?></a><!--(<?=$c?>)-->
			<?}else{?>
				<a href="?act=edit&id=<?=$key?>"><?=$item['mod_name']?></a>
			<?}?>
			</td>
			<td><a href="<?=$item['mod_alias']?>"><?=$item['mod_alias']?></a> <?=$item['mod_module_name']?></td>
			<td><?
			$loc=explode('|',$item['mod_location']);
			$locDesc=array();
			foreach ($loc as $l){
				if(isset($location[$l])){
					$locDesc[]=$location[$l];
				}
			}
			echo implode(', ',$locDesc);
			
			/*if(trim($item['mod_region'])){
				?><br><?=$item['mod_region']?><?
			}*/
			
			?></td>
			<td>
			<small><?=$item['mod_access']?></small>
			</td>
			<td style="width:100px">

		<a href="?act=edit&id=<?=$key?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/></a>
	
		<a href="?act=edit&parent=<?=$key?>" rel="<?=$key?>" class="add_to"><img src="/img/pic/add_16.gif" title="Добавить подраздел" alt="Добавить подраздел"/></a>
			
		<a href="#" onclick="return false"><img class="move" src="/img/pic/move_16.gif" title="Переместить" alt="Переместить" /></a>
		<?/*a class="scrooll ajax" href="?method=setPosition&move=up&id=<?=$key?>"><img src="/img/pic/up_16.gif" title="На одну позицию выше" alt="На одну позицию выше"/></a>
		<a class="scrooll ajax" href="?method=setPosition&move=down&id=<?=$key?>"><img src="/img/pic/down_16.gif" title="На одну позицию ниже" alt="На одну позицию ниже"/></a*/?>
		
		<?if($item['hide']!=1){?>
		<a href="?act=hide&id=<?=$key?>&mode=1"  onclick="return confirm('Скрыть <?=$item['mod_name']?>?')"><img src="/img/pic/trash_16.gif" title="Скрыть" border="0" alt=""/></a>
		<?}else{?>
		<a href="?act=hide&id=<?=$key?>&mode=0"><img src="/img/pic/redo_16.gif" title="Открыть" border="0" alt=""></a>
		<?}?>
		
		<?if($component->isSu()){?>
		<a href="?act=remove&id=<?=$key?>"><img src="/img/pic/del_16.gif" title="Удалить" border="0" alt="" onclick="return confirm('Удалить <?=$item['mod_name']?>?')"/></a>
		
		<?}?>
			</td></tr><?
			if(isset($item['ch']) && $item['ch']){
				displayModule($item['ch'],$location,$component,$deep+1);
			}
			
		}
	}

?>
<form id="modules">
<a href='?act=edit<?=($get->exists('id')?'&id='.$get->getInt('id'):'')?>' class="add_to" rel="0"><img style="vertical-align:middle" src="/img/pic/add_16.gif" title="Добавить" alt="Добавить"/>Добавить</a>
|
<a href="/admin/enum/redirect/"><img style="vertical-align:middle" src="/img/pic/web_16.png"  alt="ссылка"/> Редирект</a>
<?
		?><table class="grid"><?
		?><tr>
		<th></th>
		<th>Название</th><th>Псевдоним</th><th>Расположение</th><th>Доступ</th><th>Действия</th></tr><?

		displayModule($module,$location,$this);
		?></table>
<a href='?act=edit<?=($get->exists('id')?'&id='.$get->getInt('id'):'')?>' class="add_to" rel="0"><img style="vertical-align:middle" src="/img/pic/add_16.gif" title="Добавить" alt="Добавить"/>Добавить</a>
		
		</form>
<script type="text/javascript" src="/core/component/modules/modules.js"></script>