var addr = function (a) {

    var city = a.replace(new RegExp('^([^\,]+), (.+)'), '$1');
    var addr = a.replace(new RegExp('^([^\,]+), (.+)'), '$2');

    $('#order [name=address]').val(addr);
    $('#order [name=city]').val(city).change();



    $('#basket-form [name=address]').keyup();
}


var tab = function (group, itm) {
    $('.' + group).removeClass('act');
    $('.' + itm).addClass('act');
}

var catalog = {
    id: 0,
    sendOrder: function () {
        var data = $(this.form).serialize();
        $(this).attr('disabled', true);
        $('.error', this.form).hide();
        $.post('?act=sendOrderSimple', data, catalog._sendOrder, 'json');
    },
    _sendOrder: function (res) {
        $('#basket-form [name=send]').attr('disabled', false);
//		alert(res.error)
        if (res.error) {
            var error = res.error;
            for (var i in error) {
//				alert(error[i])
                if (error[i]) {
                    $('#error-' + i).text(error[i]).css('display', 'block');
                }
            }
        } else if (res.id) {
            var msg = $('#simple_order_success').text().replace('{num}', res.id)
            $('#simple_order_success').text(msg).show();
            $('#basket-form').hide();

        }
    }
}


var expandOrder = function () {
    $('#order').show();
}

var openLoginForm2 = function () {
    $('#block-logon').show();
    $('#block-logon [name=login]').focus();
    $('.order_logon').hide();
}
var orderSignUp = function () {
//	$('#block-logon').show();
    $('[name=phone]').focus();
    $('.order_logon').hide();
}


var order_but = $('[name=order]');
var logon_but = $('[name=logon]');

var bsort = function (ord, sort) {
    $('[name=ord]').val(ord);
    $('[name=sort]').val(sort);
    shop.refresh();
}

$(function () {

//	$('.bsort').live('click',function(){
//		
//		return false;
//	})



    var rendTime = function () {
        var dte = $('[name=date]').val();
        if (dte) {
            dte = dte.split('.');
        } else {
            return;
        }

        dte = dte[1] + '/' + dte[0] + '/' + dte[2]
        $('[name=time] option').each(function () {
            var tme = dte + ' ' + $(this).attr('value') + ':00:00';
            if (Date.parse(tme) / 1000 < CUR_TIME) {
                $(this).css('background', 'red');
                if ($(this).is(':selected')) {
                    $(this).parents('.select').addClass('err')
                }
            } else {
                $(this).css('background', 'none');
                if ($(this).is(':selected')) {
                    $(this).parents('.select').removeClass('err')
                }
            }
//			alert(tme)
//			alert(Date.parse(tme))
        });


    };
    rendTime();

    $('[name=date],[name=time]').change(function () {
        rendTime();
    });


    tab('step', 'step1');


    shop.bindCount($('#basket-form input.count'));

    $('#basket-form [name=send]').click(catalog.sendOrder);


    $('#basket-form input').focus(function () {
        $('.order_logon .notice').hide();

    });
    $('#basket-form').find('input,select,textarea').live('change', function () {
        $.post('?act=saveOrder', $(this.form).serialize(), function () {
        });

    });


    /*Оформление заказа*/
    $('[name=want_reg]').click(function () {
        $('#want_reg_tab').toggle(this.checked)
    });
    $('[name=auto_pass]').click(function () {
        $('#auto_pass_tab').toggle(!this.checked)
    });

    $('#chb_logon').click(function () {
        $('#block-logon').toggle(this.checked);
        $('#want_reg_tab').toggle(!this.checked);
        $('[name=login]').focus()
    });
    $('#reg_1').click(function () {

        $('#block-logon').toggle(!this.checked);
        $('#want_reg_tab').toggle(this.checked);
        $('#auto_pass_lab').toggle(this.checked);
        $('[name=reg_login]').focus()
    });
    $('#reg_-1').click(function () {
        $('#block-logon').toggle(!this.checked);
        $('#want_reg_tab').toggle(this.checked);
        $('#auto_pass_lab').toggle(!this.checked);
        $('[name=reg_login]').focus()
    });
    $('#reg_0').click(function () {

        $('#block-logon').toggle(!this.checked);
        $('#want_reg_tab').toggle(!this.checked);
        $('[name=name]').focus()
    });

    $('input.date').attachDatepicker();

    logon_but.click(function () {

        $('#error-logon').hide()
        $.post('?act=logon', $(this.form).serialize(), function (res) {
            if (res.msg) {
                $('#error-logon').text(res.msg).show();
            } else {
                for (var i in res) {
                    $('[name=' + i + ']').val(res[i]);
                }
                $('#block-logon-change').remove();
            }

        }, 'json');
        return false;
    });

    order_but.click(function () {

//		if(!$('#agreement').attr('checked')){
//			alert('Вы не приняли условия');
//			return false;
//		}

        var form = this.form;
        var data = $(this.form).serialize();
        $('input.error,select.error,textarea.error').removeClass('error');
        $('.error').hide();

        var order_but_label = order_but.text();

        $(order_but).text($(order_but).attr('alt'))//.attr('disabled',true);
        $.post("?act=sendOrder", data, function (res) {
            if (res.error) {
                var err_txt = '';
                for (var i in res.error) {
                    var el = $('[name=' + i + ']', form);
                    if (el.length > 0) {
                        $('#error-' + i).html(res.error[i]).show();
                        el.addClass('error');
                    }else{
                       err_txt+= res.error[i]+'<br>';
                    }

                }
                if(err_txt){
                    $.alert(err_txt);
                }
                //$('.step').removeClass('act');
                var first = $('input.error:eq(0)', form);
//				alert(first.attr('name'))

                //var tab_id = first.parents('.step').attr('id');
                //tab('step', tab_id);
                first.focus();

                $(order_but).text(order_but_label).attr('disabled', false);

            } else if (res.id) {
                $('#order-form').hide();
                $('#order-content-bar').hide();
                var or = $('#order-report').show()

                var html = or.html();

                html = html.replace('{order_num}', res.order_num)
                        .replace('{order_num1}', res.order_num)
                        .replace('{order_count}', res.count)
                        .replace('{ps_href}', res.ps_href)
                        .replace('{order_delivery}', res.delivery)
                        .replace('{order_total_price}', res.total_price);
                or.html(html);

                document.location.href = '#';

                if (res.redirect_href) {
                    setTimeout(function () {
                        document.location.href = res.redirect_href;
                    }, 10);
                }
            }
        }, 'json');

        return false;
    });
    //Платёжная система (15,11,2012)
    $('.IncCurrLabel').click(function () {
        var nm = this.href.replace(/.*#/, '');
        $('.IncCurrLabel').removeClass('act');
        $(this).addClass('act');
        $('[name=IncCurrLabel]').val(nm);
        $('#pay_system_3').attr('checked', true);
        return false;
    });

    $('[name=delivery_type]').change(function () {
        shop.refresh();
    });


    $('[name=is_jur]').click(function () {
        $('#block-jur').toggle(this.checked)
    });

    $('#is_receiver').click(function () {
        $('#receiver').toggle(!$(this).attr('checked'))
    });

    if (!!window.ymaps) {
        ymaps.ready(function () {


            header_ymap_init();
            var t;

            $('#basket-form [name=city]').change(function () {
                $('#basket-form [name=address]').keyup();
            });


            $('#basket-form [name=address]').keyup(function () {
                var q = $('#basket-form [name=city]').val() + ', ' + $('#basket-form [name=address]').val();
                //			alert(q)
                $('#comment-address').hide();
                clearTimeout(t);
                t = setTimeout(function () {



                    var _tmp = function () {
                        // Геокодер возвращает результаты в виде упорядоченной коллекции GeoObjectArray
                        return function (res) {
                            var point = res.geoObjects.get(0);
                            var data = [];
                            var it = 0;
                            if (ZONE_DATA.length > 0) {
                                for (var i in ZONE_DATA) {

                                    var myPolygon = new ymaps.Polygon(
                                            ZONE_DATA[i].Coordinates, {},
                                            {}
                                    );
                                    myPolygon.zone_id = ZONE_DATA[i].zone_id;
                                    headerMyMap.geoObjects.add(myPolygon);
                                    data[i] = myPolygon;
                                }
                            }
                            var result = ymaps.geoQuery(data);
                            //					alert(q+' '+data.length);
                            var objectsContainingPolygon = result.searchContaining(point);
                            var zone = 0;
                            objectsContainingPolygon.each(function (res) {
                                zone = res.zone_id;
                            });
                            $('#basket-form [name=delivery_zone]').val(zone);
                            shop.refresh();
                            //						alert(zone)
                            //					$.get('/ymap/?act=GetDescrByZone&id='+zone,function(res){
                            //						$('#calc-delivery .res').html(res);
                            //					});
                        };
                    };
                    ymaps.geocode(q).then(_tmp());
                }, 500);
            });

            $('#basket-form [name=address]').keyup();

        });
    }


});	