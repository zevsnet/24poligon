<?php

namespace SB\Bitrix\Tools\Orm;

use Bitrix\{
    Main\ArgumentException, Main\Entity\ExpressionField, Main\Entity\Query, Main\Entity\ReferenceField
};

class File
{
    /**
     * @param Query $query
     * @param string $fieldName
     * @param string $refFieldName
     * @param string $refName
     *
     * @return Query $query
     *
     * @throws ArgumentException
     */
    public static function attachToQuery(
        Query $query,
        string $fieldName,
        string $refFieldName,
        string $refName = ''
    ): Query {
        if (empty($refName)) {
            $refName = $refFieldName . '_PATH';
        }
        static::attachReferenceField($query, $refName, $refFieldName);
        static::attachPathExpressionField($query, $fieldName, $refName);
        return $query;
    }

    /**
     * @param Query $query
     * @param string $fieldName
     * @param string $refName
     *
     * @return Query $query
     *
     * @throws ArgumentException
     */
    public static function attachReferenceField($query, string $fieldName, string $refName): Query
    {
        $query->registerRuntimeField('', static::getReferenceField($fieldName, $refName));
        return $query;
    }


    /**
     * @param Query $query
     * @param string $fieldName
     * @param string $refName
     *
     * @return Query $query
     */
    public static function attachPathExpressionField($query, $fieldName, $refName): Query
    {
        $query->registerRuntimeField('', static::getPathExpressionField($fieldName, $refName));
        return $query;
    }

    /**
     * @param string $fieldName
     * @param string $refName
     *
     * @return ReferenceField
     *
     * @throws ArgumentException
     */
    public static function getReferenceField(string $fieldName, string $refName): ReferenceField
    {
        return new ReferenceField(
            $fieldName,
            '\Bitrix\Main\File',
            ['=this.' . $refName => 'ref.ID'],
            ['join_type' => 'LEFT']
        );
    }

    /**
     * @param string $fieldName
     * @param string $refName
     * @param string $delimiter
     *
     * @return ExpressionField
     */
    public static function getPathExpressionField(
        string $fieldName,
        string $refName,
        string $delimiter = '.'
    ): ExpressionField {
        return new ExpressionField(
            $fieldName,
            "CONCAT(%s,'***',%s)",
            [$refName . $delimiter . 'SUBDIR', $refName . $delimiter . 'FILE_NAME'],
            [
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            $fileArr = explode('***', $value);
                            $results['SUBDIR'] = $fileArr[0];
                            $results['FILE_NAME'] = $fileArr[1];
                            $resFile = '';
                            if (!empty($fileArr[0])) {
                                $resFile = \Cfile::GetFileSRC($results, false, false);
                            }
                            return $resFile;
                        }
                    ];
                }
            ]
        );
    }
}