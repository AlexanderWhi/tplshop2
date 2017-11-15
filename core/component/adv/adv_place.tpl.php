<div>
    <form method="POST">
        <button class="button" name="apply">Применить</button>
        <? $this->displayPageControl('plEdit'); ?>
        <? if ($rs->getCount()) { ?>
            <table class="grid">
                <tr>
                    <th>Идентификатор</th>
                    <th>Описание</th>
                    <th>Ширина</th>
                    <th>Высота</th>
                    <th></th>
                </tr>
                <? while ($rs->next()) { ?>
                    <tr>
                        <td style="width:200px">
                            <img src="/img/pic/opnbr_16.gif" title="<?= my_htmlspecialchars($rs->get("description")); ?>" alt="<?= my_htmlspecialchars($rs->get("description")); ?>"/> 
                            <a href="?act=plEdit&id=<?= $rs->get('id'); ?>"><strong>[<?= $rs->get("id"); ?>]</strong></a>
                        </td>
                        <td>
                            <small>[<?= my_htmlspecialchars($rs->get("description")); ?>]</small>
                        </td>
                        <td><input class="num" name="width[<?= $rs->get('id'); ?>]" value="<?= $rs->get('width') ?>"></td>
                        <td><input class="num" name="height[<?= $rs->get('id'); ?>]" value="<?= $rs->get('height') ?>"></td>
                        <td style="width:100px">
                            <a href="?act=plEdit&id=<?= $rs->get('id'); ?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/></a>
                            <a onclick="return confirm('Вы действительно хотите удалить запись?')" href="?act=plRemove&id=<?= $rs->get('id'); ?>"><img src="/img/pic/trash_16.gif" title="Удалить запись" border="0" alt=""/></a>
                        </td>
                    </tr>	
                <? } ?>
            </table>
            <? $this->displayPageControl('plEdit'); ?>
        <? } else { ?>
            <div>Список пуст</div>
        <? } ?>
    </form>
</div>
<script type="text/javascript" src="/core/component/adv/adv_place.js"></script>