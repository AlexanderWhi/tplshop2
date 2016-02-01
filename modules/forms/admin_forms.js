$(document).ready(news_ready=function(){	
	$('.remove').click(function(){
		if(confirm('Удалить запись?')){
			$('#preloader').show();
			$.post('?act=remove',{id:this.id.replace('remove','')},function(resp){
				$('#remove'+resp.id).parent().parent().hide();
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
})