$(function(){
	$('[name=remove]').click(function(){
		if(confirm('Удалить выбранное?')){
			if($('[name^=item]:checked').length==0){
				alert('Необходимо выбрать элементы');
			}else{
				$.postData('?act=remove',$(this.form).serialize(),function(res){
						document.location.reload()
					}
				);
			}
		}
		return false;
	});
})