<?=$this->getText($this->getUri())?>

<form id="delivery-form">

<label>Номер заказа</label>
<input class="i" name="order_num">
<button name="searh" type="submit"></button>
<div class="inline_block">
<input id="region" type="checkbox" checked><label for="region">Показать районы доставки</label>
&nbsp;
&nbsp;
&nbsp;
<input id="pickup" type="checkbox"><label for="pickup" class="margin">Места самовывоза</label>
</div>
</form>

<div id="map" style="width: 100%; height: 400px"></div>

<?=$this->getText($this->getUri()."bottom")?>



<script type="text/javascript">   

ymaps.ready(ymap_init);
var myMap;
var myPlacemark;
function ymap_init(){ 
	var _tmp=function(){
				// Геокодер возвращает результаты в виде упорядоченной коллекции GeoObjectArray
		return function (res) {
			var point = res.geoObjects.get(0);
			myMap = new ymaps.Map("map", {
		           center: point.geometry.getCoordinates(),
		           zoom: 13,
		           type: "yandex#map"
		    }); 
//		    myMap.controls.add("zoomControl").add("mapTools").add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
	        
		    
		    if(ZONE_DATA.length>0){
		    	
				for(var i in ZONE_DATA){
					var c="#ffffff";
					try{
						if(ZONE_DATA[i].zone_id && COLOR_LIST[ZONE_DATA[i].zone_id]){
							c=COLOR_LIST[ZONE_DATA[i].zone_id];
						}
					}catch(e){}
					var myPolygon = new ymaps.Polygon(
					    ZONE_DATA[i].Coordinates,{},
					    {strokeColor: c,fillColor: c+'80'}
					 	);
					myMap.geoObjects.add(myPolygon);
					myPolygon.zone_id=ZONE_DATA[i].zone_id;
//					myPolygon.options.set('visible',false);

				}
			}           
		}
	}
	ymaps.geocode('Екатеринбург').then(_tmp());
}


$(function(){
	$('#region').click(function(){
		var el=this;
		if(myMap){
			
			myMap.geoObjects.each(function(obj){
				
				if(obj.zone_id){
//					alert($(el).prop('checked'))
					if($(el).prop('checked')){
						obj.options.set('visible',true);
					}else{
						obj.options.set('visible',false);
					}
				}
		 	});
		}
		
	});
	
	$('#delivery-form').submit(function(){
		
		$.post('?act=getPos',$(this).serialize(),function(res){
			if(res.point){
//				alert(res.point)
				if(myPlacemark){
					myPlacemark.geometry.setCoordinates(res.point.split(','));
				}else{
					myPlacemark = new ymaps.Placemark(res.point.split(','), {}, {
				        iconLayout: 'default#image',
				        iconImageHref: '/img/mark.png',
				        iconImageSize: [33, 42],
				        iconImageOffset: [-15, -42]
    				});
    				myMap.geoObjects.add(myPlacemark);
    				
				}
				myMap.setCenter(res.point.split(','));
				
				
			}
			
		},'json');
		
		return false;
	})
	
});
</script>