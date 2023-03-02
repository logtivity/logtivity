<?php

class Logtivity_Api
{
	/**
	 * Option class to access the plugin settings
	 * 
	 * @var object
	 */
	protected $options;

	/**
	 * Should we wait to return the response from the API?
	 * 
	 * @var boolean
	 */
	public $waitForResponse = true;

	/**
	 * Definitely don't wait for a response.
	 * 
	 * @var boolean
	 */
	public $asyncOverride = false;

	/**
	 * The API key for either the site or team
	 * 
	 * @var string
	 */
	public $api_key;

	public function __construct()
	{
		$this->options = new Logtivity_Options;
	}

	/**
	 * Get the API URL for the Logtivity endpoint
	 * 
	 * @return string
	 */
	public function getEndpoint($endpoint)
	{
		return logtivity_get_api_url() . $endpoint;
	}

	public function post($url, $body)
	{
		return $this->makeRequest($url, $body, 'POST');
	}

	public function get($url, $body)
	{
		return $this->makeRequest($url, $body, 'GET');
	}

	public function setApiKey($api_key)
	{
		$this->api_key = $api_key;

		return $this;
	}

	public function async()
	{
		$this->asyncOverride = true;

		return $this;
	}

	/**	
	 * Make a request to the Logtivity API
	 * 
	 * @param  string $url
	 * @param  array $body
	 * @param  string $method
	 * @return mixed $response
	 */
	public function makeRequest($url, $body, $method = 'POST')
	{
		if (!$this->api_key) {
			$this->api_key = logtivity_get_api_key();
		}

		if (!$this->api_key) {
			return;
		}

		if (!$this->options->urlHash()) {
			$this->options->update(['logtivity_url_hash' => md5(home_url())], false);
		}

		if (logtivity_has_site_url_changed()) {
			return;
		}

		$shouldLogLatestResponse = !$this->asyncOverride && ($this->waitForResponse || $this->options->shouldLogLatestResponse());

		$response = wp_remote_post($this->getEndpoint($url), [
			'method' => $method,
			'timeout'   => ( $shouldLogLatestResponse ? 6 : 0.01),
			'blocking'  => ( $shouldLogLatestResponse ? true : false),
			'redirection' => 5,
			'httpversion' => '1.0',
			'headers' => [
				'Authorization' => 'Bearer '.$this->api_key
			],
			'body' => $body,
			'cookies' => array()
		]);

		$response = wp_remote_retrieve_body($response);

		if (empty($response)) {
			return $response;
		}

		if ($shouldLogLatestResponse && $this->notUpdatingWidgetInCustomizer() && $method === 'POST') {
			$this->options->update([
					'logtivity_latest_response' => [
						'date' => date("Y-m-d H:i:s"),
						'response' => print_r($response, true)
					]
				],
				false
			);

			update_option('logtivity_last_settings_check_in_at', ['date' => date("Y-m-d H:i:s")]);

			$body = json_decode($response, true);

			$this->updateSettings($body);
		}

		return $response;
	}

	public function updateSettings($body)
	{
		if (isset($body['settings'])) {
			$this->options->update([
					'logtivity_global_disabled_logs' => $body['settings']['disabled_logs'] ?? null,
					'logtivity_enable_white_label_mode' => $body['settings']['enable_white_label_mode'] ?? null,
					'logtivity_disabled_error_levels' => $body['settings']['disabled_error_levels'] ?? null,
					'logtivity_disable_error_logging' => $body['settings']['disable_error_logging'] ?? null,
					'logtivity_hide_plugin_from_ui' => $body['settings']['hide_plugin_from_ui'] ?? null,
					'logtivity_disable_default_logging' => $body['settings']['disable_default_logging'] ?? null,
					'logtivity_enable_options_table_logging' => $body['settings']['enable_options_table_logging'] ?? null,
					'logtivity_enable_post_meta_logging' => $body['settings']['enable_post_meta_logging'] ?? null,
					'logtivity_custom_plugin_name' => $body['settings']['custom_plugin_name'] ?? null,
				],
				false
			);
		}
	}

	/**	
	 * You cannot call an extra update_option during a widget update so we make 
	 * sure not to log the most recent log response in this case.
	 * 
	 * @return bool
	 */
	private function notUpdatingWidgetInCustomizer()
	{
		if (!isset($_POST['wp_customize'])) {
			return true;
		}

		if (!isset($_POST['action'])) {
			return true;
		}

		return ! ($_POST['action'] === 'update-widget' && $_POST['wp_customize'] === 'on');
	}
}