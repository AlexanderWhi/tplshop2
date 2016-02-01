$(function(){
	$('input.date').attachDatepicker();
	$('#board-form').submit(function(){
		var el=this;
		$.post(this.action,$(this).serialize(),function(res){
			if(res.err){
				var text='';
				for(var i in res.err){
					text+=res.err[i]+'\n';
				}
				if(text){
					$.alert(text);
				}
			}else{
				$('#board-form').hide();
				$('.success').show();							
			}	
		},'json')
				
		return false;
	});	

});	