$(function(){
	var it=0;
	 $('button[name=add]').click(function(){
	 
	 	var c=$('[name=delivery_zone] :selected').attr('color');
	 	
	 	var myPolygon = new ymaps.Polygon(
	    [[myMap.getCenter()]],{},
	    {strokeColor: c,draggable:true,fillColor: c+'80'}
	 	);
	    myMap.geoObjects.add(myPolygon);
		myPolygon.editor.startEditing();
		myPolygon.zone_id=$('[name=delivery_zone]').val();
//		obj[it++]={
//			'myPolygon':myPolygon,
//			'id':;
//		}
	 });
	
	 $('button[name=save]').click(function(){
	 	var data=[];
	 	var it=0;
	 	myMap.geoObjects.each(function(obj){
	 		data[it++]={
	 			'zone_id':obj.zone_id,
	 			'Coordinates':obj.geometry.getCoordinates(),
	 		};
	 	});
	 	$('#preloader').show();
	 	$.post('?act=save',{data:$.toJSON(data)},function(res){
	 		
	 		$('#preloader').hide();
			util.msg(res.msg);
			
			var html='';
			for(var i in res.time_list){
				html+='<option value="'+res.time_list[i]+'">'+res.time_list[i]+'</option>';
			}
			$('[name=delivery_zone_data] option[value]').each(function(){
				if($(this).attr('value')!=''){$(this).remove();}
			});
			
			$('[name=delivery_zone_data]').append(html);
			
			
	 	},'json');

	 });
       $('button[name=remove]').click(function(){
	 	
	 	$('#preloader').show();
	 	$.post('?act=remove',{time:$('[name=delivery_zone_data]').val()},function(res){
	 		
	 		$('#preloader').hide();
			util.msg(res.msg);
			
			var html='';
			for(var i in res.time_list){
				html+='<option value="'+res.time_list[i]+'">'+res.time_list[i]+'</option>';
			}
			$('[name=delivery_zone_data] option[value]').each(function(){
				if($(this).attr('value')!=''){$(this).remove();}
			});
			
			$('[name=delivery_zone_data]').append(html);
			
			
	 	},'json');

	 });
       
	$('[name=delivery_zone_data]').change(function(){
		if($(this).val()){
			$.get('?act=getZoneData&time='+$(this).val(),function(res){
				if(res.length>0){
					
					myMap.geoObjects.removeAll();
					for(var i in res){
						var c="#ffffff";
						try{
							if(res[i].zone_id && COLOR_LIST[res[i].zone_id]){
								c=COLOR_LIST[res[i].zone_id];
							}
							
						}catch(e){}
						var myPolygon = new ymaps.Polygon(
						    res[i].Coordinates,{},
						    {strokeColor: c,draggable:true,fillColor: c+'80'}
						 	);
						    myMap.geoObjects.add(myPolygon);
							myPolygon.editor.startEditing();
							myPolygon.zone_id=res[i].zone_id;

					}
				}
//				alert($.toJSON(res));
			},'json');
		}
	});     
       
	$('[name=search]').click(function(){
		var q=$('[name=address]').val();
		$('#preloader').show();
		
		var _tmp=function(){
				// Геокодер возвращает результаты в виде упорядоченной коллекции GeoObjectArray
				return function (res) {
					var point = res.geoObjects.get(0);
						
					var html='<strong>'+q+'</strong>';
					var myPlacemark = new ymaps.Placemark(point.geometry.getCoordinates(),{balloonContent: html});
											
					myMap.setCenter(myPlacemark.geometry.getCoordinates());
//					myMap.geoObjects.add(myPlacemark);
					
					var data=[];
				 	var it=0;
				 	myMap.geoObjects.each(function(obj){
				 		data[it++]=obj;
				 	});
					var result = ymaps.geoQuery(data);
					
					var objectsContainingPolygon = result.searchContaining(myPlacemark);
					$('#preloader').hide();
					objectsContainingPolygon.each(function(res){
						alert(res.zone_id);
						
					});
					
				}
			}

			ymaps.geocode(q).then(_tmp());
		
	});
	
	$('#editmode').click(function(){
		var edt=$(this).prop('checked')
		myMap.geoObjects.each(function(obj){
			if(edt){
				obj.editor.startEditing();
				obj.options.set('draggable',true)
			}else{
				obj.editor.stopEditing();
				obj.options.set('draggable',false)
			}
			
	 		
	 	});
		
		
	});
	
});