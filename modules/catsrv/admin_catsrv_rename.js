$(function(){
	$('#rename').submit(function(){
		if(!$.trim(this.name.value) || !$.trim(this.new_name.value)){
			alert('Заполните все поля');return false;
		}
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			msg(res.msg)
			$('#preloader').hide();
		},'json');	
		return false;
	});
});	