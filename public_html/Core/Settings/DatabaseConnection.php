<?php

namespace Core\Settings;

use Dorm\Database;

class DatabaseConnection {
	public static $connected = false;

	public function __construct() {
		if (self::$connected) return;

		try {
			Database::connect([
				'dbname' => 'database',
				'user' => 'admin',
				'password' => 'password',
				'host' => 'mariadb'
			]);
		} catch (\Exception $e) {
			throw $e;
		}

		self::$connected = true;
	}
}
