<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Modules\Database\Database;
use Modules\Exception\Exceptions\ApplicationException;

$configPath = __DIR__ . '/config.json';

if (false === is_readable($configPath)) {

    throw new ApplicationException(ApplicationException::ERROR_MISSING_CONFIG, $configPath);
}

$configJson = file_get_contents($configPath);

$config = json_decode($configJson, true);

if (JSON_ERROR_NONE !== json_last_error()) {

    throw new ApplicationException(ApplicationException::ERROR_INVALID_CONFIG, '[' . json_last_error() . '] ' . json_last_error_msg());
}

if (empty($config['database'])) {

    throw new ApplicationException(ApplicationException::ERROR_CONFIG_MISSING_FIELD, 'database');
}

$dbConfig = $config['database'];

// Initialize database connection, as everywhere else we are going to use same instance of it to work with data
Database::getSharedInstance($dbConfig['host'], $dbConfig['dbname'], $dbConfig['username'], $dbConfig['password'], $dbConfig['port']);