/*
 * 	Easy Slider 1.7 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/4004/easy-slider-15-the-easiest-jquery-plugin-for-sliding
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
/*
 *	markup example for $("#slider").easySlider();
 *	
 * 	<div id="slider">
 *		<ul>
 *			<li><img src="images/01.jpg" alt="" /></li>
 *			<li><img src="images/02.jpg" alt="" /></li>
 *			<li><img src="images/03.jpg" alt="" /></li>
 *			<li><img src="images/04.jpg" alt="" /></li>
 *			<li><img src="images/05.jpg" alt="" /></li>
 *		</ul>
 *	</div>
 *
 */

(function($) {

	$.fn.easySlider = function(options){
	  
		// default configuration properties
		var defaults = {			
//			prevId: 		'prevBtn',
			prevText: 		'',
//			nextId: 		'nextBtn',	
			nextText: 		'',
			controlsShow:	true,
			controlsBefore:	'',
			controlsAfter:	'',	
			controlsFade:	true,
//			firstId: 		'firstBtn',
			firstText: 		'First',
			firstShow:		false,
//			lastId: 		'lastBtn',	
			lastText: 		'Last',
			lastShow:		false,				
			vertical:		false,
			speed: 			800,
			auto:			false,
			pause:			2000,
			continuous:		false, 
			numeric: 		true,
			prevNext: 		true,
//			numericId: 		'controls',
			animation: 		'fade'//slide
//			animation: 		'slide'//slide
		}; 
		
		var options = $.extend(defaults, options);  
				
		this.each(function() {  
			var obj = $(this); 				
			var s = $("li", obj).length;
			var w = $("li", obj).width(); 
			var h = $("li", obj).height(); 
			var clickable = true;
			obj.width(w); 
			obj.height(h); 
						
			
			var ts = s-1;
			var t = 0;
			
			if(options.continuous){
				if(options.animation=='fade'){
					$("ul li", obj).hide();
					$("ul li:eq(0)", obj).show();
					$("ul", obj).css({position:'relative'});
					
				}
				else{
					$("ul", obj).prepend($("ul li:last-child", obj).clone().css("margin-left","-"+ w +"px"));
					$("ul", obj).append($("ul li:nth-child(2)", obj).clone());
					$("ul", obj).css('width',(s+1)*w);
					obj.css("overflow","hidden");
				}
					
			};				
			
			if(!options.vertical && options.animation!='fade') $("li", obj).css('float','left');
			if(options.animation=='fade') $("li", obj).css({'position':'absolute'});
			
								
			if(options.controlsShow && $('li',this).length>1){//15.06.2012 когда картинок больше 1
				var html = options.controlsBefore;				
				if(options.numeric){
					html += '<ol class="control"></ol>';
					
				} 
				if(options.prevNext){
					if(options.firstShow) html += '<span class="first"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
					html += ' <a class="prev" href=\"javascript:void(0);\">'+ options.prevText +'</a>';
					html += ' <a class="next" href=\"javascript:void(0);\">'+ options.nextText +'</a>';
					if(options.lastShow) html += ' <span class="last"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';				
				};
				html += options.controlsAfter;						
				$(obj).append(html);										
			};
			
			if(options.numeric){									
				for(var i=0;i<s;i++){						
					$(document.createElement("li"))
//						.attr('id',options.numericId + (i+1))
						.html('<a rel='+ (i+1) +' href=\"javascript:void(0);\">&nbsp;'+(i+1)+'&nbsp;</a>')//(i+1) +
						.appendTo($(".control",obj))
						.click(function(){							
							animate($("a",$(this)).attr('rel')-1,true);
						}); 												
				};	
//				alert($('#'+options.numericId).html());						
			} 
			
			if(options.prevNext) {
				$("a.next ",obj).click(function(){
					animate("next",true);
				});
				$("a.prev",obj).click(function(){		
					animate("prev",true);				
				});	
				$(".first a",obj).click(function(){		
					animate("first",true);
				});				
				$(".last a",obj).click(function(){		
					animate("last",true);				
				});				
			};
			
			function setCurrent(i){
				i = parseInt(i);
				$(".control li", obj).removeClass("current");
				$(".control li:eq("+i+")",obj).addClass("current");
			};
			
			function adjust(){
				if(t>ts) t=0;		
				if(t<0) t=ts;
				if(options.animation=='fade'){
					
				}else{
					if(!options.vertical) {
						$("ul",obj).css("margin-left",(t*w*-1));
					} else {
						$("ul",obj).css("margin-left",(t*h*-1));
					}
				}
				
				clickable = true;
				if(options.numeric) setCurrent(t);
			};
			
			function animate(dir,clicked){
//				debug.append('dir='+dir+' t='+t+' ts='+ts);
				
				if (clickable){
					clickable = false;
					var ot = t;				
					switch(dir){
						case "next":
							t = (ot>=ts) ? (options.continuous ? 0 : ts) : t+1;						
							break; 
						case "prev":
							t = (t<=0) ? (options.continuous ? ts : 0) : t-1;
							break; 
						case "first":
							t = 0;
							break; 
						case "last":
							t = ts;
							break; 
						default:
							t = dir;
							
							break; 
					};	
					var diff = Math.abs(ot-t);
					var speed = diff*options.speed;	

					if(!options.animation=='fade'){
						if(!options.vertical) {
							p = (t*w*-1);
							$("ul",obj).animate(
								{ marginLeft: p }, 
								{ queue:false, duration:speed, complete:adjust }
							);				
						} else {
							p = (t*h*-1);
							$("ul",obj).animate(
								{ marginTop: p }, 
								{ queue:false, duration:speed, complete:adjust }
							);					
						};
					}else{
						if(clicked){
							var el=$("ul li:eq("+(t)+")",obj);
							var el2=$("ul li",obj).not(el);	 
						}else{
							var el=$("ul li:eq("+(t==s?0:t)+")",obj); 
							var el2=$("ul li",obj).not(el);
						}
						var patch_ie=($.browser.msie) && ($.browser.version <= 8.0);
						if (/png$/.test(el.find('img').attr('src')) && patch_ie) {
							el.show();adjust();
						}else{
							el.fadeTo(speed*1, 1,function(){$(this).show()}); adjust();		
						}
						if(/png$/.test(el2.find('img').attr('src')) && patch_ie){
							el2.hide();
						}else{
							el2.fadeTo(speed*1, 0,function(){$(this).hide()});
						}
					}
					if(!options.continuous && options.controlsFade){					
						if(t==ts){
							$(".next a",obj).hide();
							$(".last a",obj).hide();
						} else {
							$(".next a",obj).show();
							$(".last a",obj).show();					
						};
						if(t==0){
							$(".prev a",obj).hide();
							$(".first a",obj).hide();
						} else {
							$(".prev a",obj).show();
							$(".first a",obj).show();
						};					
					};				
					
					if(clicked) clearTimeout(timeout);
					if(options.auto && dir=="next" && !clicked){;
						timeout = setTimeout(function(){
							animate("next",false);
						},diff*options.speed+options.pause);
					};
			
				};
				
			};
			// init
			var timeout;
			if(options.auto){;
				timeout = setTimeout(function(){
					animate("next",false);
				},options.pause);
			};		
			
			if(options.numeric) setCurrent(0);
		
			if(!options.continuous && options.controlsFade){					
				$(".prev a",obj).hide();
				$(".first a",obj).hide();				
			};				
			
		});
	  
	};

})(jQuery);