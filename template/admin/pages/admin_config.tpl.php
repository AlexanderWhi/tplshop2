<?
function __render_config($config){
    foreach ($config as $k=>$node){
        if(isset($node['group'])){
            ?><?if(!empty($node['name'])){?><h4><?=$node['name']?><?}?></h4><?
            __render_config($node['group']);
            ?><hr /><?
        }else{
            ?>
            <div>
                <label><?=@$node['name']?></label><br>
                <input class="input-text" name="<?=$k?>" value="<?=@$node['value']?>">
            </div>
                <?
        }
    }
}
?>
<form action="?act=saveConfig" class="config-form" method="POST">
    <?/*pre><?  print_r($config->getConfig())?></pre*/?>
    <?  __render_config($config->getConfig())?>
    <button type="submit">Сохранить</button>
</form>