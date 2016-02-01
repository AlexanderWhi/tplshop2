<?php
include_once 'core/component/AdminComponent.class.php';
class Files extends AdminComponent {
	protected $mod_name='Файлы';
	protected $mod_title='Файловый архив';
	
	function actDefault(){
		global $get;
		
		
		$data=array(
			'rs'=>array(),
			'mode'=>$mode=$get->get('mode'),
			'path'=>$get->get('path'),
			'from'=>$get->get('from'),
		);
		
		
		$path="";
		if($get->exists("path")){
			$path=$get->get("path")."/";
		}
		$sPath     = $this->m_dir; 
		if($path!=""){
			$sPath.="/".$path;
		}
		if(file_exists($sPath)){
			$dDir      = opendir($sPath); 
			//realpath (".");
			while ($sFileName=readdir($dDir)) 
			{ 
				$sFilePath=$sPath."/".$sFileName;
				if ($sFileName!='.' && $sFileName!='..' && is_dir($sFilePath)) 
				{
					
					$item=array("name"=>$sFileName,
						"path"=>$path.$sFileName,
						"full_path"=>realpath ($sFilePath),
						"is_dir"=>is_dir($sFilePath),
						"size"=>filesize($sFilePath),
						"perm"=>substr(sprintf('%o', fileperms($sFilePath)), -4),
						"modify"=>date("d.m.Y H:i:s",filemtime($sFilePath)),
						"create"=>date("d.m.Y H:i:s",fileatime($sFilePath))
					);
					if($item['is_dir']){
//						$item['size']=disk_total_space($item['full_path']);
					}
					$data['rs'][]=$item;
				} 
			}
			rewinddir ($dDir);
			while ($sFileName=readdir($dDir)) 
			{ 
				$sFilePath=$sPath."/".$sFileName;
				if ($sFileName!='.' && $sFileName!='..' && is_file($sFilePath)) 
				{ 	         	
					if($mode=='img' && !preg_match('/(png|jpg|gif)$/i',$sFileName)){
						continue;
					}
					$item=array("name"=>$sFileName,
					"path"=>$path.$sFileName,
					"full_path"=>realpath ($sFilePath),
					"is_dir"=>is_dir($sFilePath),
					"size"=>filesize($sFilePath),
					"perm"=>substr(sprintf('%o', fileperms($sFilePath)), -4),
					"modify"=>date("d.m.Y H:i:s",filemtime($sFilePath)),
					"create"=>date("d.m.Y H:i:s",fileatime($sFilePath))
					);
					
					
					$data['rs'][]=$item;
				} 
			}
			closedir ($dDir);
		}
		$this->updateFileExplorer();
		$this->display($data,dirname(__FILE__).'/files.tpl.php');
	}
	public $m_rootDir="";
	
	public $m_dir=".";
	
//	protected $m_collection=array();
	
//	function onInit(ArgumentList $args){
//		if($args->exists("dir")){
//			$this->m_dir=$args->getArgument("dir");
//		}
//	}
	
	function actRename(){
		global $post;
		$path=$post->get('path');
		if(empty($path)){
			$path='.';
		}
		rename($path.'/'.$post->get('oldname'),$path.'/'.$post->get('newname'));
		echo 'ok';exit;
	}
		
	function actRemove(){
		global $get,$post;
		$path=$post->get('path');
		if(empty($path)){
			$path='.';
		}
		
		if($post->getArray('item')){
			foreach ($post->getArray('item') as $item) {
				if(file_exists($item)){
					if(is_file($item)){
						unlink($item);
					}else{
						rmdir ($item);
					}
				}
			}
			echo printJSON(array('item'=>$post->getArray('item')));exit;
		}else{
			$sPath=$path.'/'.$post->get('name');
			if(file_exists($sPath)){
				if(is_file($sPath)){
					unlink($sPath);
				}else{
					rmdir ($sPath);
				}
			}
		}
		
		
		echo 'ok';exit;
	}
	
	function actRemoveAll(){
		global $get,$post;
		$path=$post->get('path');
		if(empty($path)){
			$path='.';
		}
		$sPath=$path.'/'.$post->get('name');
		if(is_dir($sPath)){
			$d=opendir($sPath);
			while ($f=readdir($d)) {
				if(is_file($sPath.'/'.$f) && !preg_match('/^\./i',$f)){
					@unlink($sPath.'/'.$f);
				}
			}
		}
		
	}
	
	function refresh(){
		global $get;
		
		$mode=$get->get('mode');
		
		$path="";
		if($get->exists("path")){
			$path=$get->get("path")."/";
		}
		$sPath     = $this->m_dir; 
		if($path!=""){
			$sPath.="/".$path;
		}
		if(file_exists($sPath)){
			$dDir      = opendir($sPath); 
			//realpath (".");
			while ($sFileName=readdir($dDir)) 
			{ 
				$sFilePath=$sPath."/".$sFileName;
				if ($sFileName!='.' && $sFileName!='..' && is_dir($sFilePath)) 
				{
					
					$item=array("name"=>$sFileName,
						"path"=>$path.$sFileName,
						"full_path"=>realpath ($sFilePath),
						"is_dir"=>is_dir($sFilePath),
						"size"=>filesize($sFilePath),
						"perm"=>substr(sprintf('%o', fileperms($sFilePath)), -4),
						"modify"=>date("d.m.Y H:i:s",filemtime($sFilePath)),
						"create"=>date("d.m.Y H:i:s",fileatime($sFilePath))
					);
					if($item['is_dir']){
//						$item['size']=disk_total_space($item['full_path']);
					}
					$this->m_collection[]=$item;
				} 
			}
			rewinddir ($dDir);
			while ($sFileName=readdir($dDir)) 
			{ 
				$sFilePath=$sPath."/".$sFileName;
				if ($sFileName!='.' && $sFileName!='..' && is_file($sFilePath)) 
				{ 	         	
					if($mode=='img' && !preg_match('/(png|jpg|gif)$/')){
						continue;
					}
					$item=array("name"=>$sFileName,
					"path"=>$path.$sFileName,
					"full_path"=>realpath ($sFilePath),
					"is_dir"=>is_dir($sFilePath),
					"size"=>filesize($sFilePath),
					"perm"=>substr(sprintf('%o', fileperms($sFilePath)), -4),
					"modify"=>date("d.m.Y H:i:s",filemtime($sFilePath)),
					"create"=>date("d.m.Y H:i:s",fileatime($sFilePath))
					);
					
					
					$this->m_collection[]=$item;
				} 
			}
			closedir ($dDir);
		}
		$this->updateFileExplorer();
	}
	
	
	function actSubmit(){
		global $get,$post;
		if($post->exists('upload')){
			$sPath     = $this->m_dir; 
			if($post->exists('path')){
				$sPath.="/".$post->get('path');
			}
			if(!file_exists($sPath)){
				mkdir($sPath,0666,true);
			}
			foreach ($_FILES['userfile']['tmp_name'] as $k=>$tmp_name) {
				if($tmp_name){
					move_uploaded_file($tmp_name,$sPath.'/'.$_FILES['userfile']['name'][$k]);
				}
				
			}
			
		}elseif($post->exists('create')){
			$sPath     = $this->m_dir;
			if($get->exists('path')){
				$sPath.="/".$args->getArgument('path');
			} 
			if($post->exists('dirname')){
				$sPath.="/".$post->getArgument('dirname');
			}
			mkdir($sPath);
			header("location: .");exit;
		}
				
		header("location: {$_SERVER['HTTP_REFERER']}");exit;
	}
	
	
	/**
	 * @var Collection
	 */
	private $fileExplorer=array();
	
	
	function appendFileExplorer($name,$path){
		
		$this->fileExplorer[]=array("name"=>$name,"path"=>$path);

	}
	function renderFileExplorer(){
		global $get;
	
		$output='';
		foreach ($this->fileExplorer as $v){
			if($output!=''){
				$output.= ' / ';
			}
			if($v["path"]==$get->get("path")){
				$output.= '<span>'.$v["name"].'</span>';
			}else{
				if($v["path"]==""){
					$output.= '<a href="?">'.$v["name"].'</a>';
				}else{
					$output.= '<a href="?path='.$v["path"].'">'.$v["name"].'</a>';
				}
			}
		}
		return $output;
	}
	
	function updateFileExplorer(){
		global $get;
		
		$path=$get->get("path");
		
		$temp=explode('/',$path);
		$this->appendFileExplorer("#","");
		$currentPath="";
		for($i=0;$i<count($temp);$i++){
			$currentPath.=$temp[$i];
			$this->appendFileExplorer($temp[$i],$currentPath);
			$currentPath.="/";
		}
	}
	
	function actEdit(){
		global $get;
		$data=array(
			'path'=>$get->get('path'),
			'content'=>file_get_contents ($get->get('path'))
		);
		$this->explorer[]=array('name'=>'Редактировать');
		$this->display($data,dirname(__FILE__).'/files_edit.tpl.php');
	}
	function actSave(){
		global $post;
		copy($post->get('path'),$post->get('path').'.'.time().'.back');
		$fp = fopen ($post->get('path'), "w");
		fwrite($fp,$post->get('content'));
		fclose($fp);
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	
	
	function actNewfolder(){
		global $post;
		if(empty($_POST['path'])){
			$path='.';
		}else{
			$path=$_POST['path'];
		}
		mkdir($path.'/'.$post->get('name'));
		echo printJSON(execTime()) ;
	}
	
	function actArchive(){
		
		if (substr($file, -4) =='.zip'){
			$zip = new ZipArchive;
			if ($zip->open($file) === TRUE) {
				$zip->extractTo($this->path.$this->pathFile);
				$zip->close();
				$this->log->logFile("файл ".$file.' распакован');
				return true;
			} else {
				$this->log->logFile("ОШИБКА файл ".$file.' НЕ распакован');
				return false;
			};
		}
	}
	/**
	 * http://htmlweb.ru/php/function/ziparchive.php
	 *
	 */
	function actCreateArchive(){
		
		global $get,$post;
		
		function archDir($dir,&$zip,$baseDir){
			$d=opendir($dir);
			;
			while (false!==($file=readdir($d))) {
				if($file!='.' && $file!='..'){
					if(is_dir($dir.'/'.$file)){
						archDir($dir.'/'.$file,$zip,$baseDir);
					}else{
						$zip->addFile($dir.'/'.$file,str_replace($baseDir.'/','',$dir.'/'.$file));
					}
				}
			}
			closedir($d);
		}
		
		$zip = new ZipArchive();
		
		$path=$get->get('path');
		if(empty($path)){$path='.';}
		$sPath=$get->get('path');
		$filename = $get->get('path').".zip";
		if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE)die("cannot open <$filename>\n");
		if(file_exists($sPath)){
			if(is_file($sPath)){
				$zip->addFile($path ,$get->get('name'));
			}else{
				archDir($sPath,$zip,$sPath);
			}
		} 
		$zip->close();
		echo "numfiles: " . $zip->numFiles . "\n";echo "status:" . $zip->status . "\n";exit;
	}
	
	function actDownloadGZ(){
		global $get;
		$path=$get->get('path');
		$name=$get->get('name');
		header('Content-Type: application/octet-stream' );
		header('Content-Disposition: attachment; filename="' . $name . '.gz";');
		header( "Content-Encoding: gzip" );
		
		$content=gzcompress(file_get_contents($path),3);
		file_put_contents($path.'.gz',$content);
		
		header( "Content-Length: " . strlen($content) );
		echo $content;exit;
		
	}	
	
	function actDownload(){
		global $get;
		$path=$get->get('path');
		$name=$get->get('name');
		header('Content-Type: application/octet-stream' );
		header('Content-Disposition: attachment; filename="' . $name . '";');
		
		
//		$content=gzcompress(file_get_contents($path),3);
		$content=file_get_contents($path);
		
		
		header( "Content-Length: " . strlen($content) );
		echo $content;exit;
		
	}
	
	function actUploadFileForm(){
		global $get;
		$ext=file_ext($get->get('file_name'));
		
		$data=array(
			'file_name'=>$get->get('file_name'),
			'ext'=>$ext,
			'title'=>$get->get('title'),
		);
		$this->display($data,dirname(__FILE__).'/upload_file.tpl.php');
	}
	
	function actUploadFile(){
		global $post;
		move_uploaded_file($_FILES['userfile']['tmp_name'],$post->get('file_name'));
		echo printJSONP(array('msg'=>'Загружено'));exit; ;
	}
}
?>