_cb=function(resp){
	$('#preloader').hide();
	$('[name=img]').val(resp.path);
	fillImages(resp.images);
	if(resp.img){
		$('[name=img]').val(resp.img);
		$('#img').attr('src',util.scaleImg(resp.img,'h100'));
	}
	if(resp.id){
		$('[name=id]').val(resp.id);
	}
	msg(resp.msg);
}


var uplClone=function(){
		var el=$(this).parent();
		var new_el=el.clone().insertAfter(el).find('input').change(uplClone);
	}
var fillImages=function(images){
//	alert(images)

	var bar=$('#img-upload').empty();
	for(var i in images){
//		alert(images[i])
	
		var el=$('.img-upload-item:hidden').clone().css('display','inline-block');
		$('input[name=pos_]',el).val(parseInt(i)+1).attr('name','pos[]');
		$('input[name=images_]',el).val(images[i].img).attr('name','images[]');
		var format=$('select[name=format_]',el).attr('name','format[]');
		var desc=$('textarea[name=desc_]',el).attr('name','desc[]');
		if(images[i].format){
			format.val(images[i].format);
		}
		if(images[i].description){
			desc.val(images[i].description);
		}
		
		
		$('img',el).attr('src',util.scaleImg(images[i].img,'w100h100'));
		
		$('a',el).click(function(){
			if(confirm('Удалить?')){
				$(this).parent().remove();
			}
			return false;
		});
		
		$(el).appendTo(bar);
	}
}
$(function(){
	$('[name=date]').datepicker({ dateFormat: 'dd.mm.yy'});
	$('[name=clear]').click(function(){
		$('[name=img]').val('');
		$('#img').attr('src','/img/no_image.png');
	});
	$('#gallery-edit-form').submit(function(){$('#preloader').show();});
	
	$('.img-upload-bar-item input').change(function(){
		$(this.form).submit();
	});
	
	fillImages(IMAGES);
//	alert(1)
	$('.reload').click(function(){
		$.post(this.href,{},function(res){
			fillImages(res);
			
		},'json');
		
		
		return false;
	});
});
