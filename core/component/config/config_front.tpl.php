<form method="POST" id="front-form" action="?act=frontSave">
    <table class="form">
        <tr>
            <td>лого</td>
            <td>
                <?if($logo){?>
                <img src="<?=$logo?>"><br>
                <?}?>
                <input type="file" name="logo">
                <label><input type="checkbox" name="clear_logo"> По умолчанию</label>
            </td>
        </tr>
        <tr>
            <td>Favicon</td>
            <td>
                <?if($favicon){?>
                <img src="<?=$favicon?>"><br>
                <?}?>
                <input type="file" name="favicon"> 
                <label><input type="checkbox" name="clear_favicon"> По умолчанию</label>
            </td>
        </tr>
    </table>
    <br/>
    <input type="submit" value="Сохранить" class="button"/>	
</form>
<script type="text/javascript" src="/core/component/config/config.js"></script>