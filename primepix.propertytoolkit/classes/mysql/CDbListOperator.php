<?

/**
 * Class CDbListOperator
 * Class to work with props type list
 */
class CDbListOperator extends CDbBaseOperator
{

	protected static $type = 'L';

	/**
	 * Function will return list of props and number of product and values
	 * @param  int $iblockId
	 * @return array
	 */
	public function getPropsList($iblockId, $elementId) {

		$elFilter = !empty($elementId) ? "JOIN (SELECT DISTINCT biep2.IBLOCK_PROPERTY_ID FROM b_iblock_element_property biep2 
			WHERE biep2.IBLOCK_ELEMENT_ID = $elementId) q ON bip.ID = q.IBLOCK_PROPERTY_ID" : "";

		$strSql   = sprintf('SELECT bip.ID, bip.NAME, bip.CODE, bip.MULTIPLE,
			(SELECT COUNT(_biep.ID) FROM `%s` _biep WHERE bip.ID = _biep.IBLOCK_PROPERTY_ID) PQTY, 
			(SELECT COUNT(ID) FROM `%s` bipe WHERE bipe.PROPERTY_ID = bip.ID) VQTY
			FROM `%s` bip
				%s
			WHERE bip.IBLOCK_ID = %d 
				AND bip.PROPERTY_TYPE = "L" 
				AND bip.ACTIVE = "Y" 
				AND bip.CODE NOT LIKE "CML2_%%"
			ORDER BY bip.NAME, PQTY DESC',
			self::TABLE_IB_EL_PROP,
			self::TABLE_IB_PROP_ENUM,
			self::TABLE_IB_PROP,
			$elFilter,
			(int)$iblockId,
			(int)$iblockId);

		return $this->DB->Query($strSql);

	}

	/**
	 * Function will return list of values with prop name for values page
	 * @param  int $propId
	 * @return array
	 */
	public function getValuesList($propId) {

		$strSql = sprintf('SELECT bip.NAME, bip.MULTIPLE, bip.ID, bipe.ID VALUE_ID, bipe.VALUE VALUE
			FROM `%s` bip
				INNER JOIN `%s` bipe ON bip.ID = bipe.PROPERTY_ID
			WHERE bip.ID = %d
			ORDER BY bipe.VALUE',
			self::TABLE_IB_PROP,
			self::TABLE_IB_PROP_ENUM,
			$propId);

		return $this->DB->Query($strSql);
	}

	/**
	 * Function will return list of enum values
	 * @param  array $propsIds
	 * @return array
	 */
	public function getEnumValues($propsIds) {

		$propsIdStr = join(',', $propsIds);

		$strSql = sprintf('SELECT bipe.ID, bipe.PROPERTY_ID, bipe.VALUE
			FROM  `%s` bipe
			WHERE bipe.PROPERTY_ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
			$propsIdStr);

		return $this->DB->Query($strSql);
	}

	public function getValuesByIds($valuesIds) {

		$valuesIdStr =  join(',', $valuesIds);

		$strSql = sprintf('SELECT bipe.ID, bipe.PROPERTY_ID, bipe.VALUE
			FROM  `%s` bipe
			WHERE bipe.ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
			$valuesIdStr);

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
				AND bip.MULTIPLE = "N" 
				AND bip.PROPERTY_TYPE = "L"
				AND bip.ACTIVE = "Y"
				AND bip.CODE NOT LIKE "CML2_%%"
			GROUP BY 1,2
			HAVING count(biep.IBLOCK_PROPERTY_ID) > 1',
			self::TABLE_IB_EL_PROP,
			self::TABLE_IB_PROP,
			(int)$iblockId);

		return $this->DB->Query($strSql);

	}

	/**
	 * Function will list of iblock elements, connected with prop value
	 * @param  int	  $propId
	 * @param  string $propValue
	 * @return array
	 */
	public function getConnectedElems($propId, $propValue) {

		$strSql = sprintf('SELECT biep.IBLOCK_PROPERTY_ID, biep.IBLOCK_ELEMENT_ID, biep.VALUE, be.NAME ELEMENT_NAME
			FROM `%s` biep
			JOIN `%s` be ON be.ID = biep.IBLOCK_ELEMENT_ID
			WHERE biep.IBLOCK_PROPERTY_ID = %d AND biep.VALUE = %d',
			self::TABLE_IB_EL_PROP,
			self::TABLE_IB_ELEMENT,
			(int)$propId,
			(int)$propValue);

		return $this->DB->Query($strSql);
	}


	public function getConnectedLinksByValue($valueId) {

		$strSql = sprintf('SELECT *
			FROM `%s` biep
			WHERE biep.VALUE_ENUM = %d',
			self::TABLE_IB_EL_PROP,
			(int)$valueId);

		return $this->DB->Query($strSql);
	}

	/**
	 * Function will convert array of props to number type
	 * @param  array $propsIds
	 */
	public function convertProp2Number($propsIds) {

		$propsIdstr = join(',', $propsIds);

		// 1 change type
		$strSql = sprintf('UPDATE `%s` SET PROPERTY_TYPE = "N" WHERE ID IN (%s)',
			self::TABLE_IB_PROP,
			$propsIdstr);
		
		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);
		
		// 2 get enum values
		$strSql = sprintf('SELECT bipe.ID, bipe.VALUE
			FROM  `%s` bipe
			WHERE bipe.PROPERTY_ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
			$propsIdstr);

		$dbProp = $this->DB->Query($strSql);
		
		while($arProp = $dbProp->Fetch()) {

			set_time_limit(60);

			// to number format
			$arProp['VALUE'] = str_replace(',', '.', $arProp['VALUE']);

			// 3 update elements props values
			$strSql = sprintf('UPDATE `%s` SET
					VALUE = "%s",
					VALUE_ENUM = "",
					VALUE_NUM = "%s"
				WHERE VALUE_ENUM = %d',
				self::TABLE_IB_EL_PROP,
				$arProp['VALUE'],
				$arProp['VALUE'],
				(int)$arProp['ID']);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);

		}

		// 4 kick unused props
		$strSql = sprintf('DELETE FROM `%s` WHERE PROPERTY_ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
			$propsIdstr);

		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);

	}

	/**
	 * Function will return array of reverse sql operations for convert to number type
	 * @param  array $propsIds
	 * @return array
	 */
	public function undoConvertProp2Number($propsIds) {
		
		$reverseSql = array();

		foreach ($propsIds as $propId) {

			// 1 create backup for table prop
			$filterStr  = sprintf('ID = %d', (int)$propId);
			$stepSql    = $this->createReverseSql('update', self::TABLE_IB_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);

			// 2 create backup for table prop enum
			$filterStr  = sprintf('PROPERTY_ID = %d', (int)$propId);
			$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_PROP_ENUM, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
			
			// 3 create backup for table element prop
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
		
		// 2 get enum values
		$strSql = sprintf('SELECT bipe.ID, bipe.VALUE
			FROM  `%s` bipe
			WHERE bipe.PROPERTY_ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
			$propsIdstr);

		$dbProp = $this->DB->Query($strSql);
		
		while($arProp = $dbProp->Fetch()) {

			set_time_limit(60);

			// 3 update elements props values
			$strSql = sprintf('UPDATE `%s` SET
					VALUE = "%s",
					VALUE_ENUM = ""
				WHERE VALUE_ENUM = %d',
				self::TABLE_IB_EL_PROP,
				$arProp["VALUE"],
				(int)$arProp["ID"]);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);

		}

		// 4 kick unused props
		$strSql = sprintf('DELETE FROM `%s` WHERE PROPERTY_ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
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

			// 2 create backup for table prop enum
			$filterStr  = sprintf('PROPERTY_ID = %d', (int)$propId);
			$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_PROP_ENUM, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
			
			// 3 create backup for table element prop
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

		}
		
		return $reverseSql;

	}

	/**
	 * Function will delete props by ids
	 * @param  array $propsIds
	 */
	public function deleteProps($propsIds) {

		$propsIds = join(',', $propsIds);

		// 1 delete form props table
		$strSql = sprintf('DELETE FROM `%s` WHERE ID IN (%s)',
			self::TABLE_IB_PROP,
			$propsIds);

		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);

		// 2 delete form props enum table, if nessessary
		$strSql = sprintf('DELETE FROM `%s` WHERE PROPERTY_ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
			$propsIds);

		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);

		if ($this->hasGroupsModule) {
			// 3 delete px relations table
			$strSql = sprintf('DELETE FROM `%s` WHERE PROPERTY_ID IN (%s)',
				self::TABLE_PX_RELATIONS,
				$propsIds);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);
		}

		// 4 delete form props element table
		$strSql = sprintf('DELETE FROM `%s` WHERE IBLOCK_PROPERTY_ID IN (%s)',
			self::TABLE_IB_EL_PROP,
			$propsIds);

		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);

	}

	/**
	 * Function will return array of reverse sql operations for props delete
	 * @param  array $propsIds
	 * @return array
	 */
	public function undoDeleteProps($propsIds) {
		
		$propsIds   = join(',', $propsIds);
		$reverseSql = array();

		// 1 create backup for props table
		$filterStr  = sprintf('ID IN (%s)', $propsIds);
		$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_PROP, $filterStr);
		$reverseSql = array_merge($reverseSql, $stepSql);

		$filterStr  = sprintf('PROPERTY_ID IN (%s)', $propsIds);

		// 2 create backup for props enum table
		$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_PROP_ENUM, $filterStr);
		$reverseSql = array_merge($reverseSql, $stepSql);

		if ($this->hasGroupsModule) {
			// 3 create backup for px relations table
			$stepSql    = $this->createReverseSql('insert', self::TABLE_PX_RELATIONS, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
		}
	
		// 4 create backup for props element table
		$filterStr  = sprintf('IBLOCK_PROPERTY_ID IN (%s)', $propsIds);
		$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_EL_PROP, $filterStr);
		$reverseSql = array_merge($reverseSql, $stepSql);

		return $reverseSql;

	}

	/**
	 * Function will delete values by ids, and clear products values if nesessary
	 * @param  int   $propId
	 * @param  array $propValuesIds
	 * @param  bool  $clearProducts
	 */
	public function deletePropValues($propId, $propValuesIds, $clearProducts = false) {


		$propValuesIds = join(',', $propValuesIds);

		// 1 delete from props enum table
		$strSql = sprintf('DELETE FROM `%s` WHERE PROPERTY_ID = %d AND ID IN (%s)',
			self::TABLE_IB_PROP_ENUM,
			$propId,
			$propValuesIds);

		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);

		if ($clearProducts) {

			$strSql = sprintf('DELETE FROM `%s` WHERE IBLOCK_PROPERTY_ID = %d AND VALUE_ENUM IN (%s)',
				self::TABLE_IB_EL_PROP,
				$propId,
				$propValuesIds);

			$this->DB->Query($strSql);
		}

	}

	/**
	 * Function will return array of reverse sql operations for props values delete
	 * @param  int   $propId
	 * @param  array $propValuesIds
	 * @param  bool  $clearProducts
	 * @return array
	 */
	public function undoDeletePropValues($propId, $propValuesIds, $clearProducts) {

		$propValuesIds = join(',', $propValuesIds);

		// 1 create backup for props enum table
		$filterStr  = sprintf('PROPERTY_ID = %d AND ID IN (%s)', $propId, $propValuesIds);
		$reverseSql = $this->createReverseSql('insert', self::TABLE_IB_PROP_ENUM, $filterStr);

		if ($clearProducts) {

			$filterStr  = sprintf('IBLOCK_PROPERTY_ID = %d AND VALUE_ENUM IN (%s)', $propId, $propValuesIds);
			$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_EL_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
		}

		return $reverseSql;
	}

	/**
	 * Function will change prop value by id
	 * @param  int    $propId
	 * @param  string $propVal
	 */
	public function editPropValue($propId, $propVal) {

		// 1 update prop value
		$strSql = sprintf('UPDATE `%s` SET VALUE = "%s" WHERE ID = %d',
			self::TABLE_IB_PROP_ENUM,
			$propVal,
			(int)$propId);

		
		$this->DB->Query($strSql);
		CPptLogger::getInstance()->log($strSql);
	}

	/**
	 * Function will return array of reverse sql operations for prop value edit
	 * @param  int    $propId
	 * @param  string $propVal
	 * @return array
	 */
	public function undoEditPropValue($propId) {

		// 1 create backup for prop enum table
		$filterStr = sprintf('ID = %d', (int)$propId);
		return $this->createReverseSql('update', self::TABLE_IB_PROP_ENUM, $filterStr);
	}

	/**
	 * Function will merge props and it values to leading
	 * @param  int    $leadingPropId
	 * @param  array  $propMergeList
	 */
	public function mergeProps($leadingPropId, $propMergeList) {

		$isMultiple = $this->checkPropMultiple($leadingPropId);

		if (!$isMultiple) {
			// 1 select leading products links for checking
			$arLeadingIds  = array();

			$dbRes = $this->getConnectedLinks($leadingPropId);
			while($arRes = $dbRes->Fetch()) {
				$arLeadingIds[$arRes['IBLOCK_ELEMENT_ID']] = $arRes['ID'];
			}
		}


		foreach($propMergeList as $propId) {

			if ( (int)$propId === (int)$leadingPropId ) {
				continue;
			}

			// 1 update props id
			$strSql = sprintf('UPDATE `%s` SET PROPERTY_ID = %d WHERE PROPERTY_ID = %d',
				self::TABLE_IB_PROP_ENUM,
				(int)$leadingPropId,
				(int)$propId);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);

			// 2 update elements props id
			$strSql = sprintf('UPDATE `%s` SET IBLOCK_PROPERTY_ID = %d WHERE IBLOCK_PROPERTY_ID = %d',
				self::TABLE_IB_EL_PROP,
				(int)$leadingPropId,
				(int)$propId);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);

			// 3 delete old props
			$this->deleteProps( array($propId) );
		}

		if (!$isMultiple) {
			// 4 check and delete duplicates
			return $this->clearSameLinksByProp($leadingPropId, $arLeadingIds);
		}


	}

	/**
	 * Function will return array of reverse sql operations for prop merge
	 * @param  int	  $leadingPropId
	 * @param  array  $propMergeList
	 * @return array
	 */
	public function undoMergeProps($leadingPropId, $propMergeList) {

		$reverseSql = array();


		foreach($propMergeList as $propId) {

			if ( (int)$propId === (int)$leadingPropId ) {
				continue;
			}

			// 1 create backup for prop table
			$filterStr  = sprintf('ID = %d', (int)$propId);
			$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);

			if ($this->hasGroupsModule) {
				// 2 create backup for px relations table
				$filterStr  = sprintf('PROPERTY_ID = %d', (int)$propId);
				$stepSql    = $this->createReverseSql('insert', self::TABLE_PX_RELATIONS, $filterStr);
				$reverseSql = array_merge($reverseSql, $stepSql);
			}

			// 3 create backup for prop enum table
			$dbRes = $this->getEnumValues( array($propId) );

			while($arRes = $dbRes->Fetch()) {
				$filterStr  = sprintf('ID = %d', (int)$arRes['ID']);
				$stepSql    = $this->createReverseSql('update', self::TABLE_IB_PROP_ENUM, $filterStr);
				$reverseSql = array_merge($reverseSql, $stepSql);
			}

			// 4 create backup for prop element table
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

		}

		return $reverseSql;
	}


	/**
	 * Function will merge one prop values
	 * @param  int    $leadingValue
	 * @param  array  $valuesList
	 */
	public function mergeValues($leadingValue, $valuesList) {


		foreach($valuesList as $propValue) {

			if ( (int)$propValue === (int)$leadingValue ) {
				continue;
			}

			// 1 replace props
			$strSql = sprintf('UPDATE `%s` SET VALUE = "%s", VALUE_ENUM = "%s" WHERE VALUE_ENUM = %d',
				self::TABLE_IB_EL_PROP,
				(int)$leadingValue,
				(int)$leadingValue,
				(int)$propValue);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);

			// 2 delete unused props
			$strSql = sprintf('DELETE FROM `%s` WHERE ID = %d',
				self::TABLE_IB_PROP_ENUM,
				(int)$propValue);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);
		}

		// 3 check same values and return reverse sql
	 	return $this->clearSameLinksByValue($leadingValue);
	}

	/**
	 * Function will return array of reverse sql operations for one prop values merge
	 * @param  int	  $leadingValue
	 * @param  array  $valuesList
	 * @return array
	 */
	public function undoMergeValues($leadingValue, $valuesList) {

		$reverseSql = array();

		foreach($valuesList as $propValue) {

			if ( (int)$propValue === (int)$leadingValue ) {
				continue;
			}

			// 1 create backup for prop enum table
			$filterStr  = sprintf('ID = %d', (int)$propValue);
			$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_PROP_ENUM, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);

			// 2 create backup for prop element table
			$strSql = sprintf('SELECT biep.ID
				FROM `%s` biep
				WHERE biep.VALUE_ENUM = %d',
				self::TABLE_IB_EL_PROP,
				(int)$propValue);

			$dbRes = $this->DB->Query($strSql);

			while($arRes = $dbRes->Fetch()) {
				$filterStr  = sprintf('ID = %d', (int)$arRes['ID']);
				$stepSql    = $this->createReverseSql('update', self::TABLE_IB_EL_PROP, $filterStr);
				$reverseSql = array_merge($reverseSql, $stepSql);
			}

		}
		return $reverseSql;
	}


	/**
	 * Function will clear same enum row in table after merging, and return additional reverse sql
	 * @param  int    $leadingValue
	 * @return array
	 */
	public function clearSameLinksByValue($leadingValue) {

		$reverseSql = array();
		$elementIDs = array();
		$sameValues = array();

		// 1 select all leading values rows
		$strSql = sprintf('SELECT * FROM `%s` WHERE VALUE_ENUM = %d',
			self::TABLE_IB_EL_PROP,
			(int)$leadingValue);

		$dbRes = $this->DB->Query($strSql);

		if ($dbRes->SelectedRowsCount() <= 0) {
			return $reverseSql;
		}

		// 2 group by product id and filter first
		while ($arRes = $dbRes->Fetch()) {

			$elementID = $arRes['IBLOCK_ELEMENT_ID'];
			if (!in_array($elementID, $elementIDs)) {
				$elementIDs[] = $elementID;
				continue;
			}

			$sameValues[] = $arRes;
		}

		// 3 delete the same and create reverse sql
		foreach ($sameValues as $sameValue) {

			$filterStr  = sprintf('ID = %d', (int)$sameValue['ID']);
			$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_EL_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);

			$strSql = sprintf('DELETE FROM `%s` WHERE ID = %d',
				self::TABLE_IB_EL_PROP,
				(int)$sameValue['ID']);
			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);

		}

		return $reverseSql;

	}

	/**
	 * Function will move values from one prop to another
	 * @param  int   $propFromMove
	 * @param  int   $prop2move
	 * @param  array $valuesList
	 */
	public function moveValues($propFromMove, $prop2move, $valuesList) {

		// list prop type
		if ($prop2move['PROPERTY_TYPE'] == 'L') {

			// 1 replace props
			$strSql = sprintf('UPDATE `%s` SET IBLOCK_PROPERTY_ID = %d WHERE IBLOCK_PROPERTY_ID = %d',
				self::TABLE_IB_EL_PROP,
				(int)$prop2move['ID'],
				(int)$propFromMove);

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);

			foreach ($valuesList as $propValue) {

				// 2 update props id
				$strSql = sprintf('UPDATE `%s` SET PROPERTY_ID = %d WHERE ID = %d',
					self::TABLE_IB_PROP_ENUM,
					(int)$prop2move['ID'],
					(int)$propValue);

				$this->DB->Query($strSql);
				CPptLogger::getInstance()->log($strSql);
			}

		// number or string prop type
		} else {

			$valuesStr = join(',', $valuesList);
			$strSql    = sprintf('SELECT * FROM  `%s` WHERE ID IN (%s)', self::TABLE_IB_PROP_ENUM, $valuesStr);
			$dbRes     = $this->DB->Query($strSql);

			while ($arRes = $dbRes->Fetch()) {

				$propValue = $prop2move['PROPERTY_TYPE'] == 'N' ? str_replace(',', '.', $arRes['VALUE']) : $arRes['VALUE'];

				// 1 replace props
				$strSql = sprintf('UPDATE `%s` SET IBLOCK_PROPERTY_ID = %d, VALUE = "%s", VALUE_NUM = "%s", VALUE_ENUM = 0 
					WHERE IBLOCK_PROPERTY_ID = %d AND VALUE_ENUM = %d',
					self::TABLE_IB_EL_PROP,
					(int)$prop2move['ID'],
					$propValue,
					$propValue,
					(int)$propFromMove,
					(int)$arRes['ID']);

				$this->DB->Query($strSql);
				CPptLogger::getInstance()->log($strSql);


				$strSql = sprintf('DELETE FROM `%s` WHERE ID = %d',
					self::TABLE_IB_PROP_ENUM,
					(int)$arRes['ID']);

				$this->DB->Query($strSql);
				CPptLogger::getInstance()->log($strSql);

			}
		}
	}

	/**
	 * Function will return array of reverse sql operations for props values move
	 * @param  int   $propFromMove
	 * @param  int   $prop2move
	 * @param  array $valuesList
	 * @return array
	 */
	public function undoMoveValues($propFromMove, $prop2move, $valuesList) {

		$reverseSql = array();

		// list prop type
		if ($prop2move['PROPERTY_TYPE'] == 'L') {

			foreach($valuesList as $propValue) {

				// 1 create backup for prop enum table
				$filterStr  = sprintf('ID = %d', (int)$propValue);
				$stepSql    = $this->createReverseSql('update', self::TABLE_IB_PROP_ENUM, $filterStr);
				$reverseSql = array_merge($reverseSql, $stepSql);
			}

		// number or string prop type
		} else {

			foreach($valuesList as $propValue) {

				// 1 create backup for prop enum table
				$filterStr  = sprintf('ID = %d', (int)$propValue);
				$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_PROP_ENUM, $filterStr);
				$reverseSql = array_merge($reverseSql, $stepSql);
			}

		}

		// 2 create backup for prop element table
		$strSql = sprintf('SELECT biep.ID
			FROM `%s` biep
			WHERE biep.IBLOCK_PROPERTY_ID = %d',
			self::TABLE_IB_EL_PROP,
			(int)$propFromMove);

		$dbRes = $this->DB->Query($strSql);

		while($arRes = $dbRes->Fetch()) {
			$filterStr  = sprintf('ID = %d', (int)$arRes['ID']);
			$stepSql    = $this->createReverseSql('update', self::TABLE_IB_EL_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
		}

		return $reverseSql;

	}


	/**
	 * Function will delete and insert links
	 * @param  int   $propId
	 * @param  int   $valueId
	 * @param  array $links2Delete
	 * @param  array $links2insert
	 */
	public function editLinks($propId, $valueId, $links2Delete, $links2insert) {

		// 1 delete links
		if (!empty($links2Delete)) {

			$strSql = sprintf('DELETE FROM `%s` WHERE ID IN (%s)',
				self::TABLE_IB_EL_PROP,
				join(',', $links2Delete));

			$this->DB->Query($strSql);
			CPptLogger::getInstance()->log($strSql);
		}

		// 2 insert links
		if (!empty($links2insert)) {

			foreach ($links2insert as $link) {

				$row = array(
					'IBLOCK_ELEMENT_ID'  => $link,
					'IBLOCK_PROPERTY_ID' => $propId,
					'VALUE_ENUM'         => $valueId,
					'VALUE'              => $valueId,
					'VALUE_TYPE'         => 'text'
				);

				list($fields, $values) = $this->DB->PrepareInsert(self::TABLE_IB_EL_PROP, $row);
				$strSql = sprintf('INSERT INTO `%s` (%s) VALUES (%s);', self::TABLE_IB_EL_PROP, $fields, $values);

				$this->DB->Query($strSql);
				CPptLogger::getInstance()->log($strSql);

			}
			

		}

	}

	/**
	 * Function will return array of reverse sql operations for values links edit
	 * @param  int   $propId
	 * @param  int   $valueId
	 * @param  array $links2Delete
	 * @param  array $links2insert
	 */
	public function undoEditLinks($propId, $valueId, $links2Delete, $links2insert) {

		$reverseSql = array();

		// 1 reverse fo delete links
		if (!empty($links2Delete)) {

			$filterStr  = sprintf('ID IN (%s)', join(',', $links2Delete));
			$stepSql    = $this->createReverseSql('insert', self::TABLE_IB_EL_PROP, $filterStr);
			$reverseSql = array_merge($reverseSql, $stepSql);
		}

		// 2 reverse for insert links
		if (!empty($links2insert)) {

			foreach ($links2insert as $link) {

				$filterStr  = sprintf('IBLOCK_PROPERTY_ID = %d AND VALUE_ENUM = %d AND IBLOCK_ELEMENT_ID = %d', 
					$propId, $valueId, $link);
				$stepSql    = $this->createReverseSql('delete', self::TABLE_IB_EL_PROP, $filterStr);
				$reverseSql = array_merge($reverseSql, $stepSql);
			}
			

		}

		return $reverseSql;
	}
}
?>