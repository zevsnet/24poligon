<?

use Bitrix\Main\Application;

class CIdexPageDb {

	static $LAST_ERROR;
	static $tableName = "bm_idex_seo_pages";
	static $tableFields = array(
		"ID", 
		"URL",
		"QUERY", 
		"USE_GET", 
		"USE_INHERITANCE",
		"ACTIVE",
		"TITLE",
		"BROWSER_TITLE",
		"KEYWORDS",
		"DESCRIPTION",
		"SEO_TEXT",
        'DOMAIN',
        "SEO_TEXT_2",
	);

	static $page;

    /**
     * CIdexPageDb constructor.
     */
    public static function getDomain()
    {
        $server = Application::getInstance()->getContext()->getServer();

        return $server->getServerName();
    }


    public static function Check($url, $query, $useGet = false, $domain = null) {
		global $DB;
        if (is_null($domain)) {
            $domain = self::getDomain();
        }

		$url = $DB->ForSql($url);
		$query = $DB->ForSql($query);
		$WHERE = array();
		$WHERE[] = "`URL` = '$url'";
		if($useGet == 'Y') {
			$WHERE[] = "`QUERY` = '$query'";
		} else {
			$WHERE[] = "`USE_GET` = 'N'";
		}
		$WHERE = " WHERE " . implode(' AND ', $WHERE)  . " AND `DOMAIN` = '$domain'  ";
		$sql = "SELECT * FROM ".self::$tableName. $WHERE . " LIMIT 1";
		$dbRes = $DB->Query($sql, __LINE__, __FILE__);
		return $dbRes->Fetch();
	}
	
	public static function Get($url, $query, $domain = null) {
		global $DB;


		$url = $DB->ForSql($url);
		$query = $DB->ForSql($query);

		if (is_null($domain)) {
            $domain = self::getDomain();
        }

        $sql = "SELECT * FROM ".self::$tableName
			 . " WHERE `URL` = '$url' AND ((`QUERY` = '$query' AND `USE_GET` = 'Y') OR (`QUERY` = ''  AND `USE_GET` = 'N'))" . " AND `DOMAIN` = '$domain'  "
			 . " ORDER BY `URL` DESC, `QUERY` DESC"
			 . " LIMIT 1 ";



		$dbRes = $DB->Query($sql);

		if(!$arRes = $dbRes->Fetch()) {


			$sql = "SELECT * FROM ".self::$tableName
				 . " WHERE (USE_INHERITANCE = 'Y') AND (('$query' LIKE CONCAT(`QUERY`,'%') AND `USE_GET` = 'Y' AND `URL` = '$url') OR  ('$url' LIKE CONCAT(`URL`,'%') AND `USE_GET` = 'N'))" . " AND `DOMAIN` = '$domain'  "
				 . " ORDER BY `URL` DESC, `QUERY` DESC"
				 . " LIMIT 1";

			$dbRes = $DB->Query($sql);
			$arRes = $dbRes->Fetch();
			if($arRes) {
				$arRes['NO_MATCH'] = 'Y';
			}
		}
		return $arRes;
	}
	

	function GetList($arOrder = array('ID' => 'ASC'), $arFilter = false, $arNavParams=false) {
		global $DB;
		$strOrder = "";
		$strFilter = "";
		$strSelect = "*";
		$strNavi = "";
	
		$arOrder = self::CheckFields($arOrder, "order");
		$arSelect =  self::CheckFields(self::$tableFields, "select");
		
		
		## формируем строку сортировки
		if(is_array($arOrder) && count($arOrder) > 0){			
			foreach($arOrder as $by=>$order){
				if(is_string($order)){
					if(in_array(strtolower($order), array("asc", "desc"))){
						$strOrder1 = "ORDER BY ";
						$strOrder2 .= "`" . $by . "`" . " " . $order . ",";
					}
				}
			}	

			$strOrder = $strOrder1 . substr($strOrder2, 0, -1);
		}	
	
		$strSelect = implode(", ", $arSelect);
		
		
		## формируем строку ограничения		
		if(count($arNavParams) > 0){
			$arNavParams["nPageSize"] = intval($arNavParams["nPageSize"]);			
			$arNavParams["nTopCount"] = intval($arNavParams["nTopCount"]);
			$arNavParams["iNumPage"] = intval($arNavParams["iNumPage"]) > 0 ? intval($arNavParams["iNumPage"])-1:0;			
			//$arNavParams["nPageSize"] = $arNavParams["nTopCount"] < $arNavParams["nPageSize"] ? $arNavParams["nTopCount"]:$arNavParams["nPageSize"];			
			
			if($arNavParams["nPageSize"] > 0) {
				$strNavi = "LIMIT ". ($arNavParams["nPageSize"] * $arNavParams["iNumPage"]) . ", " . $arNavParams["nPageSize"] * ($arNavParams["iNumPage"] + 1);
			} elseif($arNavParams["nTopCount"] > 0){
				$strNavi = "LIMIT " . $arNavParams["iNumPage"] . ", " . $arNavParams["nTopCount"];
			}			
		}
		
		
		## формируем окончательный запрос и выполняем его		
		$query = "SELECT " 
				 . $strSelect 
				 . " FROM " 
				 . self::$tableName . " "
				 . self::MakeFilter($arFilter) . " "
				 . $strOrder . " "
				 . $strNavi;
	
		return $DB->Query($query);	
		
	}
	
	
	public static function GetBlocks($ID) {
		global $DB;
		if(!$ID = intval($ID)) $ID = -1;
		
		$sql = "SELECT * FROM bm_idex_seo_blocks WHERE PAGE_ID = '$ID'";
		$dbRes = $DB->Query($sql);
		while($tmp = $dbRes->Fetch()) {
			$arRes[] = $tmp;
		}
		return $arRes;
	}
	
	
	public static function SetBlocks($ID, $arBlocks) {
		global $DB;
		if(!$ID = intval($ID)) return false;
		self::DeleteBlocks($ID);
		
		$arRes = array();
		foreach($arBlocks as $arBlock) {
			$arInsert = array(
				'PAGE_ID' => $ID,
				'HTML_ID' => "'".$DB->ForSql($arBlock['HTML_ID'])."'",
				'TEXT' => "'".$DB->ForSql($arBlock['TEXT'])."'"
			);
			$blockId = $DB->Insert('bm_idex_seo_blocks', $arInsert, __LINE__);
			
			$arRes[] = $blockId;
		}
		return $arRes;
	}

	public static function DeleteBlocks($ID) {
		global $DB;
		$DB->Query("DELETE FROM `bm_idex_seo_blocks` WHERE `PAGE_ID`=" . $ID);
	}

	public static function Add($arFields) {
		global $DB;
		unset($arFields["ID"]);

        $arFields = self::CheckFields($arFields, "sql");

		$ID = $DB->Insert(self::$tableName, $arFields, __LINE__);
		return $ID;
	}
	
	public static function Update($ID, $arFields) {
		global $DB;
		if(!$ID = intval($ID)) return false;
		unset($arFields["ID"]);
		
		$arFields = self::CheckFields($arFields, "sql");
		
		$DB->Update(self::$tableName, $arFields, "WHERE ID='".$ID."'", __LINE__);
		return $ID;
	}	
	
	public static function Delete($ID) {
		global $DB;
		if(!$ID = intval($ID)) return false;
		$DB->StartTransaction();
			$DB->Query("DELETE FROM ".self::$tableName. " WHERE `ID` = " . $ID . " LIMIT 1");
			self::DeleteBlocks($ID);			
		$DB->Commit();
		return true;	
	}
	
	
	protected function CheckFields($arFields, $mode){
		global $DB;
		$arTableFileds = self::$tableFields;
		$arOrders = array('ASC', 'DESC');
		switch($mode){
			
			case "order":
				foreach($arFields as $code => $val){
					if(!in_array($code, $arTableFileds)){
						unset($arFields[$code]);
					}
					if(!in_array($val = strtoupper($val),$arOrders)) {
						unset($arFields[$code]);	
					} else {
						$arFields[$code] = $val;
					}
				}
			break;	
			
			case "filter":
				foreach($arFields as $code => $val){
					$arCode = self::GetFilterDecimal($code);
					
					if(!in_array($arCode["CODE"], $arTableFileds)){
						unset($arFields[$code]);	
						continue;
					}
				}			
			break;
			
			case "sql":
				foreach($arFields as $code => $val){
					if(!in_array($code, $arTableFileds)){
						unset($arFields[$code]);
						continue;
					}
					
					if(strpos($code, "DATE") !== false) {
						$arFields[$code] = $DB->CharToDateFunction($val);	
					} else {
						$arFields[$code] = "'".$DB->ForSql($val)."'";	
					}
										
				}			
			break;
			
			case "select": 
				foreach($arFields as $k => $code){
					if(!in_array($code, $arTableFileds)){
						unset($arFields[$k]);
						continue;
					}
					
					if(strpos($code, "DATE") !== false) {
						$arFields[$k] = $DB->DateToCharFunction($code) . ' ' . $code;	
					} else {
						$arFields[$k] = "`".$code."`";
					}
										
				}
			break;
			
		}

		return $arFields;
	}


	function MakeFilter($arFilter){		
		global $DB;
		$arFilters = array();
		
		foreach($arFilter as $k => $v) {
			if(!$v) {
				unset($arFilter[$k]);	
			}
		}
		
		if(count($arFilter)){		
			foreach($arFilter as $k => $val){
				if(is_int($k)){
					$arFilters[] = $val;
				} else {
					$arFilters[0]["LOGIC"] = "AND";
					$arFilters[0][0][$k] = $val;
				}
			}
			
			if(count(arFilters)){
				foreach($arFilters as $arFilter){
					$logic = strtoupper($arFilter["LOGIC"]) == "OR" ? "OR":"AND";
	
					$firstLevelFilter = "";			
					unset($arFilter["LOGIC"]);
					
					foreach($arFilter as $arFilterItem){
						$arFilterItem = self::CheckFields($arFilterItem, "filter");
						
						if(count($arFilterItem)){
							$secondLevelFilter = "(";												
							
							foreach($arFilterItem as $code => $value){					
								if(is_string($value) || is_numeric($value)){
									$arClear = self::GetFilterDecimal($code);
									$code = $arClear["CODE"];
									$decimal = $arClear["DECIMAL"];
									
									if(strpos($code, "DATE") !== false) {
										$value = $DB->CharToDateFunction($value);
									} elseif(substr_count($value, "%") > 0){
										$decimal = " ";
										$value = "LIKE('" . $value . "')";
									} else {
										$value = "'" . $value . "'";
									}
									
									$secondLevelFilter .= "`" . strtoupper($code) . "`" . $decimal . $value . " AND ";					
								} elseif(is_array($value)) {
									$arIn = array();
									foreach($value as $vval) {
										$arIn[] = "'" . $vval . "'";
									}
									$arIn = implode(',',$arIn);
									$secondLevelFilter .= "`" . strtoupper($code) . "` IN(" . $arIn . ") AND ";
								}
							}
							
							$firstLevelFilter .= substr($secondLevelFilter, 0, -5) . ") " . $logic . " ";
						}
					}	
					
					$firstLevelFilter = substr($firstLevelFilter, 0, -(strlen($logic) + 2));				
					$arStrFilter[] = "(" . $firstLevelFilter . ")";
				}
				
				if(count($arStrFilter)){				
					return "WHERE " . implode(" AND ", $arStrFilter);
				}	
			}
		}
		
		return " ";
	}
	
	
	protected function GetFilterDecimal($code){
		if(preg_match("/^(<=|>=|>|<|!=)(.+)/", trim($code), $arDecimal)){			
			return array("CODE" => $arDecimal[2], "DECIMAL" => $arDecimal[1]);
		} else {
			return array("CODE" => $code, "DECIMAL" => "=");			
		}
	}

	
}

?>