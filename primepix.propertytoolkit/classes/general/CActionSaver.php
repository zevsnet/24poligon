<?php

/**
 * Class CActionSaver
 * Operator save reverse sql into log
 */
class CActionSaver {

	private static $_instance;
	private $actionFile = NULL;

	// singleton
	private function __construct() { }
	private function __clone() { }

	/**
	 * Function will get class instance
	 * @return mixed
	 */
	public static function getInstance() {
		if (NULL === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Function will init logger
	 * @param  int $id
	 * @return mixed
	 */
	public function init($file) {
		if (empty($this->actionFile)) {
			$this->actionFile = $file;
		}
		if (!file_exists($this->actionFile)) {
			file_put_contents($this->actionFile, '');
		}
		return $this;
	}

	/**
	 * Function will write to log sql
	 * @return bool
	 */
	public function saveSql($reverseSql) {

		if (empty($this->actionFile) || !is_readable($this->actionFile)) {
			return FALSE;
		}
		$reverseSql = join(PHP_EOL, $reverseSql);

		file_put_contents($this->actionFile, $reverseSql);

		return TRUE;
	}

	/**
	 * Function will read sql from log
	 * @return array
	 */
	public function readSql() {

		if (!$this->checkFile()) {
			return FALSE;
		}

		$fileContents = file_get_contents($this->actionFile);
		$reverseSql   = explode(";", $fileContents);

		if (!is_array($reverseSql)) {
			$reverseSql = array($reverseSql);
		}
		return $reverseSql;
	}

	/**
	 * Function will delete all sql from log
	 * @return bool
	 */
	public function clearSql() {

		if (!$this->checkFile()) {
			return FALSE;
		}

		file_put_contents($this->actionFile, '');
		return TRUE;
	}

	/**
	 * Function will chec log file exist and access
	 * @return bool
	 */
	public function checkFile() {

		if (empty($this->actionFile) || !is_readable($this->actionFile) || filesize($this->actionFile) == 0) {
			return FALSE;
		}

		return TRUE;
	}
}