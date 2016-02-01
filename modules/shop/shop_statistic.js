$(function(){
	$("#tab").tabs();
	$('.date').datepicker({ dateFormat: 'dd.mm.yy'});
	
	
	$('#tab2 form').submit(function(){
		$.post(this.action,$(this).serialize(),function(html){
			$('#tab2 .res').html(html);
		});
		return false;
	});
	
	$('#tab3 form').submit(function(){
		$.post(this.action,$(this).serialize(),function(html){
			$('#tab3 .res').html(html);
		});
		return false;
	});
	$('#tab4 form').submit(function(){
		$.post(this.action,$(this).serialize(),function(html){
			$('#tab4 .res').html(html);
		});
		return false;
	});
	
});	