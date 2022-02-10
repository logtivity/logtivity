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

		if (!$this->api_key) 
		{
			return;
		}

		$shouldLogLatestResponse = $this->waitForResponse || $this->options->shouldLogLatestResponse();

		$response = wp_remote_post($this->getEndpoint($url), [
			'method' => $method,
			'timeout'   => ( $shouldLogLatestResponse ? 45 : 0.01),
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

		if ($shouldLogLatestResponse && $this->notUpdatingWidgetInCustomizer() && $method === 'POST') {

			$this->options->update([
				'logtivity_latest_response' => [
					'date' => date("Y-m-d H:i:s"),
					'response' => print_r($response, true)
				]
			]);

		}

		return $response;
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