<?php

/**
 *
 * Plugin Name:       Reference WordPress Knowledgebase Plugin
 * Plugin URI:        https://wordpress.org/plugins/reference-wordpress-knowledgebase/
 * Description:       A lightweight knowledgebase plugin for your WordPress website. Works with any theme.
 * Version:           1.0.0
 * Author:            Dunhakdis
 * Author URI:        https://profiles.wordpress.org/dunhakdis/
 * Text Domain:       reference
 * Domain Path:       /languages
 * License:           GPL2
 *
 * @category Loaders
 * @package  Reference WordPress Knowledgebase Plugin
 * @author   DUNHAKDIS <info@dunhakdis.com>
 * @license  https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GPL2
 * @link     <http://dunhakdis.com>
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    return;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'classes/reference-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'classes/reference-deactivator.php';

/**
 *This action is documented in classes/reference-activator.php
 */
register_activation_hook( __FILE__, array( 'Activator', 'activate' ) );

/**
 *This action is documented in classes/reference-deactivator.php
 */
register_activation_hook( __FILE__, array( 'ReferenceDeactivator', 'deactivate' ) );

/*
 * Create Global Variable
 */
global $dsc_knb;

$dsc_knb = get_option('dsc_knb_settings');

echo gettype(unserialize($dsc_knb));
if(!empty($dsc_knb)){
	$dsc_knb = unserialize($dsc_knb);
}

require_once plugin_dir_path( __FILE__ ) . 'classes/reference-loader.php';

/**
 * This functions executes the plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_loader() {

	$plugin = new \DSC\Reference\Loader();
	$plugin->runner();

}

run_loader();

function run_activator() {

	$plugin = new \DSC\Reference\Activator();
	$plugin->activate();

}

run_activator();