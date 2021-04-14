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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'booking' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '35N V2yDMB9j)(5p9V+ OLMdeoK&Syj{tYm>Vo^,op+<qRl];6k+gEdgOrgXi.$}' );
define( 'SECURE_AUTH_KEY',  'qrb)C{];4j|XOhl# 0Ue?hok@9Ke25MB}_bL$%_b]uuoBtOZ1>>@lqL]hw0?YQaX' );
define( 'LOGGED_IN_KEY',    '#99p8NoC$E4B2Q||A3MbYw;a<Ae/pg2;>~d5_AQ<YvW?;(ncC}FdiP$$s-5aB41q' );
define( 'NONCE_KEY',        '<zLED1gQ+<xN|@I-W_I>tlWxXZM H4[2-h-@c-*;bj/{CQN|0276v7,!SCTl{1C=' );
define( 'AUTH_SALT',        'CO#)ENYO?[.SCftFz!yRXqNqGN^CYvGC.)HlD?`SNjce~K&p0!xvS:@kuPVm9L#-' );
define( 'SECURE_AUTH_SALT', 'QfUZ{W48~aB(q|P<~0&dJ8P4#D;V-QB@DD!Dp}qOE#tN+3.UHR_{RNOx+`nmoNH`' );
define( 'LOGGED_IN_SALT',   '0,%;SWR1cpN3b8tug:8$C<@0QwF+b$|DM6B =?%=<DZ;o*m&*q9w7mL B$nNb2yt' );
define( 'NONCE_SALT',       'I7/1JWcyGK6qNEqo#29&0jYN^Il|?c&6f^._8#C8qwg-aZ?>F<Os;0n/O:m18b,%' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
