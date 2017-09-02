$(function(){
    
    $("#dialog-promote").dialog({
        modal: false,
        autoOpen: false,
        height: 200,
        resizable: false,
        buttons: {
            '���������': function () {
                var data = $('#goods_item').serialize() + '&' + $("#dialog-promote form").serialize();
//                alert(data)
                $.post('/admin/promote/?act=relate', data, function (res) {
                    $("#dialog-promote").dialog('close');
                    util.msg('��������� ');
                }, 'json');
            },
            '�������': function () {
                $(this).dialog("close");
            }
        }
    });
    
    $('[name=promote]').click(function () {
        if ($('input.item:checked').length == 0) {
            alert('�� ������� �� ������ ��������');
            return;
        }
        $("#dialog-promote").dialog('open');
    });
    
    
});