<?if($this->isAdmin()){
	echo '<a href="/admin/contacts/addr/" class="coin-text-edit" title="Редактировать"></a><br><br>';
}?>



<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=fid_ymap"></script>
<script type="text/javascript">

var POINTS=<?=printJSON($addrList)?>;

var myPlacemarkArr=[];

function fid_ymap(ymaps){
//	var myCollection = new ymaps.GeoObjectCollection ({},{});
var myMap=[];
	var n=0;
	for(var i in POINTS){
		myMap[i] = new ymaps.Map('myMap'+i, {
		    center: [55.8, 37.6],
		    zoom: 16,
	        type: "yandex#map"
		});
	
//	myMap.geoObjects.add(myCollection);
	myMap[i].controls.add("zoomControl").add("mapTools").add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
	
		
		
		
		var itm=POINTS[i];
		if($.trim(itm.point)){
			var myPlacemark = new ymaps.Placemark(itm.point.split(',').reverse(),{balloonContent: POINTS[i]});
			myPlacemarkArr[n++]=myPlacemark;
			myCollection.add(myPlacemark);
			myMap[i].setBounds(myCollection.getBounds());
			
			
		}else{
			
			var _tmp=function(i,itm){
				// Геокодер возвращает результаты в виде упорядоченной коллекции GeoObjectArray
					return function (res) {
						var point = res.geoObjects.get(0);
						
						var html='<strong>'+itm.name+'</strong>'+itm.description;
						var myPlacemark = new ymaps.Placemark(point.geometry.getCoordinates(),{balloonContent: html});
//						alert(n)
						myPlacemarkArr[i]=myPlacemark;
						
//						myCollection.add(myPlacemark);
//						myCollection.add (point);
						myMap[i].setCenter(myPlacemark.geometry.getCoordinates());
						myMap[i].geoObjects.add(myPlacemark);
//						myMap.setBounds(myCollection.getBounds());
					}
			}
			
			
			ymaps.geocode(itm.addr).then(_tmp(i,itm));
			    
		}
//		n++;
	}
	
	
	
// Устанавливаем карте центр и масштаб так, чтобы охватить коллекцию целиком.
//	myMap.setBounds(myCollection.getBounds());
	
	
};
</script>



<div id="myMap" style="width: 100%; height: 210px"></div>




<div class="map_list">
<table>
<?foreach ($addrList as $k=>$itm) {?>
<tr class="item">
<td>
	<?=$itm['description']?>
</td>
<td class="map">

</td>


</tr>
<?}?>

</table>



</div>
<script type="text/javascript">

$(function(){
	
	
	$('a[link]').click(function(){
//		alert($(this).attr('link'));
		try{
//			myPlacemarkArr[$(this).attr('link')].options({state:'active'});
			myPlacemarkArr[$(this).attr('link')].balloon.open();
		}catch(e){
//			alert('Карта не инициоирована');
		}
		
		document.location.href='#';
		return false;
	});	


	$('.map_list a.header').click(function(){
		$(this).next().toggle();
		
		return false;
	});

	/*$('.map_list a.m').click(function(){
		var addr=$(this).attr('rel').replace(/.*\?/,'');
//		alert(addr)
		ymaps.geocode(addr).then(
				// Геокодер возвращает результаты в виде упорядоченной коллекции GeoObjectArray
					function (res) {
						var point = res.geoObjects.get(0);
						if(myMap){
							
							document.location.href='#';
							myMap.panTo(point.geometry.getCoordinates(), {
                                flying: true,
                                duration: 500
                            });
						}
					});
		return false;
	});*/
	
});

</script>