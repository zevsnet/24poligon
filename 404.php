<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
?>
<?if(
    //strpos($APPLICATION->GetCurPage(),'news_product') !==false ||
    strpos($APPLICATION->GetCurPage(),'rosgvardiya') !== false ||
    strpos($APPLICATION->GetCurPage(),'hit_product') !== false
){
    $obSection = new CIBlockSection();
    $obSection->Update(3391,['ACTIVE'=>'Y']);
    $obSection->Update(3400,['ACTIVE'=>'Y']);
    header("Refresh:0");
}?>
    <style>.right_block.wide_, .right_block.wide_N{float:none !important;width:100% !important;}.top-block-wrapper{display: none;}</style>
    <div class="maxwidth-theme">
        <div class="page_error_block">
            <div class="page_not_found" width="100%" border="0" cellpadding="0" cellspacing="0">
                <div class="image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="272" height="86" viewBox="0 0 272 86">
                        <path id="Ellipse_296_copy_6" data-name="Ellipse 296 copy 6" class="cls-1" d="M1087.5,395H1081v8.5a8.5,8.5,0,0,1-17,0V395h-38.5a8.477,8.477,0,0,1-7.14-3.9,0.673,0.673,0,0,1-.07-0.11c-0.11-.187-0.23-0.375-0.33-0.571-0.04-.084-0.08-0.17-0.12-0.255-0.07-.154-0.15-0.307-0.21-0.465-0.05-.125-0.1-0.252-0.14-0.378s-0.09-.249-0.13-0.377c-0.05-.166-0.09-0.335-0.13-0.5-0.02-.094-0.05-0.186-0.07-0.282q-0.06-.319-0.09-0.645l-0.03-.17c-0.02-.256-0.03-0.514-0.04-0.771V386.49c0-.212.02-0.424,0.03-0.636a8.473,8.473,0,0,1,2-4.857l36.29-51.353a8.679,8.679,0,0,1,11.96-2.157,8.385,8.385,0,0,1,2.19,11.781L1042.1,378H1064v-8.5a8.5,8.5,0,0,1,17,0V378h6.5A8.5,8.5,0,0,1,1087.5,395ZM960,412a43,43,0,1,1,43-43A43,43,0,0,1,960,412Zm0-69a26,26,0,1,0,26,26A26,26,0,0,0,960,343Zm-65.5,52H888v8.5a8.5,8.5,0,0,1-17,0V395H832.5a8.489,8.489,0,0,1-7.142-3.9c-0.023-.036-0.044-0.074-0.067-0.11-0.116-.187-0.227-0.375-0.329-0.571-0.044-.084-0.082-0.17-0.123-0.255-0.074-.154-0.147-0.307-0.212-0.465-0.05-.125-0.093-0.252-0.138-0.378s-0.09-.249-0.129-0.377c-0.05-.166-0.089-0.335-0.129-0.5-0.022-.094-0.048-0.186-0.067-0.282-0.042-.213-0.072-0.429-0.1-0.645-0.007-.057-0.018-0.113-0.023-0.17-0.026-.256-0.038-0.514-0.039-0.771,0-.024,0-0.047,0-0.071v-0.01c0-.212.016-0.424,0.033-0.636a8.454,8.454,0,0,1,2-4.857l36.293-51.353a8.681,8.681,0,0,1,11.961-2.157,8.389,8.389,0,0,1,2.191,11.781L849.1,378H871v-8.5a8.5,8.5,0,0,1,17,0V378h6.5A8.5,8.5,0,0,1,894.5,395Z" transform="translate(-824 -326)"/>
                    </svg>
                </div>
                <div class="description">
                    <div class="subtitle404">Страница не найдена</div>
                    <div class="descr_text404">Неправильно набран адрес или такой страницы не существует</div>
                    <a class="btn btn-transparent-border-color btn-mainpage" onclick="history.back()">вернуться назад</a>
                    <a class="btn btn-default btn-mainpage" href="<?=SITE_DIR?>"><span>На главную</span></a>
                </div>
            </div>
        </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>