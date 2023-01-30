<?php

class Logtivity_Check_For_New_Settings
{
	public function __construct()
	{
		add_action('init', [$this, 'maybeCheckForNewSettigns']);
	}

	public function maybeCheckForNewSettigns()
	{
		if (!$this->shouldCheckInWithApi()) {
			return;
		}

		update_option('logtivity_last_settings_check_in_at', ['date' => date("Y-m-d H:i:s")]);

		$response = json_decode(
			(new Logtivity_Api)
				->post('/settings-check', [])
		);

		if (is_object($response) && property_exists($response, 'settings')) {
			(new Logtivity_Options)->update([
					'logtivity_global_disabled_logs' => $response->settings->disabled_logs,
					'logtivity_enable_white_label_mode' => $response->settings->enable_white_label_mode,
					'logtivity_disabled_error_levels' => $response->settings->disabled_error_levels,
					'logtivity_disable_error_logging' => $response->settings->disable_error_logging,
					'logtivity_hide_plugin_from_ui' => $response->settings->hide_plugin_from_ui ?? null,
					'logtivity_disable_default_logging' => $response->settings->disable_default_logging ?? null,
					'logtivity_enable_options_table_logging' => $response->settings->enable_options_table_logging ?? null,
					'logtivity_enable_post_meta_logging' => $response->settings->enable_post_meta_logging ?? null,
				],
				false
			);
		}
	}

	public function shouldCheckInWithApi()
	{
		$latestReponse = get_option('logtivity_last_settings_check_in_at');

		if (is_array($latestReponse) && isset($latestReponse['date'])) {
			return time() - strtotime($latestReponse['date']) > 10 * MINUTE_IN_SECONDS; // 10 minutes
		}

		return true;
	}
}

$Logtivity_Check_For_New_Settings = new Logtivity_Check_For_New_Settings;