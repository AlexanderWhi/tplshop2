jQuery.numeral=function($number, $ending1, $ending2, $ending3) {
	//"продукт", "продукта", "продуктов"
	$num100 = $number % 100;
	$num10 = $number % 10;
	if ($num100 >= 5 && $num100 <= 20 || $num10 == 0 || ($num10 >= 5 && $num10 <= 9)) {
		return $ending3;
	} else if ($num10 == 1) {
		return $ending1;
	} else {
		return $ending2;
	}
}
jQuery.alert=function(msg){
	$('#alert_box').show().find('.msg').html(msg);
}



jQuery.fn.title=function(title_id){
	var title;
	if(!title_id){
		title_id='title';
	}
	if($('#'+title_id).length==0){
		title=$('<div id="'+title_id+'"></div>').appendTo('body');
	}else{
		title=$('#'+title_id);
	}

	title.hide();

	$(this).mouseover(function(){
			
		var t=$(this).data('tit');
		t=t.replace(/\n/g,'<br>');
		if($.trim(t)){
			title.show().html(t);
		}
		
		
	}).mouseout(function(){
		title.hide();
	}).mousemove(function(e){
  		var ypos=e.pageY-5;
  		var xpos=e.pageX+5;  		
  		if($(window).width()/2<xpos-$(window).scrollLeft()){
  			xpos-=title[0].clientWidth+10;
  		}
  		if($(window).height()/4*3<ypos-$(window).scrollTop()){
  			ypos-=title[0].clientHeight+10
  		}else if($(window).height()/4*1<ypos-$(window).scrollTop()){
  			ypos-=title[0].clientHeight/2
  		}
  	
  		title.css({'top':ypos+5,'left':xpos});
		
	}).each(function(){
		$(this).data('tit',this.title);
		$(this).removeAttr('title');
		$(this).find('*').removeAttr('title').removeAttr('alt');
	});
}
jQuery.fn.inptitle=function(){
	$(this).each(function(){
		$(this).data('title',$(this).attr('title'));
		$(this).attr('title','');
		if(this.value==''){
			this.value=$(this).data('title');
		}
	}).click(function(){
		if(this.value==$(this).data('title')){
			this.value='';
		}
	});
}
jQuery.fn.expand=function(){
	$(this).click(function(){
		var id=$(this).attr('href').replace(new RegExp('.*#'),'');
		if($(this).hasClass('act')){
			$(this).removeClass('act');
			$('#'+id).hide();
		}else{
			$(this).addClass('act');
			$('#'+id).show();
		}
		
		return false;
	});
}

jQuery.fn.expandblk=function(){
	
	$(this).each(function(){
		var blk=$(this);
		var title='Развернуть';
		if($(blk).attr('title')){
			title+=" "+$(this).attr('title');
		}
		var el=$('<a href="#" class="expandblk">'+title+'</a>').click(function(){
			var title='';
		
			
			if($(blk).is(":visible")){
				title='Развернуть';
				
				$(blk).hide();
			}else{
				title='Cвернуть';
				$(blk).show();
			}
			if($(blk).attr('title')){
				title+=" "+$(blk).attr('title');
			}
			$(this).text(title);
			return false;
		});
		blk.after(el);
		
	});
	
	
	$(this).click(function(){
		var id=$(this).attr('href').replace(new RegExp('.*#'),'');
		if($(this).hasClass('act')){
			$(this).removeClass('act');
			$('#'+id).hide();
		}else{
			$(this).addClass('act');
			$('#'+id).show();
		}
		
		return false;
	});
}
jQuery.fn.image=function(title_id){
	var title;
	if(!title_id){
		title_id='title';
	}
	if($('#'+title_id).length==0){
		title=$('<div id="'+title_id+'"></div>').appendTo('body');
	}else{
		title=$('#'+title_id);
	}

	title.hide();

	$(this).mouseover(function(){
		
		var t=$(this).data('tit');
		t=t.replace(/\n/g,'<br>');
		
			
		var img=$(this).attr('rel');
		
		if($.trim(img)){
			var html='<img src="'+img+'">';
			if($.trim(t)){
				html+='<div>'+t+'</div>';
			}
			title.show().html(html);
		}
	}).mouseout(function(){
		title.hide();
	}).mousemove(function(e){
  		var ypos=e.pageY-5;
  		var xpos=e.pageX+5;  		
  		if($(window).width()/2<xpos-$(window).scrollLeft()){
  			xpos-=title[0].clientWidth+10;
  		}
  		if($(window).height()/4*3<ypos-$(window).scrollTop()){
  			ypos-=title[0].clientHeight+10
  		}else if($(window).height()/4*1<ypos-$(window).scrollTop()){
  			ypos-=title[0].clientHeight/2
  		}
  	
  		title.css({'top':ypos+5,'left':xpos});
		
	}).each(function(){
		$(this).data('tit',this.title);
		$(this).removeAttr('title');
		$(this).find('*').removeAttr('title').removeAttr('alt');
	});
}


jQuery.fn.tab=function(){
	var tabs=this;
	$('ul.tabs a',this).click(function(){
		$('div.tabs',tabs).hide();
		$('ul.tabs a',tabs).removeClass('act');
		$(this).addClass('act');
		var id=$(this).attr('href').replace(new RegExp('.*#'),'');
		$('#'+id,tabs).show();
		return false;
	});
	$('ul.tabs a:eq(0)',$(this)).click();
	
}

//
jQuery.fn.select=function(){
	$(this).each(function(){
		var div=$('<div class="select"><span></span></div>').insertAfter(this);
		div.find('span').text($(':selected',this).text());
		$(this).fadeTo(0,0).show().appendTo(div);
		$(this).change(function(){
			$(this).parent().find('span').text($(':selected',this).text());
		});
		
	});
}




jQuery.fn.custom=function(){
	$(this).each(function(){
		var el=$('#'+$(this).attr('for'));
		if(el.length==1){
			var type=el.attr('type');
			var ch=el.attr('checked');
			if(type=='checkbox' || type=='radio'){
				el.addClass('custom').click(function(){
					if(this.type=='radio'){
						var name=this.name.replace(new RegExp('([\\[\\]])','g'),'\\$1');
//						alert(name)
						$('[name='+name+']').each(function(){
							$('label[for='+this.id+']').removeClass('checked')
						});
					}
					if(this.checked){
						$('label[for='+$(this).attr('id')+']').addClass('checked')
					}else{
						$('label[for='+$(this).attr('id')+']').removeClass('checked')
					}
				});
			
				$(this).addClass(type);
				if(ch){
					$(this).addClass('checked');
				}
			}else if(type=='file'){
				
				el.addClass('custom').change(function(){
					var v=this.value.replace(new RegExp('.+[\\\\/]'),'');
					$('label[for='+$(this).attr('id')+'] span').text(v);
				});
				$(this).addClass('file').prepend('<span></span>');
			}
		}
	});
}

var util={
	referer:null,
	openWin:function(url, width, height){
		var win2;
		var screen_width = window.screen.width;
		var screen_height = window.screen.height;
	 
		width += 40;
		height += 30;
	
		if(width > screen_width){
			width = screen_width - 40;
		}
		if(height > screen_height){
			height = screen_height - 100;
		}
		win2=window.open( url , 'windetail', 'width='+width+',height='+height+', top=20, left=20, resizable=1, scrollbars=1' );
		win2.focus();
	},
	notice:function(msg){
		
		$('#notice').html(msg).toggle(500,function(){
			var el=this;
			var hd=function(){$(el).toggle(200)};
			setTimeout(hd,1000) ;		
		});
	},
	scaleImg:function(img,params){
		return img.replace(/([^/]+)\.(jpg|jpeg|png|gif)$/i,'.thumbs/$1_'+params+'.$2');
	}
};
//$(function(){	
//	$('input[name=close],button[name=close]').each(function(){
//		if(util.referer==''){
//			$(this).hide();
//		}
//	}).click(function(){
//		if(util.referer==null){
//			window.history.back();
//		}else{
//			document.location=util.referer;
//		}
//		return false;
//	});	
//});