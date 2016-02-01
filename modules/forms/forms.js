$(function(){
	$('#forms').submit(function(){
		var el=this;
		var valid=true;
		$('.error',this).remove();
		$('.req',this).each(function(){
			if(!$.trim(this.value)){
				$('<span class="error">Обязательно к заполнению</span>').insertAfter(this).show();
				if(valid){
					$(this).focus();
				}
				valid=false;
				
			}
		});
		if(valid){
			$.post(this.action,$(this).serialize(),function(res){
				$(el).hide();
				$('div.success').show();	
			},'json');
		}	
		return false;
	});		
});	