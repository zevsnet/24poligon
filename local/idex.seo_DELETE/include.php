<?
IncludeModuleLangFile(__FILE__);

$arClasses = array(
	"CIdexSeo" => "classes/general/cidexseo.php",
	"CIdexSeoPage" => "classes/general/cidexseopage.php",
	"CIdexPageDb" => "classes/mysql/dbpage.php"
);

CModule::AddAutoloadClasses("idex.seo", $arClasses);

?>