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
define('DB_NAME', 'prophecy');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'd3lK!OVoeqWjm;H&7v*k^wIc[b7Q&e,yeW_{mbD{M;!KCj|w{XKPV3*@ES_c{ETu');
define('SECURE_AUTH_KEY',  '}{f{|#c#HT$U/_lZb;8AlV-0P%Mv5uz%dzGW*gyq.GqSN3i /k%bdmt hn)9HMBS');
define('LOGGED_IN_KEY',    '-9__OGmn!Eihx^e<,,!M2~pH&[dG&#Zo|YdoeaY-rKQOn~:-N30m;MpMRj@HTuF|');
define('NONCE_KEY',        'I{ dWYCavIx/EGerovhRRLL_vfP~w_hGk7UW=X[<;aw~G*WJEP@PXygvX]{&aFsW');
define('AUTH_SALT',        'Gi)6K;#9h#sxGU]?tsat~@]vK!G5_tIZ`=!5KngTc@L7nzv2`v=Q=PFP29NU/E~7');
define('SECURE_AUTH_SALT', 'lXt^*2p.zI#v0Qw~%khN6`=~>Y]X`*U1$9E+IcC`^~pA|0Djf{tK;D-^j5BeI4Fc');
define('LOGGED_IN_SALT',   'hM/YcD:v/%IL3Cm+~T[r _^1|o8*aix:xWTB%v(qhGOI;rE2T*IB/jY:tft&qZG;');
define('NONCE_SALT',       ' z=P4z{(~SimgXYNC_ vFkM$hvl_DK~::~Qk.z3&vvLWm$m4VewcXX5+tTcd%RsL');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
