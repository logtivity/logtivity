<?php

class Logtivity_Core extends Logtivity_Abstract_Logger
{
	public function registerHooks()
	{
		add_action( 'upgrader_process_complete', [$this, 'upgradeProcessComplete'], 10, 2);
		add_action( 'wp_update_nav_menu', [$this, 'menuUpdated'], 10, 2 );
		add_filter( 'widget_update_callback', [$this, 'widgetUpdated'], 10, 4);
		add_action('init', [$this, 'maybeSettingsUpdated']);
		add_action( 'permalink_structure_changed', [$this, 'permalinksUpdated'], 10, 2);
		add_action( 'update_option', [$this, 'optionUpdated'], 10, 3);

		// do_action_ref_array( 'phpmailer_init', array( &$phpmailer ) );
	}

	public function upgradeProcessComplete( $upgrader_object, $options ) 
	{
	    if ( $options['type'] != 'core' ) {
	    	return;
	    }

		if ($options['action'] == 'update') {
			return $this->coreUpdated($upgrader_object, $options);
		}

		if ($options['action'] == 'install') {
			return $this->coreInstalled($upgrader_object, $options);
		}
	}

	public function coreUpdated($upgrader_object, $options)
	{
		return Logtivity_Logger::log('WP Core Updated');
	}

	public function coreInstalled($upgrader_object, $options)
	{
		return Logtivity_Logger::log('WP Core Installed');
	}

	public function menuUpdated($nav_menu_id, $menuData = [])
	{
		if (isset($menuData['menu-name'])) {
			return Logtivity_Logger::log()
				->setAction('Menu Updated')
				->setContext($menuData['menu-name'])
				->addMeta('Menu ID', $nav_menu_id)
				->send();
		}
	}

	public function widgetUpdated($instance, $new, $old, $obj)
	{
		Logtivity_Logger::log()
			->setAction('Widget Updated')
			->setContext($obj->name)
			->addMeta('New Content', $new)
			->addMeta('Old Content', $old)
			->send();

		return $instance;
	}

	public function maybeSettingsUpdated()
	{
		if (!isset($_POST['option_page']) || !$_POST['option_page'] || !isset($_POST['action']) || $_POST['action'] != 'update') {
			return;
		}
		if (!in_array($_POST['option_page'], ['writing', 'general', 'reading', 'discussion', 'media'])) {
			return;
		}

		Logtivity::log()
			->setAction('Settings Updated')
			->setContext('Core:'.$_POST['option_page'])
			->send();
	}

	public function optionUpdated($option, $old_value, $value)
	{
		if ($this->getRequestMethod() == 'GET') {
			return;
		}
		
		if (!is_admin() || $old_value == $value) {
			return;
		}

		$ignore = [
			'cron',
			'action_scheduler_lock_async-request-runner',
			'wp_all_export_pro_addons_not_included',
			'logtivity_latest_response',
			'logtivity_api_key_check',
			'logtivity_url_hash',
			'logtivity_global_disabled_logs',
			'logtivity_enable_white_label_mode',
			'logtivity_disabled_error_levels',
			'logtivity_disable_error_logging',
			'logtivity_hide_plugin_from_ui',
			'recently_activated',
			'active_plugins',
			'jp_sync_last_success_sync',
			'jp_sync_retry_after_sync',
			'postman_state',
			'jetpack_sync_settings_dedicated_sync_enabled',
			'jetpack_plugin_api_action_links',
			'stats_cache',
			'admin_email_lifespan',
			'db_upgraded',
			'delete_blog_hash',
			'adminhash',
			'auto_plugin_theme_update_emails',
			'_wp_suggested_policy_text_has_changed',
			'ftp_credentials',
			'uninstall_plugins',
			'wp_force_deactivated_plugins',
			'fresh_site',
			'allowedthemes',
			'rxpp_blocked_methods_count',
			'wordfence_syncAttackDataAttempts',
			'akismet_spam_count',
			'jetpack_next_sync_time_sync',
			'jetpack_updates_sync_checksum',
			'wpcf7',
			'gmt_offset',
			'_edd_table_check',
			'woocommerce_marketplace_suggestions',
			'recently_edited',
			'rewrite_rules',
			'limit_login_retries',
			'post_views_count',
			'mepr_rules_db_cleanup_last_run',
			'mepr_products_db_cleanup_last_run',
			'mepr_coupons_expire_last_run',
			'mepr_groups_db_cleanup_last_run',
		];

		if (in_array($option, $ignore)) {
			return;
		}

		$wildcardIgnores = [
			'transient',
			'cache',
			'auto_updater',
			'wpe',
			'edd_api',
			'edd_sl',
			'frm_',
		];

		foreach ($wildcardIgnores as $wildcard) {
			if (strpos($option, $wildcard) !== false) {
				return;
			}
		}

		Logtivity::log()
			->setAction('Option Updated')
			->setContext($option)
			->addMetaIf(!is_array($old_value) && !is_object($old_value), 'Old Value', $old_value)
			->addMetaIf(!is_array($value) && !is_object($value), 'New Value', $value)
			->send();
	}

	private function getRequestMethod()
	{
		return $_SERVER['REQUEST_METHOD'] ?? null;
	}

	public function permalinksUpdated($old_permalink_structure, $permalink_structure)
	{
		Logtivity::log()
			->setAction('Permalinks Updated')
			->setContext($this->getPermalinkStructure($permalink_structure))
			->addMeta('Old Structure', $this->getPermalinkStructure($old_permalink_structure))
			->send();
	}

	private function getPermalinkStructure($value)
	{
		return ( $value == '' ? 'Plain' : $value);
	}
}

$Logtivity_Core = new Logtivity_Core;