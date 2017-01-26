jQuery.fn.count = function (option) {
    if ($(this).hasClass('weight')) {
        $(this).attr('title', 'товар весовой, введите вес в килограммах');
    }
    var up = function (el) {
        if ($(el).hasClass('weight')) {
            el.value = (el.value - (-0.1)).toFixed(2);
        } else {
            el.value -= -1;
        }
    };
    var down = function (el) {
        if ($(el).hasClass('weight')) {
            el.value = (el.value - 0.1 > 0 ? (el.value - 0.1).toFixed(2) : '0.10');
        } else {
//			alert(el.value)
            el.value = ((el.value - 1 >= 0) ? el.value - 1 : 1);//позволим удалять
        }
    };
    $(this).keyup(function (event) {
        if (event.keyCode != 39 && event.keyCode != 37) {
            var val = this.value.replace(new RegExp('[,]', 'g'), '.').replace(new RegExp('[^0-9\.]', 'g'), '');
            if (!this.value) {
                this.value = 1;
            }
            if (!$(this).hasClass('weight')) {
                val = parseInt(this.value)
            }
            if (this.value != val) {
                this.value = val;
            }
        }
        if (event.keyCode == 38) {
            up(this);
        }
        if (event.keyCode == 40) {
            down(this);
        }
    });

//	.click(function(){$(this).select()});

    if ($(this).mousewheel) {
        $(this).mousewheel(function (res, e) {
            if (e > 0) {
                up(this);
            } else {
                down(this);
            }
            return false;
        });
    }
//	$(this).prev().click(function(){down($(this).next()[0]);return false});
//	$(this).next().click(function(){up($(this).prev()[0]);return false});

    $('.down', $(this).parent()).click(function () {
        down($(this).next()[0]);
        return false;
    });
    $('.up', $(this).parent()).click(function () {
        up($(this).prev()[0]);
        return false;
    });

    var t;
    $('.b_down', $(this).parent()).click(function () {
        down($(this).next()[0]);
        if (option.refresh) {
            clearTimeout(t);
            t = setTimeout(shop.refresh, 500)
        }

        return false;
    });
    $('.b_up', $(this).parent()).click(function () {
        up($(this).prev()[0]);
        if (option.refresh) {
            clearTimeout(t);
            t = setTimeout(shop.refresh, 500)
        }
        return false;
    });
    return $(this);
};


var shop = {
    /* добавление в корзину 
     gid - id товара
     count - количество
     prop - свойства {sale_unit:1}
     
     */

    add: function (gid, count, prop) {
//		alert(gid)
        var data = {'id': gid};
        if (count) {
            data['count'] = count;
        }

        if ($('#count-' + gid).val()) {
            data['count'] = $('#count-' + gid).val();
            $('#count-' + gid).parents('.item').addClass('changed').find('.in_basket').text(data['count']);
        }

        if (prop) {
            for (var i in prop) {
                data[i] = prop[i];
            }
        }
//		alert('')
//		util.notice('Добавлено '+count+' товаров');
//		$.post('/catalog/?act=add',data,shop._add3,'json');
        $.post('/catalog/?act=add', data, shop._add4, 'json');
    },

    /* добавление в корзину 
     gid - id товара
     count - количество
     cb - обратная связь
     prop - свойства {sale_unit:1}
     
     */
    addCb: function (gid, count, cb, prop) {
        var data = {'id': gid, 'count': count};
        if (prop) {
            for (var i in prop) {
                data[i] = prop[i];
            }
        }
//		util.notice('Добавлено '+count+' товаров')
        $.post('/catalog/?act=add', data, cb);
    },

    addSelected: function (data) {
        util.notice('Выбранные товары добавлены в корзину')
        $.post('/catalog/?act=addSelected', data, shop._add4);
    },
    _add2: function (res) {
        $('#basket-content').html(res);
//		$('#basket-clear').toggle($('#basket-content .item').length>0);
    },
    _add3: function (res) {
//		alert('')
        if (res.error) {
            alert(res.error);
            return;
        }
//		$('#basket-content').html(res.html);

        var b = $('#basket_box').show();
        $('.img', b).css('background-image', "url('" + res.img + "')");
        $('.desc', b).text(res.name);
//		$('h1 span',b).text($.numeral(res.count,'покупка','покупки','покупок'));//"продукт", "продукта", "продуктов"
//		$('.prise_desc strong',b).text(res.summ);

        $('#basket-blk .count').text(res.count);
        $('#basket-blk .summ').text(res.summ);


        $('#basket-content').text(res.count);
    },
    _add4: function (res) {

        if (res.error) {
            alert(res.error);
            return;
        }
        var b = $('#basket_box').show();
        $('.img', b).css('background-image', "url('" + res.img + "')");
        $('.desc', b).text(res.name);

        $('#basket-blk').html(res.html);
    },

    compare: function (gid) {
        var data = {'id': gid};
        $.post('/catalog/?act=addCompare', data, shop._compare, 'json');
    },
    _compare: function (res) {
        var b = $('#compare_box').show();
        $('.compare_desc strong', b).text(res.count);
    },
    addFav: function (gid) {
//		alert('')
        var data = {'id': gid};
        $.post('/catalog/?act=addFav', data, shop._addFav, 'json');
    },
    _addFav: function (res) {

        var text = $.numeral(res.count, 'товар', 'товара', 'товаров');//"продукт", "продукта", "продуктов"
        $('#favorite-content').html(res.count + ' ' + text);

        var b = $('#favorite_box').show();

        $('h1 strong', b).text(res.count);
        $('h1 span', b).text(text);

        $('#favorite-content').text(res.count);
    },
    /**
     * Удалить из корзины
     */
    remove: function (key) {
        $.get('?act=remove&key=' + key, function (res) {
            $('#basket' + res.key).remove();
            shop.refresh();
        }, 'json');
    },
    /**
     * Очистить корзину
     */
    clear: function () {
        $.get('/catalog/?act=clear', function (res) {
            $('#basket-content .item').remove();
            $('.basket_content').append('<span>Корзина пуста</span>');
            $('.basket_content .goods').remove();

            $('#basket-clear').hide();
            $('#basket-total strong').text('0.00');

//			shop.add(0)

            $.post('/catalog/?act=add', {'id': 0}, function (res) {
                $('#basket-blk').html(res.html);
            }, 'json');

        });

    },
    t: null,
    bindCount: function (el) {
        el.count({refresh: shop.refresh}).keydown(function () {
            if (event.keyCode === 13) {
                return false;
            }
            if (event.keyCode !== 39 && event.keyCode !== 37) {
                clearTimeout(shop.t);
                var el = $(this);
                shop.t = setTimeout(function () {
                    shop.focus_name = el.attr('name');
                    shop.refresh();
                }, 500);
            }
        });
    }
    ,
    refresh: function () {
        var data = $('#basket-form').serialize();
        $.post($('#basket-form').attr('action'), data, function (res) {
            $('.basket_content').html(res);
            if (shop.focus_name) {
//				alert(shop.focus_name)
                try {
                    $('#basket-form tbody [name=' + shop.focus_name + ']').focus();
                } catch (e) {
                }
                shop.focus_name = null;
            }
            var count = $('#basket-form input.count');
            shop.bindCount(count);
            $('#basket-form .title').title();

            $('#basket-form label[for]').custom();

            var c = 0;
            count.each(function () {
                c += this.value * 1;
            });
            $('#basket-content strong').text(c);
            var delivery;
            if (delivery = $('#delivery_price').attr('delivery')) {
                $('#comment-address').show().html('Стоимость доставки по этому адресу: <strong>' + delivery + ' руб.</strong>');

            }


            $('#basket-blk .summ').text($('#basket_total_summ').text());
            $('#basket_total_summ_1').text($('#basket_total_summ').text());

            if (c == 0) {
                $('#rel').hide();
            }
            $.post('/catalog/?act=add', {'id': 0}, function (res) {
                $('#basket-blk').html(res.html)
            }, 'json');

        });
    },
    focus_name: null
};

$(function () {
    $('.goods .item .count').count();
});