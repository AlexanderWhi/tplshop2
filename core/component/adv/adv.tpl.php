<div>
    <form method="POST">

        <? $this->displayPageControl(); ?>
        <? if ($rs->getCount()) { ?>
            <table class="grid">
                <tr>
                    <th></th>
                    <th>Описание</th>
                    <th>Дата начала</th>
                    <th>Дата конца</th>
                    <th>Место</th>
                    <th>Показов</th>
                    <th>Кликов</th>
                    <th>Действие</th>
                </tr>
                <? while ($rs->next()) { ?>
                    <tr>
                        <td style="width:200px">
                            <? if (isImg($rs->get('file'))) { ?>
                                <img src="<?= scaleImg($rs->get('file'), 'w200h100') ?>">
                            <? } ?>

                        </td>
                        <td style="width:200px">
                            <a href="?act=edit&id=<?= $rs->get('id'); ?>"><small>[<?= my_htmlspecialchars($rs->get("description")); ?>]</small></a>
                        </td>
                        <td><?= $rs->get("start_date"); ?></td>
                        <td><?= $rs->get("stop_date"); ?></td>
                        <td>[<?= $rs->get("place"); ?>] <?= $rs->get("pl_description"); ?></td>
                        <td><?= $rs->get("show_ad"); ?></td>
                        <td><?= $rs->get("click"); ?></td>
                        <td style="width:100px">
                            <a class="scrooll" href="?act=edit&id=<?= $rs->get('id'); ?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/></a>
                            <a class="scrooll " onclick="return confirm('Сброс?')" href="?act=reset&id=<?= $rs->get('id') ?>"><img src="/img/pic/undo_16.gif" title="Сброс" alt="Сброс"/></a>
                            <a class="scrooll" onclick="return confirm('Вы действительно хотите удалить запись?')" href="?act=remove&id=<?= $rs->get('id'); ?>"><img src="/img/pic/trash_16.gif" title="Удалить запись" border="0" alt=""/></a>
                        </td>
                    </tr>	
                <? } ?>
            </table>
            <? $this->displayPageControl(); ?>
        <? } else { ?>
            <div>Список пуст</div>
        <? } ?>
    </form>
</div>