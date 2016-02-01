$(function(){
	
	$('[name=print]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}
		var url='?act=print&'+$(this.form).serialize();
		util.openWin(url,800,600);
	});
	
//	$('[name=order_status]').change(function(){
//		this.form.action='?act=changeStatus';
//		$(this.form).submit();
//	});
	$('[name=pages]').change(function(){
		this.form.action='?act=changePageSize&pages='+this.value;
		$(this.form).submit();
	});
	
	$("#login").autocomplete("/autocomplete/autocomplete_login.php",{scroll:false,delay:400, max:10, minChars:2,translate:false});
	$('input.date').datepicker({ dateFormat: 'dd.mm.yy'});
});	