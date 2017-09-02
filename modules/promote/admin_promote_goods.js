$(function(){
    
    $("#dialog-promote").dialog({
        modal: false,
        autoOpen: false,
        height: 200,
        resizable: false,
        buttons: {
            'Применить': function () {
                var data = $('#goods_item').serialize() + '&' + $("#dialog-promote form").serialize();
//                alert(data)
                $.post('/admin/promote/?act=relate', data, function (res) {
                    $("#dialog-promote").dialog('close');
                    util.msg('Обновлено ');
                }, 'json');
            },
            'Закрыть': function () {
                $(this).dialog("close");
            }
        }
    });
    
    $('[name=promote]').click(function () {
        if ($('input.item:checked').length == 0) {
            alert('Не выбрано ни одного элемента');
            return;
        }
        $("#dialog-promote").dialog('open');
    });
    
    
});