var call=function(){
	$('#call_me_box').show();
}
var askQuest=function(){
	$('#ask_quest_box').show();
}

$(function(){
	$('#call_me').click(function(){
		$('#call_me_box').show();
		return false;
	});	
//	$('#ask_quest').click(function(){
//		$('#ask_quest_box').show();
//		return false;
//	});
	$('#call_staff').click(function(){
		$('#call_staff_box').show();
		return false;
	});
	$('.close_popup_form').click(function(){
		$(this).parent().parent().hide();
		return false;
	});
	
	$('#call_me_form,#call_staff_form,#ask_quest_form').submit(function(){
		var el=this;
		$.post(this.action,$(this).serialize(),function(res){
			if(res.err){
				var text='';
				for(var i in res.err){
					text+=res.err[i]+'<br>\n';
				}
				if(text){
					$.alert(text);
				}else{
					$('table',el).hide();
					$('.success',el).show();							
				}
			}	
		},'json')
				
		return false;
	});
});	