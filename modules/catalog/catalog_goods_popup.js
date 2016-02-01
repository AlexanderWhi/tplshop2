$(function(){
	$('[name=catalog]').change(function(){
		document.location='?category='+this.value		
		return false;
	});
	
	$('input[name=all]').click(function(){
		$('input.sel_item').attr('checked',false);
		$('tr:visible input.sel_item').attr('checked',$(this).attr('checked'));
	});
	
	$('.price').parent().click(function(){
		var ch=$(this).find('input'); 
		ch.attr('checked',!ch.attr('checked'));
	}).find('input').click(function(){
		$(this).attr('checked',!$(this).attr('checked'));
	});
	
	$('[name=search]').keyup(function(){
		if($(this).val()){
			val=new RegExp($(this).val().replace(/(\\|\/|\)|\(|\]|\[|\+|\?|\$|\^|\.|\*|\|)/g,'\\\$&'),'gi');
			$('.grid tbody tr').each(function(){
				$(this).toggle(val.test($(this).text()));
			});
		}else{
			$('.grid tbody').find('tr:hidden').show();
		}
	});

});	

function select(id){
//	alert(id+' - ')
	window.parent.window._select(id);
}