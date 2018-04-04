<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'prophecy_cms');

/** MySQL database username */
define('DB_USER', 'prophecy_cmsuser');

/** MySQL database password */
define('DB_PASSWORD', 'hbS[c-D%%[.p');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'c$hRTm*tVm#H&TW-~wbSpo7&&^@]Z{jL^St D9DR)isp;AH]8qC~N7IyUsl:Hg9v');
define('SECURE_AUTH_KEY',  '$MyD:O1-MX@]1IQG8G;K,pWi2QK<c5?X{[h:6&yY}We7ktQQ_$$.%BK~?>fFV{t^');
define('LOGGED_IN_KEY',    '|s?>$t]BWUqrJ9WTM0uDL(wD;poBDP~;#}Ur@QOTXdO&^F<8)Yy`ri hkCDu[@Q~');
define('NONCE_KEY',        'GYAq(S|G?r5^JQ}P=.}lfXV[LJ/cCzBFPrE3h>[+sy12[ uV5L~UFHB.MvIZpWMe');
define('AUTH_SALT',        'pWCxR#J~XkfYlMp! sJ|h%1q}W>^31pS<({#$Nw9OgVn.~WW).|S<bW9py|tfYP#');
define('SECURE_AUTH_SALT', 'T_1=(=I;i!e|`f{gD(%uDSHZcvkx^9pA!<{Oyff0T3:~+h/?haVcm~uUCo5FASW7');
define('LOGGED_IN_SALT',   '[qq,_V,b{n[49~tW?xa4I^-e1H]}V8}bL0;(4:c5p7Bfo):h{Cs*74eBAJ~EA7;%');
define('NONCE_SALT',       '1@wTz|iEk%,XW(kd@$w.(;P>(I#)~#[x}GIo}O]f@%r_>om[xE9K}*9xU%U$:fG%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'cms_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
