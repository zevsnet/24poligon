<?$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
//$obSection = new CIBlockSection();
//$obSection->Update(3393,['ACTIVE'=>'Y']);
//$obSection->Update(3419,['ACTIVE'=>'Y']);

//����������
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3400', // id - ������� ���� ���������
    'IBLOCK_PROPERTY_ID' => '11775', // id ��������
    'ENUM_VALUE_NAME' => '96566'//�� - �������� ������
]);
//�������
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3419', // id - ������� ���� ���������
    'IBLOCK_PROPERTY_ID' => '11541', // id ��������
    'ENUM_VALUE_NAME' => '94746'//�� - �������� ������
]);
//���������(�����) - ����
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3393', // id - ������� ���� ���������
    'IBLOCK_PROPERTY_ID' => '11532', // id ��������
    'ENUM_VALUE_NAME' => '94709'//�� - �������� ������
]);
//���������(�����) - �����
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3393', // id - ������� ���� ���������
    'IBLOCK_PROPERTY_ID' => '11532', // id ��������
    'ENUM_VALUE_NAME' => '94708'//�� - �������� ������
]);
