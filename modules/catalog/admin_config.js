$(function () {
    $('form').submit(function () {
        $('#preloader').show();
        $.post(this.action, $(this).serialize(), function (res) {
            $('#preloader').hide();
            util.msg(res.msg);
        }, 'json');
        return false;
    });
});	