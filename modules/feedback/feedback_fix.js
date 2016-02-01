var selection={
	get:function(){
		var str='';
		 if (document.getSelection) {
			str = document.getSelection();
		 }else if(document.selection && document.selection.createRange){
			var range = document.selection.createRange();
			str = range.text;
		 }else{
		 	str = "Sorry, this is not possible with your browser.";
		 }
		 return str;
	}
}
function ctrlEnter(event)
{
	
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD)))
	{
		var select=$.trim(selection.get()+' ');
		if(select!=''){
			if(confirm('Отправить текст '+select+' ?')){
				var data={
					text:select,
					html:$('html').html()
				}
				$.post('/feedback/?act=fix',data,function(res){
					$.alert(res)
				});
			}
		}else{
			if(select=prompt('Прокоментируйте ошибку!','')){
				var data={
					text:select,
					html:$('html').html()
				}
				$.post('/feedback/?act=fix',data,function(res){$.alert(res)});
			}
		}
	}
}
$(function(){
	$(window.document).keypress(ctrlEnter);
});