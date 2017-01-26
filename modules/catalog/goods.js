var comments = function () {
    document.location.href = '#tabs'
    $('a[href*=t4]').click();
};

var previewScrool = function (px) {
    $('#preview ul').animate({'scrollLeft': $('#preview ul').scrollLeft() + px}, 800);
};



var recount = function () {
    var c = parseInt($('#c').val());
    $('#total').text(($('#total').attr('price') * c).toFixed(2));
    setTimeout(recount, 200);
};
/*@required shop.js*/

$(function () {

    recount();

    $('.count').count().keydown(function () {
        if (event.keyCode == 13) {
            var el = $(this).parent()
            var id = el.find('[name=id]').val();
            var unit_sale = el.find('[name=unit_sale]').val();
//			alert(sale_unit)
            shop.add(id, $(this).val(), {'unit_sale': unit_sale});
            return false;
        }
    }).focus();



//	document.location.href='#content'

    $('#tabs').tab();


    $('#goods-fb-form').submit(function () {
        $.post(this.action, $(this).serialize(), function (res) {
            if (res.err) {
                alert(res.err);
            } else {
                $('#goods-fb-form').hide();
            }
        }, 'json');

        return false;
    });

    $('.show_goods_comment').click(function () {

        var id = $(this).attr('rel');
        $.post('?act=showComment', {'id': id}, function (res) {
            alert('Показано');
        }, 'json');
        return false;
    });
    $('.remove_goods_comment').click(function () {

        var id = $(this).attr('rel');
        $.post('?act=RemoveComment', {'id': id}, function (res) {
            alert('Скрыто');
        }, 'json');
        return false;
    });

    $('.answer_comment').click(function () {
        $('.answer_form').show()

        $('button.answer').click(function () {
            alert($(this.form).serialize())
            return false;
        });

        return false;
    });

//	var t=null;
//	var img=[];
//	var cur=0;

    $('#preview li a').click(function () {
        $('#preview li').removeClass('act')

        var i = $(this).attr('rel2');
        var i2 = $(this).attr('href');
        $(this).parent().addClass('act');
        $('.img_list a').css('background-image', 'url(' + i + ')').attr('href', i2);
//		clearTimeout(t);
        return false;
    })
//	.each(function(){
//		img[img.length]=$(this).attr('rel');
//	});

//	function nxt(){
//
//		if(cur>img.length-1){
//			cur=0;
//		}
//		$('.img_list img').attr('src',img[cur]);
//		$('#preview li').removeClass('act')
//		$('#preview li:eq('+cur+')').addClass('act');
//		cur++;
//		t=setTimeout(nxt,SLIDE_SPEED || 3000);
//	}
//	nxt();
    $('a[rel="gallery"]').colorbox();
});	