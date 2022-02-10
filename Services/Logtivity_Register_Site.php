<?php

class Logtivity_Register_Site
{
	public static function execute($data = [])
	{
		$logtivity_options = new Logtivity_Options;

		if ($logtivity_options->getApiKey()) {
		    return new WP_Error(
		    	'logtivity_register_site_error', 
		    	__('You cannot register a site that already has an API Key.', 'logtivity')
		    );
		}

		$response = json_decode(
			(new Logtivity_Api)->setApiKey($data['team_api_key'])
				->post('/sites', [
					'name' => $data['name'] ?? home_url(),
					'url' => $data['url'] ?? home_url(),
				])
		);

		if ($response && property_exists($response, 'message')) {
			return wp_send_json(['response' => $response->message]);
		}

		$logtivity_options->update(
			array_merge(
				$data,
				[
					'logtivity_site_api_key' => $response->api_key
				]
			),
		);
	}
}