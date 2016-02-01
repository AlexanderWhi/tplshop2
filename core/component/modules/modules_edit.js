$(function(){
	$('.save').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=save&'+this.name,data,function(resp){
			$('#preloader').hide();
			$('[name=mod_id]').val(resp.mod_id);
			$('[name=mod_content_id]').val(resp.mod_content_id);
			
			if(resp.mod_alias){
				$('[name=mod_alias]').val(resp.mod_alias);
			}
			msg(resp.msg);
//			alert(resp.msg);
		},'json');
		
		return false;
	});
	
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});
	
	$('[name=mod_type]').click(function(){
		changeType();
		
	});
	
	var changeType=function(){
		$('#block-modules,#block-content').hide();
		if($('#mod_type0')[0].checked){
			$('#block-modules').show();
		}
		if($('#mod_type1')[0].checked){
			$('#block-content').show();
		}
	}
	changeType();
	
})