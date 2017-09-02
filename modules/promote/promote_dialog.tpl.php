<div id="dialog-promote" title="Установка назначить промоакцию" style="display:none">
    <form>
        <select name="promote_id">

            <? foreach (LibPromote::getPromoteList() as $row) { ?>
                <option value="<?= $row['id'] ?>"><?= $row['id'] ?>-<?= $row['create'] ?></option>
            <? } ?>
        </select>

    </form>
</div>
<script src="/modules/promote/admin_promote_goods.js"></script>