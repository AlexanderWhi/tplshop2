$(function(){
	$('[name=page_size]').change(function(){
		this.form.submit()
	});
	
	$('input[name=all]').click(function(){
		$('input.sel_item').attr('checked',false);
		$('tr:visible input.sel_item').attr('checked',$(this).attr('checked'));
	});
	
});	