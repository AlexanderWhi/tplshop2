<form method="POST" id="front-form" action="?act=frontSave">
    <table class="form">
        <tr>
            <td>����</td>
            <td>
                <?if($logo){?>
                <img src="<?=$logo?>"><br>
                <?}?>
                <input type="file" name="logo">
                <label><input type="checkbox" name="clear_logo"> �� ���������</label>
            </td>
        </tr>
        <tr>
            <td>Favicon</td>
            <td>
                <?if($favicon){?>
                <img src="<?=$favicon?>"><br>
                <?}?>
                <input type="file" name="favicon"> 
                <label><input type="checkbox" name="clear_favicon"> �� ���������</label>
            </td>
        </tr>
    </table>
    <br/>
    <input type="submit" value="���������" class="button"/>	
</form>
<script type="text/javascript" src="/core/component/config/config.js"></script>