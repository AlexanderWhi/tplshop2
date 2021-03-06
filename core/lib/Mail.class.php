<?php
class Mail{
	
	private $smtp_addr='ekt-mail.ekt.u-sc.local';
	
	private $theme='';
	private $body='';
	
	private $fromMail=FROM_MAIL;
	
	private $enc='WINDOWS-1251';
	
	function setTemplate($templateName,$varList=null){
		
		$rs=DB::select("SELECT * FROM sc_letter_template WHERE name='".$templateName."'");
		if($rs->next()){
			$this->theme=$rs->get('theme');
			$this->body=$rs->get('body');
			if(is_array($varList)){
				foreach($varList as $k=>$v){
					$this->theme=str_replace('%'.strtoupper($k).'%',$v,$this->theme);
					$this->body=str_replace('%'.strtoupper($k).'%',$v,$this->body);
				}
			}
		}else{
			$this->theme=$templateName;
			$this->body=print_r($varList,true);
		}
	}
	
	private $attachment=array();
	
	function addAttachment($a){
		$this->attachment[]=$a;
	}
	
	
	function setFromMail($mail){
		$this->fromMail=$mail;
	}
	
	function getFromMail(){
		return $this->fromMail;
	}
	
	function setTemplateBody($body,$varList=null){
		$this->body=$body;
		if(is_array($varList)){
			foreach($varList as $k=>$v){
				$this->body=str_replace('%'.strtoupper($k).'%',$v,$this->body);
			}
		}
	}
	function setTheme($theme,$varList=null){
		$this->theme=$theme;
		if(is_array($varList)){
			foreach($varList as $k=>$v){
				$this->theme=str_replace('%'.strtoupper($k).'%',$v,$this->theme);
			}
		}
	}

	function send($to,$varList=null){
		$theme=$this->theme;
		$body=$this->body;
		if(is_array($varList)){
			foreach($varList as $k=>$v){
				$theme=str_replace('%'.strtoupper($k).'%',$v,$theme);
				$body=str_replace('%'.strtoupper($k).'%',$v,$body);
			}
		}

		$body = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=Windows-1251'/></head><body>".$body."</body></html>";
		$headers  = "Content-type: text/html; charset=Windows-1251 \r\n"; 
		$headers .= "From: ".$this->fromMail." <".$this->fromMail.">\r\n"; 
		
		$theme = '=?Windows-1251?B?'.base64_encode($theme).'?='; 	

		if(is_array($to)){
				foreach($to as $mail){
					mail($mail, $theme, $body, $headers);
				}
		}else{
			mail($to, $theme, $body, $headers);
		}
	}
	
	function sendTemplateMail($to,$templateName,$varList=null){
		$rs=DB::select("SELECT * FROM sc_letter_template WHERE name='".$templateName."'");
		if($rs->next()){
			$theme=$rs->get('theme');
			$body=$rs->get('body');
			if(is_array($varList)){
				foreach($varList as $k=>$v){
					$theme=str_replace('%'.strtoupper($k).'%',$v,$theme);
					$body=str_replace('%'.strtoupper($k).'%',$v,$body);
				}
			}
			$body = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=Windows-1251'/></head><body>".$body."</body></html>";
			$headers  = "Content-type: text/html; charset=Windows-1251 \r\n"; 
			$headers .= "From: ".$this->fromMail." <".$this->fromMail.">\r\n"; 
				
			$theme = '=?Windows-1251?B?'.base64_encode($theme).'?='; 
	
			mail($to, $theme, $body, $headers);
		}
	}

	function xsend($to,$varList=null){
		$theme=$this->theme;
		$body=$this->body;
		if(is_array($varList)){
			foreach($varList as $k=>$v){
				$theme=str_replace('%'.strtoupper($k).'%',$v,$theme);
				$body=str_replace('%'.strtoupper($k).'%',$v,$body);
			}
		}
		$img=array();
		if(preg_match_all('! src="([^"]+)"!',$body,$res)){
			for($i=0;$i<count($res[0]);$i++){
				$path=$res[1][$i];
				$newPath=str_replace('http://'.$_SERVER['HTTP_HOST'],'',$path);
				$img[]=$newPath;
				$body=str_replace($path,'cid:'.basename($newPath),$body);	
			}
		}
		$body=str_replace('/>','>',$body);
		
		$body='<html><head>
		<style>
		a img{border:none}
		th{text-align:left;}
		body{font-family: Tahoma;}
		</style>
		
		</head><body>'.$body.'</body></html>';
		
		$subj= "=?Windows-1251?B?".base64_encode($theme).'?=';
		
		$n="\n";
		
	    $un        = 'b1_'.strtoupper(uniqid(time())); 
	    $un2       = 'b2_'.strtoupper(uniqid(time()+1000)); 
	    if(is_array($this->fromMail)){
	    	$head      = "From: =?Windows-1251?B?".base64_encode($this->fromMail[0])."?= <{$this->fromMail[1]}>$n";
	    }else{
	    	$head      = "From: {$this->fromMail}$n";
	    }
	     
	    $head     .= "To: $to$n"; 
	    $head     .= "Subject:".$subj."$n"; 
	    $head     .= "X-Mailer: PHPMail Tool$n"; 
//	    $head     .= "Reply-To: $this->fromMail$n"; 
	    $head     .= "Mime-Version: 1.0$n"; 
	    $head     .= "Content-Type:multipart/related;"; 
	    $head     .= "type=\"text/html\";";
	    
	    $head     .= "boundary=\"----------".$un."\"$n$n"; 
	    //////////////////////////////////////////////////////////
	    $zag     = "------------".$un."$n";
	    $zag     .= "Content-Type: multipart/alternative;";
		$zag     .= "boundary=\"----------".$un2."\"$n$n";
	    
	    $zag      .= "------------".$un2.$n."Content-Type:text/html;charset = \"Windows-1251\"";
	    $zag      .= "Content-Transfer-Encoding: 8bit$n$n$body$n$n"; 
	    $zag      .= "------------".$un2."--$n$n";
	    
	    foreach($img as $filename){
//	    	$filename=ROOT.$filename;
	    
	    	if(file_exists(ROOT.$filename)){
		    	$filename=ROOT.$filename;
		    }else{
		    	if(isImg($filename)){
		    		$filename="http://{$_SERVER['HTTP_HOST']}".$filename;
		    	}
		    }
	    	
		    $zag      .= "------------".$un."$n"; 
	 
		    $zag      .= "Content-Type: application/octet-stream;"; 
		    $zag      .= "name=\"".basename($filename)."\"$n";
		    $zag      .= "Content-ID: <".basename($filename).">$n"; 
		    $zag      .= "Content-Disposition:inline;"; 
		    $zag      .= "filename=\"".basename($filename)."\"$n";
		    $zag      .= "Content-Transfer-Encoding:base64$n$n";
		    
		    $zag      .= chunk_split(base64_encode(file_get_contents($filename)))."$n"; 
	    }	    
	    foreach ($this->attachment as $a) {
	    	$zag      .= "------------".$un."$n"; 
	 
		    $zag      .= "Content-Type: application/octet-stream;"; 
		    $zag      .= "name=\"".$a['name']."\"$n";
		    $zag      .= "Content-ID: <".$a['name'].">$n"; 
		    $zag      .= "Content-Disposition:inline;"; 
		    $zag      .= "filename=\"".$a['name']."\"$n";
		    $zag      .= "Content-Transfer-Encoding:base64$n$n";
		    $zag      .= chunk_split(base64_encode(file_get_contents($a['file'])))."$n"; 
	    }
	    
	    $zag      .= "------------".$un."--$n"; 	    
	    return @mail("$to", "$subj", $zag, $head); 
	}
}
?>