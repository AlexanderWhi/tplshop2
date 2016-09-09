var cloneImg = function (img, i) {
    var tpl = $('#img-tpl').clone().removeAttr('id').show();
    $('img', tpl).attr('src', util.scaleImg(img, 'w200h200'));
    $('.sort', tpl).val($('.sort').length);
    $('[type=hidden]', tpl).val(img);
    $('input', tpl).each(function () {
        this.name = this.name.replace(/_$/, '[]');
    });
    return tpl;
};

_upload = function (resp) {
    $('#preloader').hide();
    for (var i in resp.paths) {
        cloneImg(resp.paths[i]).appendTo('#img-list');
    }
};

var acPropName = function () {

    var grp = $('.pgrp', $(this).parent().parent());
//		alert(grp.val())
    $(this).autocomplete({
        source: function (request, response) {

            request.grp = grp.val();
            $.getJSON("/autocomplete/autocomplete_sh_prop.php", request, response);
        },
        minLength: 1
    });
};




function addNmn() {
    var data = $('#nmn tfoot tr').clone();

    $('[name]', data).each(function () {
        this.name = this.name.replace(/_$/, '[]');

    });
    $('#nmn  tbody').append(data);
}

function addProp() {
    var el = $('#add_prop tfoot tr').clone();

    $('[name=new_prop_name_]', el).each(acPropName);

    $('[name]', el).each(function () {
        $(this).attr('name', $(this).attr('name').replace(new RegExp('_$'), '[]'));

    });
    $('.remove', el).click(function () {
        $(this).parent().parent().remove();
        return false;
    });
    el.appendTo($('#add_prop tbody'));

}
$(function () {
    $("#tb").tabs();
    if (getCookie('TabsView') == 1) {
        $('#tabs_view').attr('checked', true);
    }
    //var tab_init=false;
    var changeTabsView = function () {
        if ($('#tabs_view').is(':checked')) {
            $("#tb").tabs();
            //tab_init=true
            setCookie('TabsView', 1);
        } else {
            setCookie('TabsView', 0);
            //if(tab_init){
                $("#tb").tabs('destroy');
            //}
            
        }
    };
    $('#tabs_view').click(function () {
        changeTabsView();
    });
    changeTabsView();
    $('[name=save],[name=save_as_new]').click(function () {
        $('#preloader').show();
        $.post(this.form.action, $(this.form).serialize() + '&mode=' + $(this).attr('name'), function (res) {
            $('#preloader').hide();
            if (res.id) {
                $('[name=id]').val(res.id);
                $('#data_id').text(res.id);
            }
            msg(res.msg);
        }, 'json');
        return false;
    });

    $('[name=clear]').click(function () {
        $('[name=img]').val('clear');
        $('#img-file').attr('src', '/img/admin/no_image.png');
    });

//	$('[name=upload]').upload({params:'resize=false&size=w200h200'});


    $('.add').click(function () {
        var data = $('#prop tfoot tr').clone();
        data.find('[name=pname_]').attr('name', 'pname[]').each(acPropName);
        data.find('[name=pvalue_]').attr('name', 'pvalue[]');
        data.find('[name=pgrp_]').attr('name', 'pgrp[]');
        $('#prop tbody').append(data.show());
        return false;
    });

//	$('#prop .del').live('click',function(){
//		var data=$('.pvalue',$(this).parent().parent()).val('');
//		return false;
//	});

    $('#img-list .img_del').bind('click', function () {
        $(this).parent().remove();
    });

    var pgrp_html = '';

    for (var i in PROP_GRP) {
        pgrp_html += '<option value="' + i + '">' + PROP_GRP[i] + '</option>';
    }

    $('.pgrp').html(pgrp_html).each(function () {
        $(this).val($(this).attr('rel'))
//		alert($(this).attr('rel'))

    });


    $('.pvalue').each(function () {
        var propel = $('.pname', $(this).parent().parent());
        $(this).autocomplete({
            source: function (request, response) {
                var prop = propel.val();
                request.prop = prop;
                $.getJSON("/autocomplete/autocomplete_sh_prop_val.php", request, response);
            },
            minLength: 2,
            extraParams: {'test': '111'}
        });

    });

    $('.pname').each(acPropName);



    $('#relation').change(function () {
        var data = {
            type: $(this).val(),
            goods_id: $('[name=id]', this.form).val()
        };
        $.post('?act=getRelation', data, function (res) {
            var html = '';
            for (var i in res) {
                html += '<tr>';
                html += '<td><input type="checkbox" name="rel_item[]" value="' + res[i].id + '"></td>';
                html += '<td>';

                if (res[i].img) {
                    html += '<img src="' + res[i].img + '">';
                }

                html += '</td>';
                html += '<td>' + res[i].name + '</td>';
                html += '<td>' + res[i].cname + '</td>';
                html += '</tr>';
            }
            $('#relation_container').html(html);
        }, 'json');
    });
    $('[name=relation_remove]').click(function () {

        $.post('?act=removeRelation', $(this.form).serialize(), function (res) {

            util.msg(res.msg);

        }, 'json');
        return false;
    });

    

    $('[name^=upload]').upload({params: 'resize=false'});

    for (var i in IMG_LIST) {
        cloneImg(IMG_LIST[i], i).appendTo('#img-list');
    }

    $('[name=img_del]').click(function () {
        $('#img-list :checked').parent().remove()
        return false;
    });




    $('.add').click(function () {
        var data = $('tfoot tr').clone();
//		alert(data.html())
        data.find('[name=field_value_]').attr('name', 'field_value[]');
        data.find('[name=value_desc_]').attr('name', 'value_desc[]');
        data.find('[name=position_]').attr('name', 'position[]');
        $('#enum-form table tbody').append(data.show());
        return false;
    });

//	$('#goods-props').on('click','.del',function(){
//		var data=$(this).parent().parent().remove();
//		return false;
//	});


});
