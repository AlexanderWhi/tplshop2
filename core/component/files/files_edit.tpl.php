<form method="POST">
<input type="hidden" name="path" value="<?=$path?>"/>
<div><strong><?=$path?></strong></div>
<textarea style="width:100%;height:400px" name="content"><?=$content?></textarea>
<input class="button" value="Сохранить" type="submit" name="save"/> 
<input class="button" type="submit" value="Закрыть" name="close"/>
</form>
<script type="text/javascript" src="/core/component/files/files_edit.js"></script>