$(function(){
	$('[name=remove]').click(function(){
		if(confirm('������� ���������?')){
			if($('[name^=item]:checked').length==0){
				alert('���������� ������� ��������');
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