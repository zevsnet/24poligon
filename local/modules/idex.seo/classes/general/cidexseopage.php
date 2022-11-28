<?

use Bitrix\Main\Application;

class CIdexSeoPage {
	public $path;
	public $query;	
	public static $delParams = array(
		'login', 'back_url_admin', 'clear_cache', 'logout_butt', 'bitrix_include_areas'
	);
	
	function __construct($url) {
		$arUrl = parse_url($url);
		$this->path = $arUrl['path'];	
		$this->query = self::PrepareGet($arUrl['query'], self::$delParams);
		$this->fields = self::Get($this->path, $this->query);
		if($this->fields['ID']) {
			$this->htmlBlocks = self::GetBlocks($this->fields['ID']);			
		}
	}


    public function getFields()
    {
        return $this->fields;
    }


    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }


	
	public static function PrepareGet($query, $delParams = false) {
		if(! $delParams ) return $query;
		$newGet = array();
		$arGet = explode('&', $query);
		foreach($arGet as $var) {
			$found = false;
			list($key, $value) = explode('=', $var);
			if(!$key) continue;
			foreach($delParams as $param) {
				$key = preg_replace('/\[([0-9]*)\]/is','',$key);
				if(strcasecmp($param, $key) == 0) {
					$found = true;
					break;
				}
			}
			if(!$found) {
				$newGet[$key] = $var;
			}
		}		
		ksort($newGet);
		return implode('&', $newGet);
	}
	
	public static function Get($path, $query) {
	    $arPage = CIdexPageDb::Get($path, $query);
	    return $arPage;
	}
	
	public static function GetBlocks($ID) {		
		$arBlocks = CIdexPageDb::GetBlocks($ID);
		return $arBlocks;		
	}
	
	public static function Delete($ID) {
		$res = CIdexPageDb::Delete($ID);
		return $res;		
	}	
	
	public static function Add($arFields) {

		
		if(isset($arFields['USE_GET'])) {
			$arFields['USE_GET'] = ($arFields['USE_GET'] == 'Y') ? 'Y' : 'N';
		}
		if(isset($arFields['USE_INHERITANCE'])) {
			$arFields['USE_INHERITANCE'] = ($arFields['USE_INHERITANCE'] == 'Y') ? 'Y' : 'N';
		}
		if(isset($arFields['ACTIVE'])) {
			$arFields['ACTIVE'] = ($arFields['ACTIVE'] == 'Y') ? 'Y' : 'N';
		}

		if($arFields['USE_GET'] == 'N') {
			$arFields['QUERY'] = '';
		}

        $server = Application::getInstance()->getContext()->getServer();

        $arFields['DOMAIN'] = $server->getServerName();

		$arPage = CIdexPageDb::Check($arFields['URL'], $arFields['QUERY'], $arFields['USE_GET']);



        if($arPage['ID']) {
			unset($arFields['ID']);
			foreach($arFields as $k => $v) {
				$arPage[$k] = $v;
			}
			$arFields = $arPage;
			$ID = CIdexPageDb::Update($arFields['ID'], $arFields);
		} else {
			$ID = CIdexPageDb::Add($arFields);
		}
		
		if($ID)  {
			$arBlocks = array();
			foreach($arFields['HTML_BLOCK_ID'] as $k => $value)  {
				if(!$value) continue;
				$arBlocks[] = array(
					'HTML_ID' => $value,
					'TEXT' => $arFields['HTML_BLOCK_TEXT'][$k]
				);
			}
			CIdexPageDb::SetBlocks($ID, $arBlocks);
		}
		
		return $res;			
	}
	
	function GetList($arOrder, $arFilter) {
		return CIdexPageDb::GetList($arOrder, $arFilter);
	}

}