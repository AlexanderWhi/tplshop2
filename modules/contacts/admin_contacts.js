$(function(){
	$('.remove').click(function(){
		if(confirm('������� ������?')){
			$('#preloader').show();
			$.post('?act=remove',{id:this.id.replace('remove','')},function(resp){
				$('#remove'+resp.id).parent().parent().hide();
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
	$('.removeAddr').click(function(){
		if(confirm('������� ������?')){
			$('#preloader').show();
			$.post('?act=removeAddr',{id:this.id.replace('remove','')},function(resp){
				$('#remove'+resp.id).parent().parent().hide();
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
})