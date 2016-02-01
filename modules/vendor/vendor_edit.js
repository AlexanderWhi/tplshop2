var _cb=function(resp){
			$('#preloader').hide();
			if(!!resp.err){
				var err='';
				for(var i in resp.err){
					err+=resp.err[i]+'; '
				}
				alert(err);
			}else{
				$('[name=u_id]').val(resp.u_id);
				msg(resp.msg);
				if(resp.img){
					$('#img-file').attr('src',resp.img);
				}
				if(resp.images){
					fillImages(resp.images);
				}
			}
		}
		
		
var fillImages=function(images){
	var bar=$('.img_list');
	$('.item:visible',bar).remove();
	
	for(var i in images){
		var el=$('.img_list .item:hidden').clone().css('display','inline-block');
		$('input[name=pos_]',el).val(parseInt(i)+1).attr('name','pos[]');
		$('input[name=images_]',el).val(images[i]).attr('name','images[]');
		$('img',el).attr('src',util.scaleImg(images[i],'h100'));
		
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
	$('#user-edit').submit(function(){
		$('#preloader').show();
	});
	fillImages(IMAGES);
})