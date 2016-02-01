<form method="POST" enctype="multipart/form-data" action="?act=submit">
<div>
	<div class="explorer"><?=$this->renderFileExplorer()?></div>
	
	<?if($mode=='img'){?>
		<?foreach ($rs as $v){?>
			<?if(!$v['is_dir']){?>
			<img src="/<?=scaleImg($v['path'],'h100')?>">
			<?}?>
		<?}?>
	<?}?>
	<button class="button" type="button" name="remove">Удалить</button>
	<table class="grid">
		<tr><th></th><th></th><th>Имя</th><th>Размер</th><th>Права</th><th>Создан</th><th>Изменён</th><th></th></tr>
		<?foreach ($rs as $v){?>
			<tr title="<?=$v['name']?>">
			<td>
			<input name="item[]" type="checkbox" value="<?=$v['full_path']?>">
			
			</td>
				<td><?if($v['is_dir']){?><img src="/img/pic/foldr_16.gif"/><?}else{?><img src="/img/pic/docs_16.gif"/><?}?>
				
				</td>
				<td style="font-weight:bold" class="file-name">
					<?if($v['is_dir']){?>
						
						<a href="?path=<?=$v['path']?>" title="<?=$v['full_path']?>"><?=$v['name']?></a>
					<?}else{?>
						<?=$v['name']?>
					<?}?>
				</td>
				<td><?=$v['size']?></td>
				<td><?=$v['perm']?></td>
				<td><?=$v['create']?></td>
				<td><?=$v['modify']?></td>
				<td style="text-align:right">
					<!--<a  href="?act=downloadGZ&path=<?=urlencode($v['path'])?>&name=<?=$v['name']?>" title="Скачать <?=$v['full_path']?>">
						GZ
					</a>-->					
					<a  href="?act=download&path=<?=urlencode($v['path'])?>&name=<?=$v['name']?>" title="Скачать <?=$v['full_path']?>">
						D
					</a>
				
					<a class="archive" href="?act=CreateArchive&path=<?=urlencode($v['path'])?>&name=<?=$v['name']?>" title="<?=$v['full_path']?>">
						A
					</a>
					<?if(preg_match('/\.(php|htaccess|txt|html|htm|js|css|log)$/i',$v['path'])){?>
					<a class="scrooll" href="?act=edit&path=<?=urlencode($v['path'])?>" title="<?=$v['full_path']?>">
						<img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/>
					</a>
					<?}?>
					<a class="remove" href="?act=remove&path=<?=urlencode($v['path'])?>">
						<img src="/img/pic/trash_16.gif" title="Удалить" alt="Удалить"/>
					</a>
					<?if($v['is_dir'] && preg_match('/(tmp)$/i',$v['path'])){?>|
					<a class="removeAll" href="?act=removeAll&path=<?=urlencode($v['path'])?>">
						<img src="/img/pic/trash_16.gif" title="Удалить содержимое папки" alt="Удалить">
					</a>
					<?}?>
				</td>
			</tr>
		<?}?>
	</table><br/>
	<button class="button" type="button" name="remove">Удалить</button>
		<input type="hidden" name="path" value="<?=$path?>"/>
		<input type="hidden" name="mode" value="<?=$mode?>"/>
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
		<table class="form">
		<tr><th>Создать директорию : </th><td><input name="dirname" class="input-text"/></td><td><input type="submit" id="mkdir" name="create" class="button" value="Создать"/></td> </tr>
		<tr><th>Залить файлы : </th>
			<td><input id="userfile" class="input-text multi" type="file" name="userfile[]" multiple>
			</td>
			<td>
			
			<input type="submit" name="upload" class="button" value="Загрузить"/>
			</td>
		</tr>
		
		
		</table>
		<?if($from){?>
		<a href="<?=$from?>">Закрыть</a>
		<?}?>
		
	
	<br/>

</div>
</form>

<script type="text/javascript" src="/core/component/files/files.js"></script>



