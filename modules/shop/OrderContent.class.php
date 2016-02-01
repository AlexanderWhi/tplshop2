<?class OrderContent{
	public $basket=array();
	public $count=0;
	
	public $discount=0;
	public $delivery=0;
	public $price=0;
	
	function OrderContent($basket=array()){
		$this->basket=$basket;
		foreach($this->basket as $item){
			$this->price+=intval($item['price']);
			$this->count+=intval($item['count']);
		}
	}
	function getCount(){
		return $this->count;
	}
	function getPrice(){
		
		return $this->price;
	}
	function getDiscountPrice(){
		return round($this->price-$this->price/100*floatval($this->discount));
	}
	
	function getTotalPrice(){
		$price=$this->getPrice();
		
		$price-=round($price/100*floatval($this->discount));
		
		$price+=$this->delivery;
		return round($price);
	}
}