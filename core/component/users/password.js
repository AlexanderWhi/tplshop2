$(document).ready(function(){
	
	$('form').submit(function(){
		if(this.password.value==''){
			alert('������� ������');
			this.cpassword.value="";
			return false;
		} 
		if(this.password.value!=this.cpassword.value){
			alert('������ � ������������� �� ���������');
			this.cpassword.value="";
			this.password.value="";
			return false;
		}

		var data=$(this).serialize();
		$('#preloader').show();
		$.post('?act=passchange',data,function(resp){
			$('#preloader').hide();
//			alert(resp.msg);
			msg(resp.msg);
		},'json');
		
		return false;
	});
	
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});	
})
