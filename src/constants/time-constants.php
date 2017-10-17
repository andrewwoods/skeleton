<?php
/**
 * Time Constants
 *
 * @package Constants\Time
 */


/**
 * The number of minutes for 1 hour
 */
define('HOUR_IN_MINUTES', 60);

/**
 * The number of hours in 1 day
 */
define('DAY_IN_HOURS', 24);

/**
 * The number of days in a week
 */
define('WEEK_IN_DAYS', 7);

/**
 * The number of days in the average month as an integer
 *
 * (31+28+31+30+31+30+31+31+30+31+30+31)/12 == * 30.416666667
 */
define('MONTH_IN_DAYS', 30);

/**
 * The number of days in a standard year
 */
define('YEAR_IN_DAYS', 365);

/**
 * The number of days in leap year
 */
define('LEAP_YEAR_IN_DAYS', 366);


/**
 * The number of seconds for 1 minute
 */
define('MINUTE_IN_SECONDS', 60);

/**
 * The number of seconds for 1 hour
 */
define('HOUR_IN_SECONDS', HOUR_IN_MINUTES * MINUTE_IN_SECONDS);

/**
 * The number of seconds for 1 day
 */
define('DAY_IN_SECONDS', DAY_IN_HOURS * HOUR_IN_SECONDS);

/**
 * The number of seconds for 1 week (7 days)
 */
define('WEEK_IN_SECONDS', WEEK_IN_DAYS * DAY_IN_SECONDS);

/**
 * The number of seconds for 1 month (30 days)
 */
define('MONTH_IN_SECONDS', MONTH_IN_DAYS * DAY_IN_SECONDS);

/**
 * The number of seconds for 1 year (365 days)
 */
define('YEAR_IN_SECONDS', YEAR_IN_DAYS * DAY_IN_SECONDS);

/**
 * The number of seconds for leap year (366 days)
 */
define('LEAP_YEAR_IN_SECONDS', LEAP_YEAR_IN_DAYS * DAY_IN_SECONDS);


