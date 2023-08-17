<?php
/**
 * Class CActionManager
 * Module controller
 */
class CActionManager {

	private $dbOp;

	public function __construct($dbOp) {
		$this->dbOp = $dbOp;
	}

	/**
	 * Function will call operator convert to num methods
	 * @param  array $propsIds
	 */
	public function actionConvertProps2Number($propsIds) {

		if (!is_array($propsIds)) {
			$propsIds = array($propsIds);
		}

		$reverseSql = $this->dbOp->undoConvertProp2Number($propsIds);
		CActionSaver::getInstance()->saveSql($reverseSql);

		$this->dbOp->convertProp2Number($propsIds);
		echo json_encode($propsIds);
	}

	/**
	 * Function will call operator convert to list methods
	 * @param  array $propsIds
	 */
	public function actionConvertProps2List($propsIds) {

		if (!is_array($propsIds)) {
			$propsIds = array($propsIds);
		}

		$reverseSql = $this->dbOp->undoConvertProp2List($propsIds);
		CActionSaver::getInstance()->saveSql($reverseSql);

		$this->dbOp->convertProp2List($propsIds);
		echo json_encode($propsIds);
	}

	/**
	 * Function will call operator convert to string methods
	 * @param  array $propsIds
	 */
	public function actionConvertProps2String($propsIds) {

		if (!is_array($propsIds)) {
			$propsIds = array($propsIds);
		}

		$reverseSql = $this->dbOp->undoConvertProp2String($propsIds);
		CActionSaver::getInstance()->saveSql($reverseSql);

		$this->dbOp->convertProp2String($propsIds);
		echo json_encode($propsIds);
	}

	/**
	 * Function will call operator edit methods for specific object type
	 * @param  string $object
	 * @param  int    $propId
	 * @param  string $propVal
	 */
	public function actionEditProps($object, $propId, $propVal) {

		if ($object == 'value') {

			$reverseSql = $this->dbOp->undoEditPropValue($propId);
			CActionSaver::getInstance()->saveSql($reverseSql);
			$this->dbOp->editPropValue($propId, $propVal);

		} elseif ($object == 'name') {

			$reverseSql = $this->dbOp->undoEditPropName($propId);
			CActionSaver::getInstance()->saveSql($reverseSql);
			$this->dbOp->editPropName($propId, $propVal);

		}
	}


	/**
	 * Function will call operator delete props methods
	 * @param  array $props
	 */
	public function actionDeleteProps($props) {

		if (empty($props)) {
			return FALSE;
		}

		foreach($props as $p) {
			$p = intval($p);
			$propsIDs[] = $p;
		}

		$reverseSql = $this->dbOp->undoDeleteProps($props);
		CActionSaver::getInstance()->saveSql($reverseSql);

		$this->dbOp->deleteProps($propsIDs);
		echo json_encode($propsIDs);
	}

	/**
	 * Function will call operator delete unused values methods, get connected elems, delete zero values
	 * @param  array $props
	 */
	public function actionDeleteUnusedValues($propsIds) {

		if (empty($propsIds)) {
			return FALSE;
		}

		$reverseSql = array();

		foreach ($propsIds as $propertyId) {

			$emptyValues = array();
			$propertyId  = array($propertyId);
			$dbRes       = $this->dbOp->getEnumValues($propertyId);

			while($arRes = $dbRes->Fetch()) {

				$propertyId = $arRes['PROPERTY_ID'];
				$enumValues[ $propertyId ][] = $arRes['VALUE'];

				$dbProducts = $this->dbOp->getConnectedElems($propertyId, $arRes['ID']);
				$productsCount = intval($dbProducts->SelectedRowsCount());
				if ($productsCount <= 0) {
					$emptyValues[] = $arRes['ID'];
				}
			}

			if (!empty($emptyValues)) {

				$stepSql = $this->dbOp-> undoDeletePropValues($propertyId, $emptyValues);
				$reverseSql = array_merge($reverseSql, $stepSql);

				$this->dbOp->deletePropValues($propertyId, $emptyValues);

				foreach ($emptyValues as $emptyValue) {
					$deletedValues[] = $emptyValue;
				}
			}
			
		}

		CActionSaver::getInstance()->saveSql($reverseSql);
		echo json_encode($deletedValues);

	}

	/**
	 * Function will call operator delete values methods for specific prop
	 * @param  int   $propertyId
	 * @param  array $valuesIds
	 */
	public function actionDeletePropsValues($propertyId, $valuesIds) {

		if (empty($propertyId) || empty($valuesIds)) {
			return FALSE;
		}

		$reverseSql = $this->dbOp->undoDeletePropValues($propertyId, $valuesIds, true);
		$this->dbOp->deletePropValues($propertyId, $valuesIds, true);

		CActionSaver::getInstance()->saveSql($reverseSql);
		echo json_encode($valuesIds);

	}

	/**
	 * Function will call operator merge props methods
	 * @param  int   $leading
	 * @param  array $props
	 */
	public function actionMergeProps($props, $leading) {

		if (empty($props)) {
			return FALSE;
		}

		// try merge props
		$propList  = array();
		$valsCount = false;

		foreach($props as $p) {
			$p = intval($p);
			if ($p == $leading) {
				continue;
			}
			$propList[] = $p;
		}

		$reverseSql   = $this->dbOp->undoMergeProps($leading, $propList);
		$mergeResult  = $this->dbOp->mergeProps($leading, $propList);
		$outputRes    = array('props' => $propList);

		if (is_array($mergeResult)) {
			$reverseSql          = array_merge($mergeResult['reverse'], $reverseSql);
			$outputRes['count']  = $mergeResult['count'];
		}

		CActionSaver::getInstance()->saveSql($reverseSql);

		echo json_encode($outputRes);

	}

	/**
	 * Function will call operator merge values methods
	 * @param  int   $leading
	 * @param  array $values
	 */
	public function actionMergeValues($values, $leading) {

		if (empty($values)) {
			return FALSE;
		}

		$valuesList = array();

		foreach($values as $value) {
			$value = intval($value);
			if ($value == $leading) {
				continue;
			}

			$valuesList[] = $value;
		}

		$reverseSql    = $this->dbOp->undoMergeValues($leading, $valuesList);
		$addReverseSql = $this->dbOp->mergeValues($leading, $valuesList);

		CActionSaver::getInstance()->saveSql(array_merge($addReverseSql, $reverseSql));
		echo json_encode($valuesList);
	}

	/**
	 * Function will call operator merge values methods, for values sets
	 * @param  array $groups
	 */
	public function actionMergeAllValues($groups) {

		if (empty($groups)) {
			return FALSE;
		}

		$reverseSql = array();

		foreach ($groups as $group) {
			$stepSql    = $this->dbOp->undoMergeValues($group[0], $group);
			$this->dbOp->mergeValues($group[0], $group);
			$addStepSql = $this->dbOp->clearSameLinksByValue($group[0]);

			$stepSql    = array_merge($addStepSql, $stepSql);
			$reverseSql = array_merge($reverseSql, $stepSql);
		}

		CActionSaver::getInstance()->saveSql($reverseSql);
		echo json_encode($groups);
	}

	/**
	 * Function will call operator merge values methods, for values sets
	 * @param  array $groups
	 */
	public function actionMoveValues($values, $prop2Move, $propFromMove) {

		$dbRes = $this->dbOp->getPropById($prop2Move);
		if (!$dbRes->SelectedRowsCount()) {
			echo json_encode('not_found');
			return;
		}

		$prop2Move = $dbRes->Fetch();
		$notValid  = false;
		$dbRes     = $this->dbOp->getValuesByIds($values);

		while ($arRes = $dbRes->Fetch()) {

			if ($prop2Move['PROPERTY_TYPE'] == 'N') {
				$value = str_replace(',', '.', $arRes['VALUE']);
				if (!is_numeric($value)) {
					$notValid = true;
					break;
				}
			} else if ($prop2Move['PROPERTY_TYPE'] == 'L') {
				if (mb_strlen($arRes['VALUE']) > 255) {
					$notValid = true;
					break;
				}
			}
		}
		if ($notValid) {
			echo json_encode('not_valid_' . strtolower($prop2Move['PROPERTY_TYPE']) . '_values');
			return;
		}


		$reverseSql    = $this->dbOp->undoMoveValues($propFromMove, $prop2Move, $values);
		$addReverseSql = $this->dbOp->moveValues($propFromMove, $prop2Move, $values);
		if ($addReverseSql) {
			$reverseSql = array_merge($addReverseSql, $reverseSql);
		}
		
		CActionSaver::getInstance()->saveSql($reverseSql);
		echo json_encode($values);
	}

	/**
	 * Function will call operator clear methods, for props
	 * @param  array $propsIds
	 */
	public function actionClearAllSameLinks($propsIds) {

		$reverseSql = $this->dbOp->clearAllSameLinks($propsIds);
		CActionSaver::getInstance()->saveSql($reverseSql);
	}


	/**
	 * Function will call operator edit links methods
	 * @param  array $links2Insert
	 * @param  int   $valueId
	 * @param  int   $propId
	 */
	public function actionEditLinks($propId, $valueId, $links2Insert) {

		$links2Delete = array();
		$rows2Check   = array();
		$arExistIds   = array();
		$arNotExist   = array();
		$links2Insert = empty($links2Insert) ? array() : array_filter($links2Insert, 'is_numeric');

		// select all currrent rows and get roew to delete and links to insert
		$dbRes = $this->dbOp->getConnectedLinksByValue($valueId);

		while($arRes = $dbRes->Fetch()) {

			$rows2Check[$arRes['IBLOCK_ELEMENT_ID']] = $arRes;

			if (!in_array($arRes['IBLOCK_ELEMENT_ID'], $links2Insert)) {
				$links2Delete[$arRes['IBLOCK_ELEMENT_ID']] = $arRes['ID'];
			}
		}

		if (!empty($links2Insert)) {

			// clear input data by element existance
			$dbRes = $this->dbOp->getIblockElements($links2Insert);
			while ($arRes = $dbRes->Fetch()) {
				$arExistIds[] = $arRes['ID'];
			}

			$arNotExist = array_diff($links2Insert, $arExistIds); 

			foreach ($links2Insert as $key => $link) {
				if (in_array($link, array_keys($rows2Check))) {
					unset($links2Insert[$key]);
				}
				if (!in_array($link, $arExistIds)) {
					unset($links2Insert[$key]);
				}
			}
		}

		$reverseSql = $this->dbOp->undoEditLinks($propId, $valueId, $links2Delete, $links2Insert);
		CActionSaver::getInstance()->saveSql($reverseSql);

		$this->dbOp->editLinks($propId, $valueId, $links2Delete, $links2Insert);

		echo json_encode( array_values($arNotExist) );
	}

	public function actionCreateProp($iblockId, $params) {

		$dbRes = $this->dbOp->getPropByCode($params['CODE'], $iblockId);
		if ($dbRes->SelectedRowsCount() > 0) {
			echo json_encode('code_already_exist');
			return;
		}

		$reverseSql = $this->dbOp->createProp($iblockId, $params);
		CActionSaver::getInstance()->saveSql($reverseSql);

		echo json_encode('success');

	}

	/**
	 * Function will call base operator cancel method
	 */
	public function actionCancel() {

		$actionSaver = CActionSaver::getInstance();
		$reverseSql = $actionSaver->readSql();

		if (empty($reverseSql)) {
			return 'error';
		}
			
		$actionSaver->clearSql();
		$this->dbOp->cancelLastAction($reverseSql);
		return 'success';
		
	}

}