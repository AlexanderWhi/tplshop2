<?class Basket{
	public $basket=array();
	public $count=0;
	
	public $discount=0;
	public $bonus=0;
	public $delivery=0;

	public $price=0;
	public $sum=0;
	public $margin=0;
	

	function getBasket(){
		$out=array();
		foreach ($this->basket as $k=>$item){
			$item['price']+=$item['price']/100*$this->margin;
			$item['sum']=$item['price']*$item['count'];//пересчёт суммы
			$out[$k]=$item;
		}
		return $out;
	}
	
	function __construct($basket=array(),$margin=0){
		$this->basket=$basket;
		$this->margin=$margin;
		foreach($this->getBasket() as $item){
			$this->sum+=floatval($item['price'])*floatval($item['count']);
			$this->count+=floatval($item['count']);
		}
	}
	function getCount(){
		return $this->count;
	}
	function getPrice(){
		return $this->price;
	}	
	function getSum(){
		return $this->sum;
	}
	function getDiscountSum(){
		return round($this->sum-$this->sum/100*floatval($this->discount),2);
	}	

	function getBonusSum(){
		return $this->sum-$this->bonus;
	}
	
	function getTotalSum(){
		$price=$this->getSum();
		
		$price-=$this->bonus;
		
		$price-=$price/100*floatval($this->discount);
		
		$price+=$this->delivery;
		return round($price,2);
	}
	
	/**
	 * @deprecated 
	 */
	function getDiscountPrice(){
		return $this->getDiscountSum();
	}
	/**
	 * @deprecated 
	 */
	function getTotalPrice(){
		return $this->getTotalSum();
	}
	
	
}