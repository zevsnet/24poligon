<?
$sSectionName = "Каталог";
$arDirProperties = Array(
   "viewed_show" => "Y",
   "MENU_SHOW_SECTIONS" => "Y",
   "HIDE_LEFT_BLOCK" => "N"
);
if($_SERVER['REQUEST_URI'] == '/catalog/?q=rasprodazha'){
    $arDirProperties['WIDE_PAGE']='N';
    $arDirProperties['HIDE_LEFT_BLOCK']='Y';
}

