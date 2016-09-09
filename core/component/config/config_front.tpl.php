<form method="POST" id="front-form" action="?act=frontSave">
    <table class="form">
        <tr>
            <td>лого (260x60)</td>
            <td>
                <?if($logo){?>
                <img src="<?=$logo?>"><br>
                <?}?>
                <input type="file" name="logo">
                <label><input type="checkbox" name="clear_logo"> По умолчанию</label>
            </td>
        </tr>
        <tr>
            <td>Favicon (64x64)</td>
            <td>
                <?if($favicon){?>
                <img src="<?=$favicon?>"><br>
                <?}?>
                <input type="file" name="favicon"> 
                <label><input type="checkbox" name="clear_favicon"> По умолчанию</label>
            </td>
        </tr>
        <tr>
            <td>Пустышка (100x100 - 620x360)</td>
            <td>
                <?if($no_img){?>
                <img src="<?=$no_img?>"><br>
                <?}?>
                <input type="file" name="no_img">
                <label><input type="checkbox" name="clear_no_img"> По умолчанию</label>
            </td>
        </tr>
        <tr>
            <td>Штамп (100x100 - 620x360)</td>
            <td>
                <?if($stamp_img){?>
                <img src="<?=$stamp_img?>"><br>
                <?}?>
                <input type="file" name="stamp_img">
<!--                <label><input type="checkbox" name="clear_stamp_img"> По умолчанию</label>-->
            </td>
        </tr>
    </table>
    <br/>
    <input type="submit" value="Сохранить" class="button"/>	
</form>
<script type="text/javascript" src="/core/component/config/config.js"></script>