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
	
	
	$('.reset').click(function(){
		
		if(confirm('�����?')){
			$('#preloader').show();
			$.post('?act=reset',{id:this.id.replace('reset','')},function(resp){
				$('#view'+resp.id).text('0');
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
})