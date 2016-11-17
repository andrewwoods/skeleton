<?php
/**
 * Your Application Constants
 *
 * @package Application
 */


/*
 * Version Constants
 * -----------------------------------------------------------------------------
 */

/**
 * The application uses semantic versioning
 *
 * @link http://semver.org Semantic Versioning website
 */
define('VERSION', '0.1.0');

/**
 * The minimum version of PHP needed
 *
 * @link https://secure.php.net/releases/5_2_2.php
 *
 */
define('MINIMUM_PHP_VERSION', '7.0.0');


/*
 * Application Directory Constants
 * -----------------------------------------------------------------------------
 */

/**
 * This represents the root of the project
 *
 * It's where each of the applications are located
 */
define('APP_DIR', dirname(__FILE__));

/**
 * The 'etc' directory is a directory for things that don't fit elsewhere
 *
 */
define('ETC_DIR', APP_DIR . '/etc');

/**
 * The 'lib' directory is a library for your application
 *
 */
define('LIB_DIR', APP_DIR . '/lib');

/**
 * Global include path
 */
define('PUBLIC_DIR', APP_DIR . '/public');


