<?

namespace SB\Site\Property;

use CModule;

/**
 * Class CDbBaseOperator
 * Parent operator
 */
abstract class CDbBaseOperator
{

    protected $DB = null;
    protected $logTemplate = '%s FILE: %s, LINE: %s';
    protected $hasGroupsModule = false;

    public function __construct()
    {
        global $DB;
        $this->DB = $DB;
        $this->hasGroupsModule = CModule::IncludeModule('primepix.propertygroups');
    }

    const TABLE_IB_PROP = 'b_iblock_property';
    const TABLE_IB_EL_PROP = 'b_iblock_element_property';
    const TABLE_IB_PROP_ENUM = 'b_iblock_property_enum';
    const TABLE_IB_ELEMENT = 'b_iblock_element';
    const TABLE_PX_RELATIONS = 'px_property_relations';


    /**
     * Function will return array of reverse sql operations for table, type: insert, update, delete
     *
     * @param string $type
     * @param string $tableName
     * @param string $filterStr
     *
     * @return array
     */
    public function createReverseSql($type, $tableName, $filterStr)
    {

        if ($type == 'delete') {
            return array(sprintf('DELETE FROM `%s` WHERE %s;', $tableName, $filterStr));
        }

        $reverseSql = array();

        $strSql = sprintf('SELECT * FROM `%s` WHERE %s',
            $tableName,
            $filterStr);

        $rows = $this->DB->Query($strSql);

        while ($row = $rows->Fetch()) {

            if ($type === 'insert') {
                list($fields, $values) = $this->DB->PrepareInsert($tableName, $row);
                $reverseSql[] = sprintf('INSERT INTO `%s` (%s) VALUES (%s);', $tableName, $fields, $values);
            } else {
                unset($row['ID']);
                $strUpdate = $this->DB->PrepareUpdate($tableName, $row);
                $reverseSql[] = sprintf('UPDATE `%s` SET %s WHERE %s;', $tableName, $strUpdate, $filterStr);
            }

        }

        return $reverseSql;
    }

    /**
     * Function will do reverse sql operations
     *
     * @param array $reverseSql
     */
    public function cancelLastAction($reverseSql)
    {

        foreach ($reverseSql as $sqlStep) {

            if (empty($sqlStep)) {
                continue;
            }

            ////CPptLogger::getInstance()->log($sqlStep);
            $this->DB->Query($sqlStep);
        }

    }

    /**
     * Function will return list of elements ids for prop
     *
     * @param int $propId
     *
     * @return array
     */
    public function getConnectedLinks($propId, $limit = false)
    {

        $limitStr = $limit ? "LIMIT $limit" : "";
        $strSql = sprintf('SELECT * FROM `%s` WHERE IBLOCK_PROPERTY_ID = %d %s',
            self::TABLE_IB_EL_PROP,
            (int)$propId,
            $limitStr);

        return $this->DB->Query($strSql);
    }


    /**
     * Function will return list of values rows by ids list
     *
     * @param array $valuesIds
     *
     * @return array
     */
    public function getValuesByIds($valuesIds)
    {

        $valuesIdStr = join(',', $valuesIds);

        $strSql = sprintf('SELECT * FROM `%s` WHERE ID IN (%s)',
            self::TABLE_IB_EL_PROP,
            $valuesIdStr);

        return $this->DB->Query($strSql);
    }

    /**
     * Function will return one prop by it id
     *
     * @param int $propId
     *
     * @return array
     */
    public function getPropById($propId)
    {

        $strSql = sprintf('SELECT *
			FROM `%s` bip
			WHERE bip.ID = %d
				AND bip.ACTIVE = "Y"
				AND bip.CODE NOT LIKE "CML2_%%"',
            self::TABLE_IB_PROP,
            (int)$propId);

        return $this->DB->Query($strSql);

    }

    /**
     * Function will return one prop by it code
     *
     * @param int $propCode
     *
     * @return array
     */
    public function getPropByCode($propCode, $iblockId)
    {

        $strSql = sprintf('SELECT *
			FROM `%s` bip
			WHERE bip.CODE = "%s" AND bip.IBLOCK_ID = %d',
            self::TABLE_IB_PROP,
            $propCode,
            (int)$iblockId);

        return $this->DB->Query($strSql);

    }


    /**
     * Function will create enum row and insert into table
     *
     * @param array $arRes
     *
     * @return array
     */
    public function createEnumValue($arRes)
    {

        $arFields = array(
            'VALUE' => $arRes['VALUE'],
            'PROPERTY_ID' => $arRes['IBLOCK_PROPERTY_ID'],
            'DEF' => 'N',
            'SORT' => 500,
            'XML_ID' => md5(uniqid(''))
        );

        list($fields, $values) = $this->DB->PrepareInsert(self::TABLE_IB_PROP_ENUM, $arFields);
        $strSql = sprintf('INSERT INTO `%s` (%s) VALUES (%s);',
            self::TABLE_IB_PROP_ENUM,
            $fields,
            $values);

        $this->DB->Query($strSql);
        ////CPptLogger::getInstance()->log($strSql);

        $strSql = sprintf('SELECT bipe.ID FROM `%s` bipe WHERE bipe.XML_ID = "%s"',
            self::TABLE_IB_PROP_ENUM,
            $arFields['XML_ID']);

        return $this->DB->Query($strSql);

    }


    /**
     * Function will clear same enum links
     *
     * @param int $leadingPropId
     * @param array $arLeadingIds
     *
     * @return array
     */
    protected function clearSameLinksByProp($leadingPropId, $arLeadingIds)
    {

        $reverseSql = array();
        $dbRes = $this->getConnectedLinks($leadingPropId);

        while ($arRes = $dbRes->Fetch()) {

            $arRows[] = $arRes;
            $elementId = (int)$arRes['IBLOCK_ELEMENT_ID'];

            if (!isset($arLeadingIds[$elementId])) {
                $arLeadingIds[$elementId] = $arRes['ID'];
            }
        }

        foreach ($arRows as $row) {

            if (in_array($row['ID'], $arLeadingIds)) {
                continue;
            }

            $filterStr = sprintf('ID = %d', (int)$row["ID"]);
            $stepSql = $this->createReverseSql("insert", self::TABLE_IB_EL_PROP, $filterStr);
            $reverseSql = array_merge($reverseSql, $stepSql);

            $strSql = sprintf('DELETE FROM `%s` WHERE ID = %d',
                self::TABLE_IB_EL_PROP,
                (int)$row['ID']);
            $dbRes = $this->DB->Query($strSql);
            ////CPptLogger::getInstance()->log($strSql);
        }

        return array('reverse' => $reverseSql, 'count' => count($arLeadingIds));

    }

    /**
     * Function will check duplicates and delete, return reverse insert
     *
     * @param int $leadingPropId
     * @param array $arLeadingIds
     *
     * @return array
     */
    public function clearAllSameLinks($propsIds)
    {

        $reverseSql = array();

        foreach ($propsIds as $propId) {

            set_time_limit(60);

            $elementIds = array();
            $arRows = array();
            $dbRes = $this->getConnectedLinks($propId);

            while ($arRes = $dbRes->Fetch()) {

                $arRows[] = $arRes;
                $elementId = (int)$arRes['IBLOCK_ELEMENT_ID'];

                if (!isset($elementIds[$elementId])) {
                    $elementIds[$elementId] = $arRes['ID'];
                }
            }

            foreach ($arRows as $row) {

                if (in_array($row['ID'], $elementIds)) {
                    continue;
                }

                $filterStr = sprintf('ID = %d', (int)$row['ID']);
                $stepSql = $this->createReverseSql('insert', self::TABLE_IB_EL_PROP, $filterStr);
                $reverseSql = array_merge($reverseSql, $stepSql);

                $strSql = sprintf('DELETE FROM `%s` WHERE ID = %d',
                    self::TABLE_IB_EL_PROP,
                    (int)$row['ID']);
                $dbRes = $this->DB->Query($strSql);
                ////CPptLogger::getInstance()->log($strSql);
            }
        }

        return $reverseSql;
    }

    /**
     * Function will check if prop is multiple
     *
     * @param int $propId
     *
     * @return boool
     */
    public function checkPropMultiple($propId)
    {

        $strSql = sprintf('SELECT *
			FROM `%s` bip
			WHERE bip.ID = %d',
            self::TABLE_IB_PROP,
            (int)$propId);

        $dbRes = $this->DB->Query($strSql);

        if ($arRes = $dbRes->Fetch()) {
            return $arRes['MULTIPLE'] == 'Y';
        }

        return false;

    }

    public function getIblockElements($elementIds)
    {

        $strSql = sprintf('SELECT *
			FROM `%s` bip
			WHERE bip.ID IN (%s)',
            self::TABLE_IB_ELEMENT,
            join(',', $elementIds));

        return $this->DB->Query($strSql);

    }

    public function createProp($iblockId, $params)
    {

        $iblockId = (int)$iblockId;
        $arFields = array(
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockId,
            'NAME' => $params['NAME'],
            'CODE' => $params['CODE'],
            'PROPERTY_TYPE' => $params['TYPE'],
            'MULTIPLE' => $params['MULTIPLE'] == 'Y' ? 'Y' : 'N',
            'IS_REQUIRED' => $params['IS_REQUIRED'] == 'Y' ? 'Y' : 'N',
        );

        list($fields, $values) = $this->DB->PrepareInsert(self::TABLE_IB_PROP, $arFields);
        $strSql = sprintf('INSERT INTO `%s` (%s) VALUES (%s);',
            self::TABLE_IB_PROP,
            $fields,
            $values);
        $this->DB->Query($strSql);
        //CPptLogger::getInstance()->log($strSql);

        $filterStr = sprintf('IBLOCK_ID = %d AND PROPERTY_TYPE = "%s" AND CODE = "%s"',
            $iblockId, $params['TYPE'], $params['CODE']);

        return $this->createReverseSql('delete', self::TABLE_IB_PROP, $filterStr);


    }

    /**
     * Function will delete values by row ids (iblock_element_property)
     *
     * @param int $propId
     * @param array $propValuesIds
     */
    public function deletePropValues($propId, $propValuesIds)
    {


        $propValuesIds = join(',', $propValuesIds);

        // 1 delete from props enum table
        $strSql = sprintf('DELETE FROM `%s` WHERE IBLOCK_PROPERTY_ID = %d AND ID IN (%s)',
            self::TABLE_IB_EL_PROP,
            (int)$propId,
            $propValuesIds);

        $this->DB->Query($strSql);
        //CPptLogger::getInstance()->log($strSql);

    }

    /**
     * Function will delete props by ids
     *
     * @param array $propsIds
     *
     * @return array
     */
    public function deleteProps($propsIds)
    {

        $propsIds = join(',', $propsIds);

        // 1 delete form props table
        $strSql = sprintf('DELETE FROM `%s` WHERE ID IN (%s)',
            self::TABLE_IB_PROP,
            $propsIds);

        $this->DB->Query($strSql);
//        //CPptLogger::getInstance()->log($strSql);

        if ($this->hasGroupsModule) {
            // 2 delete px relations table
            $strSql = sprintf('DELETE FROM `%s` WHERE PROPERTY_ID IN (%s)',
                self::TABLE_PX_RELATIONS,
                $propsIds);
        }

        $this->DB->Query($strSql);
//        //CPptLogger::getInstance()->log($strSql);

        // 3 delete form props element table
        $strSql = sprintf('DELETE FROM `%s` WHERE IBLOCK_PROPERTY_ID IN (%s)',
            self::TABLE_IB_EL_PROP,
            $propsIds);

        $this->DB->Query($strSql);
//        //CPptLogger::getInstance()->log($strSql);

    }

    /**
     * Function will return array of reverse sql operations for delete props
     *
     * @param array $propsIds
     *
     * @return array
     */
    public function undoDeleteProps($propsIds)
    {

        $propsIds = join(',', $propsIds);
        $reverseSql = array();

        // 1 create backup for props table
        $filterStr = sprintf('ID IN (%s)', $propsIds);
        $stepSql = $this->createReverseSql('insert', self::TABLE_IB_PROP, $filterStr);
        $reverseSql = array_merge($reverseSql, $stepSql);

        if ($this->hasGroupsModule) {
            // 2 create backup fort px relations table
            $filterStr = sprintf('PROPERTY_ID IN (%s)', $propsIds);
            $stepSql = $this->createReverseSql('insert', self::TABLE_PX_RELATIONS, $filterStr);
            $reverseSql = array_merge($reverseSql, $stepSql);
        }

        // 3 create backup for props element table
        $filterStr = sprintf('IBLOCK_PROPERTY_ID IN (%s)', $propsIds);
        $stepSql = $this->createReverseSql('insert', self::TABLE_IB_EL_PROP, $filterStr);
        $reverseSql = array_merge($reverseSql, $stepSql);

        return $reverseSql;

    }

    /**
     * Function will return array of reverse sql operations for props values delete
     *
     * @param int $propId
     * @param array $propValuesIds
     *
     * @return array
     */
    public function undoDeletePropValues($propId, $propValuesIds)
    {

        $propValuesIds = join(',', $propValuesIds);

        // 1 create backup for props enum table
        $filterStr = sprintf('IBLOCK_PROPERTY_ID = %d AND ID IN (%s)', (int)$propId, $propValuesIds);
        $reverseSql = $this->createReverseSql('insert', self::TABLE_IB_EL_PROP, $filterStr);

        return $reverseSql;
    }

    /**
     * Function will merge props and it values to leading
     *
     * @param int $leadingPropId
     * @param array $propMergeList
     */
    public function mergeProps($leadingPropId, $propMergeList)
    {

        $isMultiple = $this->checkPropMultiple($leadingPropId);

        if (!$isMultiple) {
            // 1 select leading products links for checking
            $arLeadingIds = array();

            $dbRes = $this->getConnectedLinks($leadingPropId);
            while ($arRes = $dbRes->Fetch()) {
                $arLeadingIds[$arRes['IBLOCK_ELEMENT_ID']] = $arRes['ID'];
            }
        }


        foreach ($propMergeList as $propId) {

            if ((int)$propId === (int)$leadingPropId) {
                continue;
            }

            // 2 update elements props id, check dublicate values
            $strSql = sprintf('UPDATE `%s` SET IBLOCK_PROPERTY_ID = %d WHERE IBLOCK_PROPERTY_ID = %d',
                self::TABLE_IB_EL_PROP,
                (int)$leadingPropId,
                (int)$propId);

            $this->DB->Query($strSql);
//            //CPptLogger::getInstance()->log($strSql);

            // 3 delete old props
            $this->deleteProps(array($propId));
        }

        if (!$isMultiple) {
            // 4 check and delete duplicates
            return $this->clearSameLinksByProp($leadingPropId, $arLeadingIds);
        }
    }


    /**
     * Function will return array of reverse sql operations for prop merge
     *
     * @param int $leadingPropId
     * @param array $propMergeList
     *
     * @return array
     */
    public function undoMergeProps($leadingPropId, $propMergeList)
    {

        $reverseSql = array();

        foreach ($propMergeList as $propId) {

            if ((int)$propId === (int)$leadingPropId) {
                continue;
            }

            // 1 create backup for prop table
            $filterStr = sprintf('ID = %d', (int)$propId);
            $stepSql = $this->createReverseSql('insert', self::TABLE_IB_PROP, $filterStr);
            $reverseSql = array_merge($reverseSql, $stepSql);

            if ($this->hasGroupsModule) {
                // 2 create backup for px relations table
                $filterStr = sprintf('PROPERTY_ID = %d', (int)$propId);
                $stepSql = $this->createReverseSql('insert', self::TABLE_PX_RELATIONS, $filterStr);
                $reverseSql = array_merge($reverseSql, $stepSql);
            }

            // 3 create backup for prop element table
            $strSql = sprintf('SELECT biep.ID
				FROM `%s` biep
				WHERE biep.IBLOCK_PROPERTY_ID = %d',
                self::TABLE_IB_EL_PROP,
                (int)$propId);

            $dbRes = $this->DB->Query($strSql);

            while ($arRes = $dbRes->Fetch()) {

                $filterStr = sprintf('ID = %d', (int)$arRes['ID']);
                $stepSql = $this->createReverseSql('update', self::TABLE_IB_EL_PROP, $filterStr);
                $reverseSql = array_merge($reverseSql, $stepSql);

            }

        }

        return $reverseSql;
    }


    /**
     * Function will change prop name by id
     *
     * @param int $propId
     * @param string $propName
     */
    public function editPropName($propId, $propName)
    {

        // 1 update prop name
        $strSql = sprintf('UPDATE `%s` SET NAME = "%s" WHERE ID = %d',
            self::TABLE_IB_PROP,
            $propName,
            (int)$propId);

        $this->DB->Query($strSql);
        ////CPptLogger::getInstance()->log($strSql);

    }


    /**
     * Function will return array of reverse sql operations for prop name edit
     *
     * @param int $propId
     *
     * @return array
     */
    public function undoEditPropName($propId)
    {

        // 1 create backup for prop
        $filterStr = sprintf('ID = %d', (int)$propId);
        return $this->createReverseSql('update', self::TABLE_IB_PROP, $filterStr);

    }


    /**
     * Function will change prop value by row id in element_property table
     *
     * @param int $propId
     * @param string $propName
     */
    public function editPropValue($propId, $propName)
    {

        // 1 update prop name
        $strSql = sprintf('UPDATE `%s` SET VALUE = "%s" WHERE ID = %d',
            self::TABLE_IB_EL_PROP,
            $propName,
            (int)$propId);

        $this->DB->Query($strSql);
        ////CPptLogger::getInstance()->log($strSql);

    }

    /**
     * Function will return array of reverse sql operations for prop value edit
     *
     * @param int $propId
     *
     * @return array
     */
    public function undoEditPropValue($propId)
    {

        // 1 create backup for prop
        $filterStr = sprintf('ID = %d', (int)$propId);
        return $this->createReverseSql('update', self::TABLE_IB_EL_PROP, $filterStr);

    }

    /**
     * Function will move values from one prop to another
     *
     * @param int $propFromMove
     * @param int $prop2move
     * @param array $valuesList
     */
    public function moveValues($propFromMove, $prop2move, $valuesList)
    {

        $reverseSql = array();

        // number or strings property type
        if ($prop2move['PROPERTY_TYPE'] != 'L') {

            foreach ($valuesList as $propValue) {

                // 1 replace props
                $strSql = sprintf('UPDATE `%s` SET IBLOCK_PROPERTY_ID = %d WHERE IBLOCK_PROPERTY_ID = %d AND ID = %d',
                    self::TABLE_IB_EL_PROP,
                    (int)$prop2move['ID'],
                    (int)$propFromMove,
                    (int)$propValue);

                $this->DB->Query($strSql);
                ////CPptLogger::getInstance()->log($strSql);
            }

            // list property type
        } else {

            $valuesStr = join(',', $valuesList);
            $strSql = sprintf('SELECT * FROM  `%s` WHERE ID IN (%s)', self::TABLE_IB_EL_PROP, $valuesStr);
            $dbRes = $this->DB->Query($strSql);

            while ($arRes = $dbRes->Fetch()) {

                set_time_limit(60);

                $arNewEnum = array('IBLOCK_PROPERTY_ID' => (int)$prop2move['ID'], 'VALUE' => $arRes['VALUE']);
                $dbSubRes = $this->createEnumValue($arNewEnum);

                if ($arSubRes = $dbSubRes->Fetch()) {

                    $strSql = sprintf('UPDATE `%s` SET IBLOCK_PROPERTY_ID = %d, VALUE = %d, VALUE_ENUM = %d WHERE IBLOCK_PROPERTY_ID = %d AND ID = %d',
                        self::TABLE_IB_EL_PROP,
                        (int)$prop2move['ID'],
                        $arSubRes['ID'],
                        $arSubRes['ID'],
                        (int)$propFromMove,
                        $arRes['ID']);

                    $this->DB->Query($strSql);
                    ////CPptLogger::getInstance()->log($strSql);

                    // 3 delete from props enum
                    $filterStr = sprintf('ID = %d', $arSubRes['ID']);
                    $stepSql = $this->createReverseSql('delete', self::TABLE_IB_PROP_ENUM, $filterStr);
                    $reverseSql = array_merge($reverseSql, $stepSql);
                }

            }

        }

        return $reverseSql;
    }


    /**
     * Function will return array of reverse sql operations for props values move
     *
     * @param int $propFromMove
     * @param int $prop2move
     * @param array $valuesList
     *
     * @return array
     */
    public function undoMoveValues($propFromMove, $prop2move, $valuesList)
    {

        $reverseSql = array();

        // 1 create backup for prop element table
        $strSql = sprintf('SELECT biep.ID
			FROM `%s` biep
			WHERE biep.IBLOCK_PROPERTY_ID = %d',
            self::TABLE_IB_EL_PROP,
            (int)$propFromMove);

        $dbRes = $this->DB->Query($strSql);

        while ($arRes = $dbRes->Fetch()) {
            $filterStr = sprintf('ID = %d', (int)$arRes['ID']);
            $stepSql = $this->createReverseSql('update', self::TABLE_IB_EL_PROP, $filterStr);
            $reverseSql = array_merge($reverseSql, $stepSql);
        }

        return $reverseSql;

    }

}

?>