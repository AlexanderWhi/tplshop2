<?
global $offer;
$offer['count'] = $count;
$offer['counts'] = $counts;

function displayCatalog($catalog, $deep, $mod_uri) {
    global $CONFIG, $offer, $count, $counts;
    foreach ($catalog as $item) {
        ?>
        <tr id="item<?= $item['id'] ?>">
            <td>
                <input name="item[]" value="<?= $item['id'] ?>" type="checkbox">

            </td>
            <td style="padding-left:<?= $deep * 20 ?>px;">

                <a href="<?= $mod_uri ?>CatalogEdit/?id=<?= $item['id'] ?>">
        <?= $item['name'] ?></a>
            </td>
            <td><?= $item['id'] ?></td>
            <td>
                <a href="<?= $mod_uri ?>goods/?category=<?= $item['id'] ?>">
        <?= intval(@$offer['count'][$item['id']]) ?>
                </a>
                <?if(Cfg::get('GOODS_FIELD_CATALOGS')){?>
                /<a href="<?= $mod_uri ?>goods/?category=<?= $item['id'] ?>">
        <?= intval(@$offer['counts'][$item['id']]) ?>
                <?}?>
                </a>
            </td>
            <td><input name="sort[<?= $item['id'] ?>]" value="<?= $item['sort'] ?>" style="width:50px"></td>
            <td><input name="main_sort[<?= $item['id'] ?>]" value="<?= $item['main_sort'] ?>" style="width:50px"></td>
            <td>
                <input type="checkbox" name="export[]" value="<?= $item['id'] ?>" <? if ($item['export'] == 1) { ?>checked<? } ?>>
            </td>
            <td style="text-align:right">
                <a class="add_to" rel="<?= $item['id'] ?>" href="<?= $mod_uri ?>CatalogEdit/?parent=<?= $item['id'] ?>"><img src="/img/pic/add_16.gif" title="Добавить подраздел" alt="Добавить подраздел"/></a>

                <a href="<?= $mod_uri ?>CatalogEdit/?id=<?= $item['id'] ?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/></a>

                <a href="?act=onRemove&id=<?= $item['id'] ?>"><img src="/img/pic/trash_16.gif" title="Удалить" border="0" alt="" onclick="return confirm('Удалить <?= $item['name'] ?>?')"/></a>
            </td></tr><?
        if (isset($item['children'])) {
            displayCatalog($item['children'], $deep + 1, $mod_uri);
        }
    }
}
?>

<form id="catalog-form" method="POST" action="?act=saveCatalogSort" >

    <table class="form">

        <tr>
            <td>
                <a class="add_to" rel="0" href="<?= $this->mod_uri ?>CatalogEdit/"><img style="vertical-align:middle" src="/img/pic/add_16.gif" title="Добавить" alt="Добавить"/>Добавить</a>
                | <a href="/admin/catalog/goods/">Все товары</a> | <a href="/srv/yml.php">YML</a> | <a href="/admin/catalog/config/">Настройки</a>
            </td>
            <td style="text-align:right"><button class="button" type="submit" name="save">Применить</button>
                <button class="button" type="submit" name="remove">Удалить</button>
<? if ($this->isSu()) { ?>
                    <button class="button_long" type="submit" name="UpdateCatalogCache">Обновить кеш</button>
                <? } ?>

            </td>
        </tr>

    </table>
    <br>
    <table class="grid">
        <thead>
            <tr><th></th><th><?= $this->sort('name', 'Название') ?></th><th><?= $this->sort('id', 'Идентификатор') ?></th><th>товаров</th><th><?= $this->sort('sort', 'Позиция') ?></th><th><?= $this->sort('main_sort', 'Позиция на главной') ?></th><th><input type="checkbox" id="export_all"> <?= $this->sort('export', 'Экспорт') ?></th></tr>
        </thead>
        <tbody>
<? displayCatalog($catalog, 0, $this->mod_uri) ?>
        </tbody>
    </table>
</form>

<script type="text/javascript" src="/modules/catalog/admin_catalog.js"></script>