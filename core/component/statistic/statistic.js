$(function(){
	$('.date').datepicker({ dateFormat: 'dd.mm.yy'});
	$('button').click(function(){
		$('#preloader').show();
		var f=this.form;
		$.post('?act='+this.name,$(f).serialize(),function(res){
			$('#load_data').html(res);
			$('#preloader').hide();
			$('a.ref').click(function(){
				$('#preloader').show();
				$.post('?act=RefererUrl',$(f).serialize()+'&url='+this.href,function(res){
					$('#load_data').html(res);
					$('#preloader').hide();
				});
				return false;
			});
			
		});
	});
	
//	$('[name=visitPerDay]').click();
});