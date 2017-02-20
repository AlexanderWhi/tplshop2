var filter = function (href) {
    document.location.href = href;
};
var debug = {
    append: function (msg) {
        var h = $('#debug').html();
        $('#debug').html(h + msg + '<br>');
    }
    ,
    text: function (msg) {
        $('#debug').html(msg);
    }
};



var alertBox = function (msg) {
    $('#alert_box').show().find('.msg').text(msg);
};
var openLoginForm = function () {
    $('#login-form-bar').show();
    $('#block-guest').addClass('act');
//	document.location.href='#login-form-bar'
};

var goLogin = function () {
    $('#block-user input[name=login]').focus();
};

var signup = function () {
    $('#login-box div.join').addClass('act');
    $('#login-box div.signup [name=name]').focus();
};
var enter = function () {
    $('#login-box div.join').addClass('act');
    $('#login-box div.login [name=login]').focus();
};

//function onChangeCity(id){
//	$.post('?act=ChangeCity',{'id':id},function(res){
//		document.location.reload();
//	},'json');
//}
function changeCity(city) {
    $.post('?act=ChangeCity', {'city': city}, function (res) {
        document.location.reload();
    }, 'json');
}

function addrBook() {
    $.post('/srv/book.php', {}, function (resp) {
        $('#popup_box').show();
        $('#popup_box form .cont').html(resp);
    });
}


function showSearch() {
    if ($('#search-bar').is(":visible")) {
        $('#search-bar').hide();
        $('#menu>ul').show();

    } else {
        $('#search-bar').show().find('input').focus();
        $('#menu>ul').hide();
    }
}
$(function () {
    $('.menu-mobile button[rel]').click(function () {
        var b = this;
        var rel = $(this).attr('rel');

        $('.submenu-mobile').not('#' + rel).hide();
        var el = $('#' + rel);
        if (el.is(':hidden')) {
            el.css('margin-top', '0px');
            el.data('scroll', $(window).scrollTop());
            //alert(el.data('scroll'));
            el.slideDown(300);
        } else {
            el.slideUp(100);
        }
        $('a.close', el).click(function () {
            $(b).click();
            return false;
        });
        return false;
    });
    $(window).scroll(function () {
        var el = $('.submenu-mobile').not(':hidden');
        if (el.length > 0) {
            var scr = el.data('scroll') - $(window).scrollTop();
            //alert(scr);
            if (scr > 0) {
                scr = 0;
                el.data('scroll', $(window).scrollTop());
            }
            el.css('margin-top', scr + 'px');
        }

    });
    $('#main-subscribe').submit(function () {
        $.post(this.action, $(this).serialize(), function (res) {
            if (res.err) {
                $.alert(res.err);
            } else {
                $.alert(res.msg);
            }
        }, 'json');

        return false;
    });

    $('#login-box div.join span.join').click(function () {
        if ($('#login-box div.join').hasClass('act')) {
            $('#login-box div.join').removeClass('act');
        } else {
            $('#login-box div.join').addClass('act');
        }
        return false;
    });
    $('#login-box .close').click(function () {
        $('#login-box div.join').removeClass('act');
        return false;
    });


    $(".scrollable a.left").click(function () {
        $("ul", $(this).parent()).scrollable("prev");
        return false;
    });
    $(".scrollable a.right").click(function () {
        $("ul", $(this).parent()).scrollable("next");
        return false;
    });
    $(".scrollable ul").scrollable({horizontal: true, naviItem: 'li', size: 3});


    $(".slider").easySlider({
        auto: true,
        continuous: true,
        numeric: true,
        pause: SLIDE_SPEED || 3000
    });

    $('#open-login-form').click(function () {
        openLoginForm();
        return false;
    });
    $('.close_popup_form,.popup_form .close,.popup1_form .close').click(function () {
//		alert('')
        $(this).parents('.popup_form,.popup1_form').hide();
        return false;
    });

    $('.dialog_box .close').click(function () {
        $(this).parents('.dialog_box').hide();
        return false;
    });

    $('.notice .close').click(function () {
        $(this).parents('.notice').hide();
        return false;
    });
//	.each(function(){alert('')});


//	$('.title').title();

    $('.image').image();
    //$('input.inptitle').inptitle();
    $('select.select').select();
    $('label[for]').custom();
    $('a.expand').expand();
    $('div#tabs').tab();

    $('.exit').click(function () {
        if (!confirm("Выйти?")) {
            return false;
        }
    });

    $('#search-bar input,#search input,#search-bar-mobile input').autocomplete('/autocomplete/autocomplete_search.php',
            {
                width: 300,
                selectFirst: false,
                max: 50,
                minChars: 3,
                delay: 100,
                formatItem: function (row, i, num) {
                    var result = row[0] + "<strong> " + row[1] + "</strong> р.";
                    return result;
                },
                onItemSelect: function (li) {
                    //if( li == null ) var sValue = "Ничего не выбрано!";  
                    if (!!li.extra)
                        var sValue = li.extra[2];
                    else
                        var sValue = li.selectValue;
                    //alert("Выбрана запись с ID: " + sValue); 
                    return sValue;
                }
            });



//	alert('');
    $('.first_block').each(function () {
        var el = $(':eq(0)', this);
        var exp = $(':not(:eq(0))', this).hide();
        var href = $('<a href="#">Подробнее...</a><br><br>').click(function () {
            exp.toggle();
            return false;
        }).insertAfter(el);


    });


    $('#login-box .signup form').submit(function () {
        var form = this;
        $('div.err', form).hide();
        $.post(this.action, $(this).serialize(), function (res) {

            if (res.err) {
                for (var i in res.err) {
                    $('#err-' + i, form).text(res.err[i]).show();
                }
            } else {
                alertBox(res.msg);

//				$(form).hide();
//				$('#login-box .signup .confirm').show();
            }

        }, 'json');
        return false;
    });


    $('.subscribe').submit(function () {
        $.post(this.action, $(this).serialize(), function (res) {
            if (res.err) {
                $.alert(res.err);
            } else {
                $.alert(res.msg);
            }
        }, 'json');

        return false;
    });

//	$(window).scroll(function(){
//		
//		if($(window).scrollTop()>59+120){
//			$('#slide-bar').addClass('slide');
//		}else{
//			$('#slide-bar').removeClass('slide');
//		}
//		
//	});




});

     	