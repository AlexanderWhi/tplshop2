var _cb=function(resp){
			$('#preloader').hide();
			if(!!resp.err){
				var err='';
				for(var i in resp.err){
					err+=resp.err[i]+'; '
				}
				alert(err);
			}else{
				$('[name=u_id]').val(resp.u_id);
				msg(resp.msg);
				if(resp.img){
					$('#img-file').attr('src',resp.img);
				}
			}
		}
$(function(){
	$('#user-edit').submit(function(){
		$('#preloader').show();
	});
})