<?php

/**
 * Class CFactory
 * Factory return operator by specific type of props
 */
class CFactory {

	/**
	 * Function will return operator with specific type
	 * @return object
	 */
	public static function createOperator($type) {
		switch($type) {
			case "NUMBER":
				return new CDbNumberOperator;

			case "STRING":
				return new CDbStringOperator;

			default:
				return new CDbListOperator;
		}
	}
}
