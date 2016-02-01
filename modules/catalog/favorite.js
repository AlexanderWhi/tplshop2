$(function(){
	
	$('a.add').click(function(){
		var id=$(this).attr('rel');
		shop.add(id,'+1');
		
		return false;
	});	
	
	$('a.remove').click(function(){
		
		if(confirm('Убрать из избранного?')){
			$.get(this.href,{},function(res){
				$("#favorite"+res.id).remove();
			},'json');
		}
		
		return false;
	});
	
});	