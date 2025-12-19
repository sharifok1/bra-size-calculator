<?php
/**
 * Plugin Name: Bra Size Calculator
 * Plugin URI: https://wordpress.org/plugins/bra-size-calculator/
 * Description: Bra size calculator with Bangladesh standard sizing rules.
 * Version: 1.0.0
 * Author: sharifok
 * Author URI: https://github.com/sharifok1/bra-size-calculator
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: brasical-bra-size-calculator
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'BRASICAL_CALC_PATH', plugin_dir_path( __FILE__ ) );
define( 'BRASICAL_CALC_URL', plugin_dir_url( __FILE__ ) );

// Frontend shortcode + assets
require_once BRASICAL_CALC_PATH . 'includes/brasical-calculator-frontend.php';
// Admin dashboard
if ( is_admin() ) {
    require_once BRASICAL_CALC_PATH . 'includes/brasical-calculator-admin.php';
}
