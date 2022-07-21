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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'bEH5k5hDkoZwVrlzMyJFVHpZKSyy3F16MWpM2MpSmV9mYzAtmO1XNNvMgV+Zf4clsefo1cP8Oz7piSgmDAwQ4A==');
define('SECURE_AUTH_KEY',  '7bRlq4P7urQ1YC4tTkAAZFc+JbGCrhSqfja/zpko7dV0h842DldraK4Tc8gdCX4zJiFOpvqdGSuqZOhNBgFmLQ==');
define('LOGGED_IN_KEY',    'UHn8N1zIkSFj2Ybo5V/3PMjBNu91toYRiMWDP1uT+ftz0gRZ6z2Or6TXVDIZTCN/DxmeXL/iqOJ3Mg88sJ/Ywg==');
define('NONCE_KEY',        '69aN+StFRZoc9lmAzZEq6tjucBgr8FCZv+vi8dTorsn5b/7XxWrSOXwjqp6DyBHBfM5npONIYXLwhpc1MHHqyg==');
define('AUTH_SALT',        '/bV6zM283pbEG35FLE0UEG+XOl8gEosQpcMsjWZ9fPZAovGQ43T/WnDP+f8CTny5tRPnsSE5tWZSvpaWT7ZbdA==');
define('SECURE_AUTH_SALT', 'UokWzXQW3NmjE5P+3XXsSk0XNiEFoO1KXxZm++BF7T8bdV6kKg0AAoS333cbGnh/dp5UqmKulcmn6rYWS00C0A==');
define('LOGGED_IN_SALT',   'cW0iAAQbKKmaJd932cz7Vt2wzS6FcNTHFcAZzi5SdpQoS/OkUnkYQ20mMYx7lA9t57bfM1gZB0Tc93xsrjf6CA==');
define('NONCE_SALT',       '9K9/XKLBRupWEzBUR3xiqVgy8faKvlYtTryYdEhn3DbH9kF7uFwSqpgAMPttNxh3W8O9mrBKvPcgLf9PsGNdLA==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
