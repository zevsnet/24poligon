<?

/**
 * Class CDbNumberOperator
 * Class to work with props type number
 */
class CDbNumberOperator extends CDbBaseOperator
{

	protected static $type = 'N';

	/**
	 * Function will return list of props and number of products
	 * @param  int $iblockId
	 * @return array
	 */
	public function getPropsList($iblockId, $elementId) {

		$elFilter = !empty($elementId) ? "JOIN (SELECT DISTINCT biep2.IBLOCK_PROPERTY_ID FROM b_iblock_element_property biep2 
			WHERE biep2.IBLOCK_ELEMENT_ID = $elementId) q ON bip.ID = q.IBLOCK_PROPERTY_ID" : "";

		$strSql   = sprintf('SELECT bip.ID, bip.NAME, bip.CODE, 
			(SELECT COUNT(_biep.ID) FROM `%s` _biep WHERE bip.ID = _biep.IBLOCK_PROPERTY_ID) VQTY
			FROM `%s` bip
				%s
			WHERE bip.IBLOCK_ID = %d 
				AND bip.PROPERTY_TYPE = "N" 
				AND bip.ACTIVE = "Y"
				AND bip.CODE NOT LIKE "CML2_%%"
			ORDER BY bip.NAME, VQTY DESC',
			self::TABLE_IB_EL_PROP,
			self::TABLE_IB_PROP,
			$elFilter,
			(int)$iblockId);

		return $this->DB->Query($strSql);



	}

	/**
	 * Function will return list of values with prop name for values page
	 * @param  int $propId
	 * @return array
	 */
	public function getValuesList($propId) {

		$strSql = sprintf('SELECT biep.ID, bip.NAME, bip.MULTIPLE, biep.VALUE, biep.IBLOCK_ELEMENT_ID ELEMENT_ID,  
			bie.NAME ELEMENT_NAME, biep.IBLOCK_PROPERTY_ID PROPERTY_ID
			FROM `%s` bip
				INNER JOIN `%s` biep ON biep.IBLOCK_PROPERTY_ID = bip.ID
				INNER JOIN `%s` bie  ON biep.IBLOCK_ELEMENT_ID  = bie.ID
			WHERE bip.ID = %d
			ORDER BY bip.ID',
			self::TABLE_IB_PROP,
			self::TABLE_IB_EL_PROP,
			self::TABLE_IB_ELEMENT,
			$propId);

		return $this->DB->Query($strSql);
	}

	/**
	 * Function will check if exist same links
	 * @param  int $iblockId
	 * @return bool
	 */
	public function getPropsWithSameLinks($iblockId) {

		$strSql = sprintf('SELECT biep.IBLOCK_PROPERTY_ID ID, biep.IBLOCK_ELEMENT_ID,  count(biep.IBLOCK_PROPERTY_ID) COUNT
			FROM  `%s` biep
				INNER JOIN `%s` bip ON bip.ID = biep.IBLOCK_PROPERTY_ID
			WHERE bip.IBLOCK_ID = %d 
				AND bip.PROPERTY_TYPE = "N"
				AND bip.MULTIPLE = "N"
				AND bip.ACTIVE = "Y"
				AND bip.CODE NOT LIKE "CML2_%%"
			GROUP BY 1,2
			HAVING count(biep.IBLOCK_PROPERTY_ID) > 1',
			self::TABLE_IB_EL_PROP,
			self::TABLE_IB_PROP,
			$iblockId);

		return $this->DB->Query($strSql);

	}


	/**
	 * Function will convert array of props to list type, insert values to enum
	 * @param  array $propsIds
	 */
	public function convertProp2List($propsIds) {

		$propsIdstr = join(',', $propsIds);

		// 1 change type
		$strSql = sprintf('UPDATE `%s` SET PROPERTY_TYPE = "L" WHERE ID IN (%s)',
			self::TABLE_IB_PROP,
			$propsIdstr);
		
		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);

		// 2 insert values to prop enum & update values in element prop table
		foreach ($propsIds as $propId) {

			$strSql = sprintf('SELECT biep.ID, biep.IBLOCK_PROPERTY_ID, biep.VALUE
				FROM `%s` biep
				WHERE biep.IBLOCK_PROPERTY_ID = %d',
				self::TABLE_IB_EL_PROP,
				(int)$propId);

			$dbRes = $this->DB->Query($strSql);

			while ($arRes = $dbRes->Fetch()) {

				set_time_limit(60);

				$dbSubRes = $this->createEnumValue($arRes);

				if ($arSubRes = $dbSubRes->Fetch()) {

					$strSql = sprintf('UPDATE `%s` SET VALUE = %d, VALUE_ENUM = %d WHERE IBLOCK_PROPERTY_ID = %d AND ID = %d',
					self::TABLE_IB_EL_PROP,
					$arSubRes['ID'],
					$arSubRes['ID'],
					(int)$propId,
					$arRes['ID']);
				
					$this->DB->Query($strSql);
					CPptLogger::getInstance()->log($strSql);
				}
			}
		}
	}

	/**
	 * Function will return array of reverse sql operations for convert to list type
	 * @param  array $propsIds
	 * @return array
	 */
	public function undoConvertProp2List($propsIds) {
		
		$reverseSql = array();

		foreach ($propsIds as $propId) {

			// 1 create backup for table prop
			$filterStr  = sprintf('ID = %d', (int)$propId);
			$stepSql    = $this->createReverseSql('update', self::TABLE_IB_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
			
			// 2 create backup for table element prop
			$strSql = sprintf('SELECT biep.ID
				FROM `%s` biep
				WHERE biep.IBLOCK_PROPERTY_ID = %d',
				self::TABLE_IB_EL_PROP,
				(int)$propId);


			$dbRes = $this->DB->Query($strSql);

			while($arRes = $dbRes->Fetch()) {

				$filterStr  = sprintf('ID = %d', (int)$arRes['ID']);
				$stepSql    = $this->createReverseSql('update', self::TABLE_IB_EL_PROP, $filterStr);
				$reverseSql = array_merge($reverseSql, $stepSql);
			}

			// 3 delete from props enum
			$filterStr  = sprintf('PROPERTY_ID = %d', (int)$propId);
			$stepSql    = $this->createReverseSql('delete', self::TABLE_IB_PROP_ENUM, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);

		}
		
		return $reverseSql;

	}

	/**
	 * Function will convert array of props to string type
	 * @param  array $propsIds
	 */
	public function convertProp2String($propsIds) {

		$propsIdstr = join(',', $propsIds);

		// 1 change type
		$strSql = sprintf('UPDATE `%s` SET PROPERTY_TYPE = "S" WHERE ID IN (%s)',
			self::TABLE_IB_PROP,
			$propsIdstr);
		
		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);
	}


	/**
	 * Function will return array of reverse sql operations for convert to string type
	 * @param  array $propsIds
	 * @return array
	 */
	public function undoConvertProp2String($propsIds) {
		
		$reverseSql = array();

		foreach ($propsIds as $propId) {

			// 1 create backup for table prop
			$filterStr  = sprintf('ID = %d', (int)$propId);
			$stepSql    = $this->createReverseSql('update', self::TABLE_IB_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
		}
		
		return $reverseSql;

	}

}
?>