$(function(){
	$('.confirm').click(function(){
		var el=this;
		if(confirm('������ �����?')){
			$.get($(this).attr('href'),function(res){
				if(res.msg){
					$.alert(res.msg);
				}
				$(el).hide();
			},'json');
		}
			
		return false;
	});
	
});	