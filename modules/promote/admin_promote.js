$(function () {
//alert();
    $('#promote-form').submit(function () {

        $('#preloader').show();
        $.post(this.action, $(this).serialize(), function (res) {
            document.location.reload();
            $('#preloader').hide();
        }, 'json');
        return false;
    });

    $('.remove').click(function () {
        if (confirm('Удалить запись?')) {
            $('#preloader').show();
            $.get(this.href, function (res) {
                document.location.reload();
                $('#preloader').hide();
            }, 'json');
        }
        return false;
    });
});