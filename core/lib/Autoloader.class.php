<?php
class Autoloader{
	public static function Register() {
        if (function_exists('__autoload')) {
            //    Register any existing autoloader function with SPL, so we don't get any clashes
            spl_autoload_register('__autoload');
        }
        //    Register ourselves with SPL
        return spl_autoload_register(array('Autoloader', 'Load'));
    }   //    function Register()


    /**
     * Autoload a class identified by name
     *
     * @param    string    $pClassName        Name of the object to load
     */
    public static function Load($pClassName){
        if ((class_exists($pClassName,FALSE)) ) {
            //    Either already loaded, or not a PHPExcel class request
            return FALSE;
        }

        if((strpos($pClassName, 'Lib') !== 0)){
        	$pClassFilePath = "core/lib/$pClassName.class.php";
        }else{
        	$module=strtolower(preg_replace('/^Lib/','',$pClassName));
        	 $pClassFilePath = "modules/$module/$pClassName.class.php";
        }
        $pClassFilePath=ROOT."/".$pClassFilePath;
        if ((file_exists($pClassFilePath) === FALSE) || (is_readable($pClassFilePath) === FALSE)) {
            //    Can't load
            return FALSE;
        }

        require($pClassFilePath);
    }   //    function Load()
}?>