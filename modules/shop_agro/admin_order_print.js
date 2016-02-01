$(function(){
	$('.up').click(function(){
		var el=$(this).parent().parent();
		if(el.prev('.pagebreak').length){
			
			el.insertBefore(el.prev('.pagebreak'))
		}
		return false;
	});
	
	$('.down').click(function(){
		var el=$(this).parent().parent();
		if(el.next('.pagebreak').length){
			
			el.insertAfter(el.next('.pagebreak'))
		}
		return false;
	});
	
	$('.remove').click(function(){
		if(confirm('Удалить?')){
			var el=$(this).parent().parent();
			el.remove()
		}
		return false;
	});
	
	$('.expand').click(function(){
		var el=$(this).parent().parent();
		el.find('.order').toggle();
		return false;
	});
	
	$('.expand-all').click(function(){
		var el=$(this).parent().parent();
		el.find('.order').toggle();
		return false;
	});
	
});	