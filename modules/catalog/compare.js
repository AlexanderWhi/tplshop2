$(function(){
	$('a.add').click(function(){
		var id=$(this).attr('rel');
		shop.add(id,'+1');
		
		return false;
	});
		
});	