<?php

/**
 * Class CPptLogger
 * Module logger
 */
class CPptLogger {

	private static $_instance;
	private $logFile = NULL;

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
		if (empty($this->logFile)) {
			$this->logFile = $file;
		}
	}

	/**
	 * Function will write to log with template
	 * @return bool
	 */
	public function log($msg) {

		$tmp = '%s: %s FILE: %s, LINE: %s' . PHP_EOL;
		$dbg = debug_backtrace();

		if (empty($this->logFile)) {
			return FALSE;
		}

		$msg = sprintf($tmp, date('d/m/Y H:i:s'), $msg, $dbg[0]['file'], $dbg[0]['line']);
		$MEM = sprintf('%s Mb', round(memory_get_usage(TRUE) / (1024 * 1024), 1));
		$msg = str_replace('MEM', $MEM, $msg);

		file_put_contents($this->logFile, $msg, FILE_APPEND);

		return TRUE;
	}
}