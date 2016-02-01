$(document).ready(news_ready=function(){	
	$('.remove').click(function(){
		if(confirm('”далить запись?')){
			$('#preloader').show();
			$.post('?act=remove',{id:this.id.replace('remove','')},function(resp){
				$('#remove'+resp.id).parent().parent().hide();
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
	
	$('.reset').click(function(){
		if(confirm('—брос?')){
			$('#preloader').show();
			$.post('?act=reset',{id:this.id.replace('reset','')},function(resp){
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
})