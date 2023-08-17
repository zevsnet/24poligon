<?
function createField($entityId, $fieldName, $fieldType = 'string') {
	$arUserField = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => $entityId, "FIELD_NAME" => $fieldName))->Fetch();
	if(!$arUserField)
	{
		$arFields = array(
			"FIELD_NAME" => $fieldName,
			"ENTITY_ID" => $entityId,
			"USER_TYPE_ID" => $fieldType,
			"XML_ID" => $fieldName,
			"SORT" => 100,
			"MULTIPLE" => "N",
			"MANDATORY" => "N",
			"SHOW_FILTER" => "I",
			"SHOW_IN_LIST" => "Y",
			"EDIT_IN_LIST" => "Y",
			"IS_SEARCHABLE" => "N",
		);
		$ob = new CUserTypeEntity();
		$FIELD_ID = $ob->Add($arFields);
		return $FIELD_ID;
	} else {
		return false;
	}
}
?>