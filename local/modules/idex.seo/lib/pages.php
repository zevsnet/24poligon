<?php
namespace Idex\Seo;

use Bitrix\Main;

/**
 * Class SeoPagesTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> URL string(255) mandatory
 * <li> QUERY string(255) mandatory
 * <li> USE_GET bool optional default 'N'
 * <li> USE_INHERITANCE bool optional default 'N'
 * <li> ACTIVE bool optional default 'Y'
 * <li> TITLE string optional
 * <li> BROWSER_TITLE string optional
 * <li> KEYWORDS string optional
 * <li> DESCRIPTION string optional
 * <li> SEO_TEXT string optional
 * <li> SEO_TEXT_2 string optional
 * </ul>
 *
 **/

class PagesTable extends Main\Entity\DataManager
{
	/**
	 * @param array $data
	 * @return Main\Entity\AddResult
	 * @throws Main\ArgumentException
	 * @throws \Exception
	 */
	public static function add(array $data)
	{
	    $result = new Main\Entity\AddResult();
		if(!$data['URL']) {
			$result->addError(new Main\Error('Need $data[\'URL\']'));
			return $result;
		}
		$item = self::getByUrl($data['URL']);
		if(!$item) {
			return parent::add($data);
		}

		$res = self::update($item['ID'], $data);
		if(!$res->isSuccess()) {
			$result->addErrors($res->getErrors());
			return $result;
		}
		$result->setId($item['ID']);

		return $result;
	}

	/**
	 * @param $url
	 * @return array|false
	 * @throws Main\ArgumentException
	 */
	public static function getByUrl($url)
	{

		$item = self::getList([
			'filter' => [
				'=URL' => $url
			],
			'limit' => 1,
			'cache'=> ["ttl"=>360000]
		])->fetch();
		return $item;

	}

	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'bm_idex_seo_pages';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID',
			),
			'URL' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateUrl'),
				'title' => 'URL',
			),
			'QUERY' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateQuery'),
				'title' => 'QUERY',
			),
			'USE_GET' => array(
				'data_type' => 'boolean',
				'values' => array('N', 'Y'),
				'title' => 'USE_GET',
			),
			'USE_INHERITANCE' => array(
				'data_type' => 'boolean',
				'values' => array('N', 'Y'),
				'title' => 'USE_INHERITANCE',
			),
			'ACTIVE' => array(
				'data_type' => 'boolean',
				'values' => array('N', 'Y'),
				'title' => 'ACTIVE',
			),
			'TITLE' => array(
				'data_type' => 'text',
				'title' => 'TITLE',
			),
			'BROWSER_TITLE' => array(
				'data_type' => 'text',
				'title' => 'BROWSER_TITLE',
			),
			'KEYWORDS' => array(
				'data_type' => 'text',
				'title' => 'KEYWORDS',
			),
			'DESCRIPTION' => array(
				'data_type' => 'text',
				'title' => 'DESCRIPTION',
			),
			'SEO_TEXT' => array(
				'data_type' => 'text',
				'title' => 'SEO_TEXT',
			),
			'SEO_TEXT_2' => array(
				'data_type' => 'text',
				'title' => 'SEO_TEXT_2',
			),
            'DOMAIN' => array(
                'data_type' => 'text',
                'title' => 'DOMAIN',
            ),
		);
	}
	/**
	 * Returns validators for URL field.
	 *
	 * @return array
	 */
	public static function validateUrl()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for QUERY field.
	 *
	 * @return array
	 */
	public static function validateQuery()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
}