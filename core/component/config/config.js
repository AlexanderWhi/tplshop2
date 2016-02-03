function remove(name) {
    if (confirm('Удалить?')) {
        $.postData('?act=remove&name=' + name, {}, function (html) {
            $('#config tbody').html(html);
        }
        , 'html');
    }
}

$(function () {
    $('.save').click(function () {
        var data = $(this.form).serialize();
        $('#preloader').show();
        $.post('?act=save', data, function (resp) {
            $('#preloader').hide();
            msg(resp.msg);
//			alert(resp.msg);
        }, 'json');

        return false;
    });


    $('[name=add]').click(function () {
        $.postData('?act=add', $(this.form).serialize(), function (res) {
            if (res.html) {
                $('#config tbody').html(res.html);
                var fld = ['name', 'value', 'description'];
                for (var i in fld) {
                    $('[name=' + fld[i] + ']').val('')
                }

            }
        }, 'json');
        return false;
    });

    $('[name=remove]').click(function () {
        if (confirm('Удалить выбранное?')) {
            if ($('[name^=item]:checked').length == 0) {
                alert('Необходимо выбрать элементы');
            } else {
                $.postData('?act=remove', $(this.form).serialize(), function (html) {
                    $('#config tbody').html(html);
                }
                , 'html');
            }
        }

        return false;
    });

    $('#front-form').submit(function () {

        if (!FormData) {
            alert('html5?');
            return false;
        }
        $('#preloader').show();
        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formData = new FormData(this);
        $.ajax({
            url: formURL,
            type: 'POST',
            data: formData,
            dataType :'json',
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data, textStatus, jqXHR)
            {
                msg(data.msg);
                $('#preloader').hide();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
            }
        });
        return false;
    });
});