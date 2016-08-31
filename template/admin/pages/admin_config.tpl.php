<?

function __render_config($config) {
    foreach ($config as $k => $node) {
        if (isset($node['group'])) {
            ?><? if (!empty($node['name'])) { ?><h4><?= $node['name'] ?><? } ?></h4><?
            __render_config($node['group']);
            ?><hr /><?
        } else {
            ?>
            <div>
                <label><?= @$node['name'] ?></label><br>
                <? if (@$node['type'] == ModelConfig::TYPE_BOOL) { ?>
                    <input type="checkbox" name="<?= $k ?>" value="1" <? if (@$node['value']) { ?>checked=""<? } ?>>
                    <? } elseif (ModelConfig::TYPE_ENUM) { ?>
                    <select class="input-text" name="<?= $k ?>">
                        <? foreach ($node['enum'] as $v => $d) { ?>
                        <option value="<?=$v?>" <? if (@$node['value'] == $v) { ?>selected<? } ?>><?= $d ?></option>
                    <? } ?>
                    </select>
                <? } else { ?>
                    <input class="input-text" name="<?= $k ?>" value="<?= @$node['value'] ?>">
            <? } ?>

            </div>
            <?
        }
    }
}
?>
<form action="?act=saveConfig" class="config-form" method="POST">
    <? /* pre><?  print_r($config->getConfig())?></pre */ ?>
<? __render_config($config->getConfig()) ?>
    <button type="submit" class="button">Сохранить</button>
</form>