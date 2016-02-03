<form method="POST">
    <table class="grid">
        <? foreach ($rs as $row) {
            ?><tr>
                <td title="<?= $row['name'] ?>"><small><?= $row['description'] ?></small></td>
                
                <td>
                    <? if (isset($enum[$row['name']])) { ?>
                        <? if (is_array($value_list = $enum[$row['name']])) {
                            ?><select name="<?= strtolower($row['name']) ?>"><?
                                foreach ($value_list as $v => $d) {
                                    ?><option value="<?= htmlspecialchars($v) ?>" <? if ($row['value'] == $v) { ?>selected<? } ?>><?= $d ?></option><?
                                }
                            }
                            ?>

                        <? } else { ?>
                            <input class="input-text" name="<?= strtolower($row['name']) ?>" value="<?= htmlspecialchars($row['value']) ?>">
    <? } ?>
                        </td>
                        
            </tr><? }
?>
    </table>
    <br/>
    <input type="submit" value="Сохранить" class="button save"/>	
</form>
<script type="text/javascript" src="/core/component/config/config.js"></script>