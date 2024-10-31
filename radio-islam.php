<?php

/**
 * Plugin Name: 	Radio Islam
 * Plugin URI: 		http://radioislam.or.id/
 * Description: 	Radio Islam Indonesia (RII) Menampilakn daftar radio streaming Islam tepercaya di Indonesia yang menyandarkan dakwahnya berdasar al-Qur'an, as-Sunnah, seperti yang dipahami oleh para shahabat radhiyallaahu'anhum.
 * Version: 		2.1.5
 * Author: 			OaseMedia
 * Author URI: 		http://radioislam.or.id/
 * Text Domain:     rii
 * Domain Path:     /languages
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// define plugin constants
define( 'RII_PLUGIN_FILE', __FILE__ );
define( 'RII_PLUGIN_VERSION', '2.1.5' );

// include widget class
require_once( 'includes/widget.php' );
