<form method="POST" id="promote-form" action="?act=create">
    <table class="grid">
        <tr><th>id</th><th>�����</th><th>������</th><th>�������</th><th>�����</th><th>������������</th><th>��������</th></tr>
        <? foreach ($rs as $row) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><strong><?= $row['create'] ?></strong></td>
                <td><?= $row['discount'] ?></td>
                <td><?= $row['rel'] ?></td>
                <td><a download="" href="?act=import&id=<?= $row['id'] ?>" title="�������"><?= $row['code'] ?></a></td>
                <td><?= $row['used'] ?></td>
                <td>
                    <a class="remove" href="?act=remove&id=<?= $row['id'] ?>"><img src="/img/pic/trash_16.gif" title="�������" alt="�������"/></a>
                </td>
            </tr>	
        <? } ?>
    </table>
    <table class="form">
        <tr>
            <td>������ %</td>
            <td><input name="discount" type="tel" class="input-text" value="5"></td>
        </tr>
        <tr>
            <td>����������</td>
            <td><input name="n" type="tel" class="input-text" value="100"></td>
        </tr>
        <tr>
            <td>����� ����</td>
            <td><input name="length" type="tel" class="input-text" value="6"></td>
        </tr>
    </table>
    <button type="submit">�������</button>
</form>

<script type="text/javascript" src="/modules/promote/admin_promote.js"></script>