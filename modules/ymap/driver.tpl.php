<?=$this->getText($this->getUri())?>

<div id="map" style="width: 100%; height: 400px"></div>

<?=$this->getText($this->getUri()."bottom")?>



<script type="text/javascript">   

ymaps.ready(ymap_init);
var myMap;
function ymap_init(){ 
	var _tmp=function(){
				// Геокодер возвращает результаты в виде упорядоченной коллекции GeoObjectArray
		return function (res) {
			var point = res.geoObjects.get(0).geometry.getCoordinates();
			myMap = new ymaps.Map("map", {
		           center: point,
		           zoom: 13
		    }); 
		            
		    var myPlacemark = new ymaps.Placemark(point);
			myMap.geoObjects.add(myPlacemark);

			var ping=function(){
//				
				ymaps.geolocation.get().then(function(res){
					var point = res.geoObjects.get(0).geometry.getCoordinates();
//					alert(point)
					myPlacemark.geometry.setCoordinates(point);
					myMap.setCenter(point);
					$.post('?act=setPos',{'point':point},function(res){});
				});
				setTimeout(ping,1000*60);
			}
			ping();	  
		}
	}
	ymaps.geolocation.get().then(_tmp());
}       
</script>