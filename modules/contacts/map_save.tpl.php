<?if($this->isAdmin()){
	echo '<a href="/admin/enum/addr_list/" class="coin-text-edit" title="Редактировать"></a><br><br>';
}?>

<div id="myMap" style="width: 100%; height: 600px"></div>

<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=fid_ymap"></script>
<script type="text/javascript">

var POINTS=<?=printJSON($addrList)?>;

function fid_ymap(ymaps){
	var myCollection = new ymaps.GeoObjectCollection ({},{});

	for(var i in POINTS){
		myPlacemark = new ymaps.Placemark(i.split(',').reverse(),{balloonContent: POINTS[i]});
		myCollection.add(myPlacemark);
	}
	var myMap = new ymaps.Map('myMap', {
		    center: [55.8, 37.6],
		    zoom: 8,
	        type: "yandex#map"
	});
	myMap.controls.add("zoomControl").add("mapTools").add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
myMap.geoObjects.add(myCollection);
// Устанавливаем карте центр и масштаб так, чтобы охватить коллекцию целиком.
myMap.setBounds(myCollection.getBounds());
	
	
};
</script>