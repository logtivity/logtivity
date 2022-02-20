<?php

class Logtivity_Admin
{
	protected $options;
	
	public function __construct()
	{
		add_action( 'admin_menu', [$this, 'registerOptionsPage'] );

		add_action( 'wp_ajax_logtivity_update_settings', [$this, 'update']);
		add_action( 'wp_ajax_nopriv_logtivity_update_settings', [$this, 'update']);

		$this->options = new Logtivity_Options;
	}

	/**
	 * Register the settings page
	 */
	public function registerOptionsPage() 
	{
		if (!apply_filters('logtivity_hide_from_menu', false)) {
			add_menu_page(
				'Logtivity', 
				'Logtivity', 
				'manage_options', 
				'logtivity', 
				[$this, 'showLogIndexPage'], 
				'dashicons-chart-area', 
				26 
			);
		}
		
		if (!apply_filters('logtivity_hide_settings_page', false)) {
			add_submenu_page(
				'logtivity', 
				'Logtivity Settings', 
				'Settings', 
				'manage_options', 
				'logtivity'.'-settings', 
				[$this, 'showLogtivitySettingsPage']
			);
		}
	}

	/**	
	 * Show the admin log index
	 * 
	 * @return void
	 */
	public function showLogIndexPage()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$options = $this->options->getOptions();

		echo logtivity_view('log-index', compact('options'));
	}

	/**	
	 * Show the admin settings template
	 * 
	 * @return void
	 */
	public function showLogtivitySettingsPage() 
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$options = $this->options->getOptions();

		echo logtivity_view('settings', compact('options'));
	}

	/**
	 * Update the settings
	 * 
	 * @return WP_Redirect
	 */
	public function update()
	{
		if (!wp_verify_nonce( $_POST['logtivity_update_settings'], 'logtivity_update_settings' )) 
		{
		    wp_safe_redirect( $this->settingsPageUrl() );
			exit;
			return;
		}

		$user = new Logtivity_Wp_User;

		if (!$user->hasRole('administrator')) {
		    wp_safe_redirect( $this->settingsPageUrl() );
			exit;
			return;
		}

		$this->options->update([
				'logtivity_url_hash' => md5(home_url()),
			],
			false
		);

		delete_transient( 'dismissed-logtivity-site-url-has-changed-notice' );

		$this->options->update();
		
	    wp_safe_redirect( $this->settingsPageUrl() );
	    exit;
	}

	/**
	 * Get the url to the settings page
	 * 
	 * @return string
	 */
	public function settingsPageUrl()
	{
		return admin_url('admin.php?page=logtivity-settings');
	}

}

$Logtivity_Admin = new Logtivity_Admin;

