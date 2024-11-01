<?php
/**
 * MyClass Class Doc Comment
 *
 * @package    sparepartslive
 * @subpackage sparepartslive
 */

/*
	Plugin Name: Spareparts.Live
	Plugin URI: https://spareparts.live/plug-ins_and_add-ons.html
	Description: Electronics Parts Catalog solution - Turn your parts catalog drawings into visual navigation for your eCommerce Webshop.
	Version: 1.0.7
	Author: Spareparts.One
	Author URI: https://spareparts.one
	License: GPLv2 or later
	Text Domain: spareparts.live
*/

if (defined('ABSPATH') === false) {
	die;
}

/**
 * Spareparts.Live Plugin for Wordpress/WooCommerce.
 *
 */
class SPLPlugin {

	/**
	 * Token to be injected into the <head> section of the current theme.
	 *
	 * @var string
	 */
	public $token = '';
	public $hidetab = false;

	/**
	 * The path to the config file (persistent change).
	 *
	 * @var string
	 */
	public $configFile = '';

	/**
	 * Construct the class.
	 */
	public function __construct() {
		$this->plugin = plugin_basename(__FILE__);
		$this->configFile = wp_upload_dir() ['basedir'] . '/sparepartslive.json';
		// Add Save button action.
		add_action('admin_post_spl_save_config', [$this, 'saveConfig', ]);
		add_action('admin_post_nopriv_spl_save_config', [$this, 'saveConfig', ]);

	} //end __construct()
	

	
	/**
	 * Add configuration page.
	 *
	 * @return void
	 */
	public function registerPlugin() {
		add_action('admin_menu', [$this, 'addAdminPages', ]);
		// Add "Spareparts.Live" to Wordpress Admin Sidebar.
		add_filter('plugin_action_links_' . $this->plugin, [$this, 'settingsLink', ]);

	} //end registerPlugin()
	

	
	/**
	 * Provides a settings link to the plugin area.
	 *
	 * @param array $links Links of plugin configuration.
	 *
	 * @return array
	 */
	public function settingsLink( array $links) {
		$settingsLink = '<a href="options-general.php?page=sparepartslive_plugin">Settings</a>';
		array_push($links, $settingsLink);
		return $links;

	} //end settingsLink()
	

	
	/**
	 * Adds Spareparts.Live to the Settings section side bar.
	 *
	 * @return void
	 */
	public function addAdminPages() {
		add_options_page('spareparts.live plugin', 'spareparts.live', 'manage_options', 'sparepartslive_plugin', [$this, 'adminIndex', ], 'dashicons-admin-tools', 80);

	} //end addAdminPages()
	

	
	/**
	 * Add the configuration page.
	 *
	 * @return void
	 */
	public function adminIndex() {
		include_once plugin_dir_path(__FILE__) . '/visiblepage.php';

	} //end adminIndex()
	

	
	/**
	 * Activates the plugin.
	 *
	 * @return void
	 */
	public function activatePlugin() {
		flush_rewrite_rules();

	} //end activatePlugin()
	

	
	/**
	 * Deactivates the plugin.
	 *
	 * @return void
	 */
	public function deactivatePlugin() {
		// Deactivate the plugin.
		flush_rewrite_rules();
		wp_dequeue_script('sparepartslivelayer');
	} //end deactivatePlugin()
	

	
	/**
	 * Save config as JSON and update the header.php of the current theme.
	 *
	 * @return void
	 */
	public function saveConfig() {
		// Sanitize token
		if ( isset($_POST['token']) && isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_file_name($_REQUEST['_wpnonce']), 'sparepartslive-nonce' )) {
			$this->token = sanitize_file_name($_POST['token']);
		} else {
			$this->token = '';
		} 
		// Get HideTab setting
		$this->hidetab = isset($_POST['hidetab']);
		file_put_contents($this->configFile, json_encode(['token' => $this->token, 'hidetab' => $this->hidetab]));
		$this->updateLayerScriptTag();
		// Now redirect to the config page again.
		wp_redirect(admin_url('admin.php?page=sparepartslive_plugin'));
		die();

	} //end saveConfig()
	

	
	/**
	 * Find and update the Layer script tag.
	 *
	 * @return void
	 */
	public function updateLayerScriptTag() {
		if (!is_admin()) {
			if (strlen($this->token) == 16 || strlen($this->token) == 21) {
				wp_enqueue_script('sparepartslivelayer', 'https://layer.spareparts.live/layer.js?token=' . $this->token . '&nohandle=' . ($this->hidetab ? "true" : "false"), array(), '4.7');
			} else {
				wp_dequeue_script('sparepartslivelayer');
			} //end if
		} //end if
		
	} //end updateLayerScriptTag()
	

	
	/**
	 * Loads the config from either the layer (if available).
	 * If not then from the local splconfig.json file (if available).
	 *
	 * @return void
	 */
	public function loadConfig() {
		if (file_exists($this->configFile) === true) {
			$config = json_decode(file_get_contents($this->configFile));
			$this->token = $config->token;
			$this->hidetab = $config->hidetab;
			$this->updateLayerScriptTag();
		} //end if
		
	} //end loadConfig()
	

	
} //end class
$splPlugin = new SPLPlugin();
$splPlugin->registerPlugin();
$splPlugin->loadConfig();

// Register Activation callback.
register_activation_hook(__FILE__, [$splPlugin, 'activatePlugin']);

// Register Deactivation callback.
register_deactivation_hook(__FILE__, [$splPlugin, 'deactivatePlugin']);
