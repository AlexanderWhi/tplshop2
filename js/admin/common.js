// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    });
}

$.datepicker.regional['ru'];
$.datepicker.setDefaults(
        {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'dd.mm.yy',
            duration: '',
            minDate: new Date(2008, 0, 1),
            maxDate: null
        });
jQuery.fn.upload = function (options) {
    if (!options.cb) {
        options.cb = '_upload';
    }
    if (!options.act) {
        options.action = 'upload';
    }
    $(this).change(function () {
        $('#preloader').show();
        var oldAction = this.form.action
        $(this.form).attr({action: '?act=' + options.action + '&cb=' + options.cb + (options.params ? '&' + options.params : ''), target: 'upload'}).submit().attr('action', oldAction);
        $(this).clone().insertAfter(this).upload(options);
        $(this).remove();
    }).each(function () {
        $('<iframe id="upload" name="upload" style="display:none"></iframe>').insertAfter($(this.form));
    });
    return $(this);
}
function msg(text) {
    $("#dialog-message").dialog('open').find('#dialog-message-text').text(text);
}

jQuery.fn.grid = function () {
//	$("tbody tr",this).hover(
//		function(){	$(this).addClass("tr_hover");},
//		function(){	$(this).removeClass("tr_hover");}
//	);
    $("tbody tr:even", this).addClass('even');
    $("tbody tr:odd", this).addClass('odd');
    return $(this)
}

jQuery.fn.item = function (rel) {
    if (!rel) {
        rel = 'item';
    }
    $(this).click(function () {
//		alert($(this).prop('checked'))
        if ($(this).prop('checked')) {
            $('[name^=' + rel + ']:checkbox').prop('checked', true);
        } else {
            $('[name^=' + rel + ']:checkbox').prop('checked', false);
        }
    });
    return $(this)
}

var util = {
    referer: null,
    msg: function (text) {
        $("#dialog-message").dialog('open').find('#dialog-message-text').text(text);
    },
    openWin: function (url, width, height) {
        var win2;
        var screen_width = window.screen.width;
        var screen_height = window.screen.height;

        width += 40;
        height += 30;

        if (width > screen_width) {
            width = screen_width - 40;
        }
        if (height > screen_height) {
            height = screen_height - 100;
        }
        win2 = window.open(url, 'windetail', 'width=' + width + ',height=' + height + ', top=20, left=20, resizable=1, scrollbars=1');
        win2.focus();
    },
    scaleImg: function (img, params) {
        return img.replace(/([^/]+)\.(jpg|jpeg|png|gif)$/i, '.thumbs/$1_' + params + '.$2');
    }
};

jQuery.postData = function (act, data, cb, type) {
    if (!type) {
        type = 'json';
    }
    $('#preloader').show();
    $.post(act, data, function (resp) {
        $('#preloader').hide();
//		alert(resp)
        if (!!resp) {
            if (resp.msg) {
                util.msg(resp.msg)
            }
            if (resp.err) {
                alert(resp.err);
            }
        }
//		alert(resp)
        cb(resp);
    }, type);
}



$(function () {

    $("#dialog-message").dialog({
        modal: false,
        autoOpen: false,
        width: 400,
        //height:200,
        resizable: false,
        buttons: {
            Ok: function () {
                $(this).dialog("close");
            }
        }
    });

    $('input[name=close],button[name=close]').each(function () {
        if (util.referer == '') {
            $(this).hide();
        }
    }).click(function () {
        if (util.referer == null) {
            window.history.back();
        } else {
            document.location = util.referer;
        }
        return false;
    });

//	$('.grid').grid();

    $('[name=all]:checkbox').item();
});