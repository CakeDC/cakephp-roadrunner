<?php
declare(strict_types=1);

use Cake\Cache\Cache;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\Utility\Security;

/**
 * Test suite bootstrap for SwaggerBake.
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */
$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);

    throw new Exception("Cannot find the root of the application, unable to run tests");
};
$root = $findRoot(__FILE__);
unset($findRoot);

chdir($root);

if (is_file('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('TEST', dirname(__DIR__) . DS . 'tests');
define('ROOT', TEST . DS . 'test_app');
define('APP_DIR', 'test_app');

define('TMP', sys_get_temp_dir() . DS);
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('SESSIONS', TMP . 'sessions' . DS);

define('CAKE_CORE_INCLUDE_PATH', dirname(__DIR__) . DS . 'vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('CORE_TESTS', CORE_PATH . 'tests' . DS);
define('CORE_TEST_CASES', CORE_TESTS . 'TestCase');

ini_set('error_reporting', 'E_ALL ^ E_DEPRECATED');

require_once CORE_PATH . 'config/bootstrap.php';

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

Configure::write('debug', true);

Configure::write('Session', [
    'defaults' => 'php',
]);

Cache::setConfig([
    '_cake_core_' => [
        'engine' => 'Array',
        'prefix' => 'cake_core_',
        'serialize' => true,
    ],
    '_cake_model_' => [
        'engine' => 'Array',
        'prefix' => 'cake_model_',
        'serialize' => true,
    ],
]);

Chronos::setTestNow(Chronos::now());
Security::setSalt('a-long-but-not-random-value');

ini_set('intl.default_locale', 'en_US');
ini_set('session.gc_divisor', '1');

// Fixate sessionid early on, as php7.2+
// does not allow the sessionid to be set after stdout
// has been written to.
session_id('cli');

/**
 * Define fallback values for required constants and configuration.
 * To customize constants and configuration remove this require
 * and define the data required by your plugin here.
 */
//require_once $root . '/vendor/cakephp/cakephp/tests/bootstrap.php';