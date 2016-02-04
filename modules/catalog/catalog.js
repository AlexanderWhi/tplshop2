var expand=function(id){
	$('#'+id).toggle();
}

$(function(){
	
	$('.slider').each(function(){
		var values=$(this).attr('values').split('|');
		var max=values.length;
		var inp=$('#p-'+$(this).attr('rel'));
		
		var v1=0,v2=max-1;
		if(new RegExp('([^\-]+)-([^\-])+').test(inp.val())){
			var v=inp.val().split('-');
			for(var i in values){
				if(values[i]==v[0]){v1=i}
				if(values[i]==v[1]){v2=i}
				
			}
		}
		$(this).slider({
			range: true,
			step:1,
			min:0,
			max:max-1,
			values: [ v1, v2 ],
			change: function(event, ui) {
				var v=$(this).slider('values');
				inp.val(values[v[0]]+'-'+values[v[1]]);
				$('form#catalog-menu').submit();
			}
		});
		var maxl=12;
		var c=values.length;
		var d=1;
		if(c>maxl){
			d=Math.ceil(c/maxl);
//			alert(d)
		}
		for(var i in values){
			var l='';
			if(i%d==0){
				l=values[i];
			}
			$('<span class="grade" style="left:'+(100/(max-1)*i)+'%">'+l+'</span>').appendTo(this);
		}
	});
	
	
	
	
	
	
//	$('a.add').click(function(){
//		var id=$(this).attr('rel');
//		shop.add(id,'+1');
//		
//		return false;
//	});
//	
//	$('a.compare').click(function(){
//		var id=$(this).attr('rel');
//		shop.compare(id);
//		return false;
//	});	
	

/*$('.goods-menu h4 a').each(function(){
	
	if($(this).hasClass('expand')){
		$(this).parents('.goods-menu').find('ul').hide();
		$(this).removeClass('expand').addClass('unexpand');
	}else{
		$(this).parents('.goods-menu').find('ul').show();
		$(this).removeClass('unexpand').addClass('expand');
	}
	
}).click(function(){
	if($(this).hasClass('expand')){
		$(this).parents('.goods-menu').find('ul').hide();
		$(this).removeClass('expand').addClass('unexpand');
	}else{
		$(this).parents('.goods-menu').find('ul').show();
		$(this).removeClass('unexpand').addClass('expand');
	}
	return false;
});*/
$('a.show_all').click(function(){
	$('.prop_item.h').toggle();
	return false;
});
var t=null;
$('form#catalog-menu input').change(function(){
	var form=this.form;
	clearTimeout(t);
	t=setTimeout(function(){$(form).submit();},1000);
	
});


$('#catalog-menu .expand').each(function(){
	var alt=$(this).attr('alt');
	var text=$(this).text();
	if($('#prop_sel').hasClass('h') && false){
		$('span',this).text(text);
		$(this).addClass('e');
	}else{
		$('span',this).text(alt);
	}
	$(this).click(function(){
		if($('#prop_sel').hasClass('h')){
			$('#prop_sel').removeClass('h');
			$('span',this).text(alt);
			$(this).removeClass('e');
			
		}else{
			$('span',this).text(text);
			$(this).addClass('e');
			$('#prop_sel').addClass('h');
			
		}
		
		
		return false;
	});
	
});


});	