var _cb=function(res){
	$('#preloader').hide();
	msg(res.msg)
}

$(function(){
	$('#import').submit(function(){
		$('#preloader').show();
		$.post(this.action+'&mode=imp',$(this).serialize(),function(res){
			$.post('?act=imp&mode=upd',res,function(result){
				msg(result.msg)
				$('#preloader').hide();
			},'json');
		},'json')
				
		return false;
	});
	
	$('#import_img').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			msg(res.msg)
			$('#preloader').hide();
		},'json');		
		return false;
	});
	$('#empty_log').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			msg(res.msg)
			$('#preloader').hide();
		},'json');		
		return false;
	});
	
	$('#img_imp-form').submit(function(){
		$('#preloader').show();
	});
	
	
	
	
});	