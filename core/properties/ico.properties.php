<?php
define("ICO_PATH","/img/admin/ico");
define("ICO_F_GIF_16","f_gif_16.gif");
define("ICO_F_JPG_16","f_jpg_16.gif");
define("ICO_F_PNG_16","f_png_16.gif");
define("ICO_F_TXT_16","f_txt_16.gif");
define("ICO_F_JS_16","f_js_16.gif");
define("ICO_F_SWF_16","f_swf_16.gif");
define("ICO_F_PHP_16","f_php_16.gif");
define("ICO_F_XML_16","f_xml_16.gif");
define("ICO_F_ZIP_16","f_zip_16.gif");
define("ICO_F_UNKNOWN_16","f_unknown_16.gif");

define("ICO_T_FOLDER_16","t_folder_16.gif");

define("ICO_MINUS","i_minus.png");

define("ICO_PLUS","i_plus.png");
//////////////////////////////////////////////////
global $ico_path;
$ico_path=ICO_PATH;

global $ico_format_16;
$ico_format_16=array();
$ico_format_16["gif"]="f_gif_16.gif";
$ico_format_16["jpg"]="f_jpg_16.gif";
$ico_format_16["png"]="f_png_16.gif";
$ico_format_16["xml"]=ICO_F_XML_16;
$ico_format_16["txt"]="f_txt_16.gif";

$ico_format_16["js"]="f_js_16.gif";
$ico_format_16["swf"]="f_swf_16.gif";
$ico_format_16["php"]="f_php_16.gif";
$ico_format_16["zip"]="f_zip_16.gif";
$ico_format_16["unknown"]="f_unknown_16.gif";

global $ico_type_16;
$ico_type_16=array();
$ico_type_16["folder"]="t_folder_16.gif";
?>