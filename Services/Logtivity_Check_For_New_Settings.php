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

		$api = new Logtivity_Api;

		$response = $api->post('/settings-check', []);

		$response = wp_remote_retrieve_body($response);

		$body = json_decode($response, true);

		$api->updateSettings($body);
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