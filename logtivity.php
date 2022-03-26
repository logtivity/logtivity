<?php

/**
 * Plugin Name: Logtivity
 * Plugin URI:  https://logtivity.io
 * Description: Dedicated Event Monitoring for WordPress using Logtivity.io.
 * Version:     1.13.0
 * Author:      Logtivity
 * Text Domain: logtivity
 */

class Logtivity
{
	protected $version = '1.13.0';

	/**
	 * List all classes here with their file paths. Keep class names the same as filenames.
	 * 
	 * @var array
	 */
	private $dependancies = [
		'Helpers/Helpers',
		'Helpers/Logtivity_Wp_User',
		'Admin/Logtivity_Log_Index_Controller',
		'Admin/Logtivity_Dismiss_Notice_Controller',
		'Admin/Logtivity_Options',
		'Admin/Logtivity_Admin',
		'Services/Logtivity_Api',
		'Services/Logtivity_Logger',
		'Services/Logtivity_Register_Site',
		'Helpers/Logtivity_Log_Global_Function',
		'Logs/Logtivity_Abstract_Logger',
	];

	/**
	 *
	 *	Log classes
	 * 
	 */
	private $logClasses = [
		'Logs/Core/Logtivity_Post',
		'Logs/Core/Logtivity_User',
		'Logs/Core/Logtivity_Core',
		'Logs/Core/Logtivity_Theme',
		'Logs/Core/Logtivity_Plugin',
		'Logs/Core/Logtivity_Comment',
		'Logs/Core/Logtivity_Term',
	];

	/**
	 * List all integration dependancies
	 * 
	 * @var array
	 */
	private $integrationDependancies = [
		'WP_DLM' => [
			'Logs/Download_Monitor/Logtivity_Download_Monitor',
		],
		'MeprCtrlFactory' => [
			'Logs/Memberpress/Logtivity_Memberpress',
		],
		'Easy_Digital_Downloads' => [
			'Logs/Easy_Digital_Downloads/Logtivity_Abstract_Easy_Digital_Downloads',
			'Logs/Easy_Digital_Downloads/Logtivity_Easy_Digital_Downloads'
		],
		'EDD_Software_Licensing' => [
			'Logs/Easy_Digital_Downloads/Logtivity_Easy_Digital_Downloads_Software_Licensing'
		],
		'EDD_Recurring' => [
			'Logs/Easy_Digital_Downloads/Logtivity_Easy_Digital_Downloads_Recurring'
		],
		'FrmHooksController' => [
			'Logs/Formidable/Logtivity_FrmEntryFormatter',
			'Logs/Formidable/Logtivity_Formidable'
		]
	];

	public function __construct()
	{
		$this->loadDependancies();

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [$this, 'addSettingsLinkFromPluginsPage'] );

		register_activation_hook( __FILE__, [$this, 'activated']);

		add_action( 'admin_notices', [$this, 'welcomeMessage']);
		
		add_action( 'admin_notices', [$this, 'checkForSiteUrlChange']);

		add_action('admin_enqueue_scripts', [$this, 'loadScripts']);
	}

	public static function log($action = null, $meta = null, $user_id = null)
	{
		return Logtivity_Logger::log($action, $meta, $user_id);
	}

	public function loadDependancies()
	{
		foreach ($this->dependancies as $filePath) 
		{
			$this->loadFile($filePath);
		}

		$this->maybeLoadLogClasses();

		$this->loadIntegrationDependancies();
	}

	public function maybeLoadLogClasses()
	{
		if ($this->defaultLoggingDisabled()) {
			return;
		}

		foreach ($this->logClasses as $filePath) 
		{
			$this->loadFile($filePath);
		}
	}

	/**
	 * Is the default Event logging from within the plugin enabled
	 * 
	 * @return bool
	 */
	public function defaultLoggingDisabled()
	{
		return (new Logtivity_Options)->getOption('logtivity_disable_default_logging');
	}

	public function loadIntegrationDependancies()
	{
		add_action('plugins_loaded', function() {
			foreach ($this->integrationDependancies as $key => $value) 
			{
				if (class_exists($key)) {
					foreach ($value as $filePath) 
					{
						$this->loadFile($filePath);
					}
				}
			}
		});
	}

	public function loadFile($filePath)
	{
		require_once plugin_dir_path( __FILE__ ) . $filePath .'.php';
	}

	public function addSettingsLinkFromPluginsPage($links) 
	{
		if (apply_filters('logtivity_hide_settings_page', false)) {
			return $links;
		}

		$settings_links = array(
			'<a href="' . admin_url( 'admin.php?page=logtivity-settings' ) . '">Settings</a>',
		);
		
		return array_merge($settings_links, $links);
	}

	public function activated()
	{
		if (apply_filters('logtivity_hide_settings_page', false)) {
			return;
		}

		set_transient( 'logtivity-welcome-notice', true, 5 );
	}

	public function welcomeMessage() 
	{
		if(get_transient( 'logtivity-welcome-notice') ) {
			echo logtivity_view('activation');

		    delete_transient( 'logtivity-welcome-notice' );
		}
	}

	public function checkForSiteUrlChange()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			return;
		}

		if(logtivity_has_site_url_changed() && !get_transient( 'dismissed-logtivity-site-url-has-changed-notice')) {
			echo logtivity_view('site-url-changed-notice');
		}
	}

	public function loadScripts()
	{
		wp_enqueue_style( 'logtivity_admin_css', plugin_dir_url(__FILE__) . 'assets/admin.css', false, $this->version );
		wp_enqueue_script( 'logtivity_admin_js', plugin_dir_url(__FILE__) . 'assets/app.js', false, $this->version );
	}
}

$logtivity = new Logtivity;