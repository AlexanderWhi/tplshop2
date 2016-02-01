$(function(){
	$('#content-form').submit(function(){
		var data=$(this).serialize();
		$('#preloader').show();
		$.post('?act=save',data,function(resp){
			$('#preloader').hide();
			if(resp.err){
				alert(resp.err)
			}else{
				
				$('[name=c_id]').val(resp.c_id);
				util.msg(resp.msg);
			}
				
		},'json');
		
		return false;
	});
	
})