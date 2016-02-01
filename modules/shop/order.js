$(function(){
	$('.item_head .expand').click(function(){
		var id=$(this).attr('href').replace(new RegExp('.*#'),'');
		if($('#head'+id).hasClass('act')){
			$('#head'+id).removeClass('act');
			$('#body'+id+' .item_content').hide();
		}else{
			$('#head'+id).addClass('act');
			$('#body'+id+' .item_content').show();
		}
		
		return false;
	});
	$('.save_review').click(function(){
		var text=$(this).prev().val();
		var id=$(this).parent().attr('id').replace('review','');
		var el=this;
		$(el).parent().prev().text('Сохраняем отзыв');
		
		$.post(this.form.action,{'review':text,'id':id},function(res){
			$(el).parent().prev().text(res.msg);
			$(el).parent().toggle();
			
		},'json');
		
	});
	
	if(ORDER_ID){
		var el=$('#order'+ORDER_ID);
		$(window).scrollTop(el.offset().top);
//		document.location.href=document.location.href+'#order'+ORDER_ID;
	}
});	