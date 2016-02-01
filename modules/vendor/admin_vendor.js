$(function(){	
	$('.num').click(function(){
		var el=this;
			$('#preloader').show();
			$.post('?act=accept',{num:this.value,state:el.checked},function(resp){
				el.checked=!el.checked;
				$('#preloader').hide();
			},'json');
		return false;
	});
})