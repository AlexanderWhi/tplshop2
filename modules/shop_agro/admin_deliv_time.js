$(function(){
	$('#deliv-time-form').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			$('#preloader').hide();
			util.msg(res.msg);
		},'json');
		return false;
	});
	
	$('.add').click(function(){
		
		var el=$('#deliv-time-form tfoot tr').clone();
//		alert(el.html())
		$('input',el).each(function(){
			this.name=this.name.replace(/_$/,'[]');
		});
		$('#deliv-time-form table tbody').append(el);
		return false;
	});
	
	$('.del').live('click',function(){
		var data=$(this).parent().parent().remove();
		return false;
	});
});
