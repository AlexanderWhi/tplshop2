<form method="POST" id="promote-form" action="?act=create">
    <table class="grid">
        <tr><th>id</th><th>Время</th><th>Скидка</th><th>товаров</th><th>кодов</th><th>использовано</th><th>Действие</th></tr>
        <? foreach ($rs as $row) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><strong><?= $row['create'] ?></strong></td>
                <td><?= $row['discount'] ?></td>
                <td><?= $row['rel'] ?></td>
                <td><a download="" href="?act=import&id=<?= $row['id'] ?>" title="Скачать"><?= $row['code'] ?></a></td>
                <td><?= $row['used'] ?></td>
                <td>
                    <a class="remove" href="?act=remove&id=<?= $row['id'] ?>"><img src="/img/pic/trash_16.gif" title="Удалить" alt="Удалить"/></a>
                </td>
            </tr>	
        <? } ?>
    </table>
    <table class="form">
        <tr>
            <td>Скидка %</td>
            <td><input name="discount" type="tel" class="input-text" value="5"></td>
        </tr>
        <tr>
            <td>Количество</td>
            <td><input name="n" type="tel" class="input-text" value="100"></td>
        </tr>
        <tr>
            <td>Длина кода</td>
            <td><input name="length" type="tel" class="input-text" value="6"></td>
        </tr>
    </table>
    <button type="submit">Создать</button>
</form>

<script type="text/javascript" src="/modules/promote/admin_promote.js"></script>