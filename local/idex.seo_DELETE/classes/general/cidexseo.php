<?
IncludeModuleLangFile(__FILE__);

class CIdexSeo
{

    public static $MODULE_ID = 'idex.seo';
    public static $obPage;

    function ShowPanel()
    {
        global $APPLICATION; // Добавление кнопки в панель управления

        $POST_RIGHT = $APPLICATION->GetGroupRight("idex.seo");
        if ($POST_RIGHT == "D") return;

        $APPLICATION->AddPanelButton(array(
            "HREF" => "javascript:CIdexSeo.ShowDialog()",
            "TEXT" => GetMessage("IDEX_SEO_PANEL_TEXT"),
            "SRC" => "/bitrix/themes/.default/icons/" . self::$MODULE_ID . "/idex_seo_24_22.png",
            "ALT" => GetMessage("IDEX_SEO_PANEL_TEXT"),
            "MAIN_SORT" => 300,
            "SORT" => 100
        ));


        if (COption::GetOptionString(self::$MODULE_ID, "add_jquery", "N") == 'Y') {
            CJSCore::Init(array("jquery"));
        }

        $APPLICATION->AddHeadString("<script src='/bitrix/js/" . self::$MODULE_ID . "/idex_seo.js?v=1' charset='utf-8' encoding='utf-8'></script>");
        $APPLICATION->AddHeadString('<link href="/bitrix/themes/.default/idex.seo.css" type="text/css" rel="stylesheet" />');


    }

    function GetPage()
    {
        if (!self::$obPage) {

            self::$obPage = new CIdexSeoPage($_SERVER['REDIRECT_URL']
                ? $_SERVER['REDIRECT_URL']
                : $_SERVER['REQUEST_URI']);
        }
        return self::$obPage;
    }

    function SetSeoParams()
    {
        // только для клиентской части сайта
        if (defined("ADMIN_SECTION") || ADMIN_SECTION === true || strpos($_SERVER['PHP_SELF'], "/bitrix/admin") === true) return;
        global $APPLICATION;
        $obPage = self::GetPage();

        $hasPage = true;
        if (!$obPage || !$obPage->fields) {
            $hasPage = false;
        }
        if ($obPage->fields['ACTIVE'] == 'N') {
            $hasPage = false;
        }

        if ($hasPage) {
            if ($obPage->fields['TITLE']) {
                $APPLICATION->SetTitle($obPage->fields['TITLE']);
            }
            if ($obPage->fields['BROWSER_TITLE']) {
                $APPLICATION->SetPageProperty("title", $obPage->fields['BROWSER_TITLE']);
            }
            if ($obPage->fields['DESCRIPTION']) {
                $APPLICATION->SetPageProperty("description", $obPage->fields['DESCRIPTION']);
            }
            if ($obPage->fields['KEYWORDS']) {
                $APPLICATION->SetPageProperty("keywords", $obPage->fields['KEYWORDS']);
            }

            $city = $APPLICATION->GetPageProperty('#incity#');
            $seoText = str_replace('#incity#', $city, $obPage->fields['SEO_TEXT']);

            if ($obPage->fields['SEO_TEXT']) {
                $APPLICATION->SetPageProperty("idex_seo_text", '<div class="idex_sub_text_1">' . $seoText . '</div>');
            }
            if ($obPage->fields['SEO_TEXT_2']) {
                $APPLICATION->SetPageProperty("idex_seo_text_2", '<div class="idex_sub_text_2">' . $obPage->fields['SEO_TEXT_2'] . '</div>');
            }
        }

        if (($page = $_REQUEST['PAGEN_1']) > 0) {
            $APPLICATION->SetPageProperty("title", $APPLICATION->GetTitle(false) . ' – страница ' . $page);
        }

    }

    function ReplaceContent($content)
    {
        // только для клиентской части сайта
        if (defined("ADMIN_SECTION") || ADMIN_SECTION === true || strpos($_SERVER['PHP_SELF'], "/bitrix/admin") === true) return;


        $obPage = self::GetPage();
        if (!$obPage) {
            return;
        }
        if ($obPage->fields['ACTIVE'] == 'N') {
            return;
        }
        if ($obPage->htmlBlocks) {
//            foreach ($obPage->htmlBlocks as $val) {
//                $content = self::ReplaceTagConent($content, $val['HTML_ID'], $val['TEXT']);
//            }
        }
    }


    function json_encode($arr)
    {
        $parts = array();
        $is_list = false;

        if (!is_array($arr)) return;
        if (count($arr) < 1) return '{}';

        //Find out if the given array is a numerical array
        $keys = array_keys($arr);
        $max_length = count($arr);

        if (($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
            $is_list = true;
            for ($i = 0; $i < count($keys); $i++) { //See if each key correspondes to its position
                if ($i != $keys[$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }
        foreach ($arr as $key => $value) {
            if (is_array($value)) { //Custom handling for arrays
                if ($is_list) $parts[] = self::json_encode($value); /* :RECURSION: */
                else $parts[] = '"' . $key . '":' . self::json_encode($value); /* :RECURSION: */
            } else {
                $str = '';
                if (!$is_list) $str = '"' . $key . '":';
                //Custom handling for multiple data types
                if (is_numeric($value)) $str .= $value; //Numbers
                elseif ($value === false) $str .= 'false'; //The booleans
                elseif ($value === true) $str .= 'true';
                else $str .= '"' . addslashes($value) . '"'; //All other things
                // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
                $parts[] = $str;
            }
        }
        $json = implode(',', $parts);
        if ($is_list) return '[' . $json . ']';//Return numerical JSON
        return '{' . $json . '}';//Return associative JSON
    }

    public static function ReplaceTagConent($Str, $TagId, $NewContent)
    {

        if (preg_match("/[\s\S]*<(\w+)[^<]+id[\s]*=[\s]*['\"]?" . $TagId . "['\"\s][^>]*(>)/i", $Str, $matches, PREG_OFFSET_CAPTURE)) // Все что до нашего тега
        {
            $tag = $matches[1][0];
            $EndPos = $matches[2][1] + 1;

            if (preg_match("/<\/" . $tag . "[\s]*>[\s\S]*/i", $Str, $MX, PREG_OFFSET_CAPTURE, $EndPos)) { // Все что после нашего тега
                return $matches[0][0] . $NewContent . $MX[0][0];
            } else
                return $Str;
        } else
            return $Str;
    }

}

?>