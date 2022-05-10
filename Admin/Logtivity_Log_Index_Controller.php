<?php

class Logtivity_Log_Index_Controller
{
	public function __construct()
	{
		add_action("wp_ajax_nopriv_logtivity_log_index_filter", [$this, 'search']);
		add_action("wp_ajax_logtivity_log_index_filter", [$this, 'search']);
	}

	public function search()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$response = json_decode(
			(new Logtivity_Api)->get('/logs', [
				'page' => $this->getInput('page'),
				'action' => $this->getInput('search_action'),
				'context' => $this->getInput('search_context'),
				'action_user' => $this->getInput('action_user'),
			])
		);

		if (property_exists($response, 'message') && $response->message) {
			return $this->errorReponse($response->message);
		}

		return $this->successResponse($response);
	}

	private function successResponse($response)
	{
		return wp_send_json([
			'view' => logtivity_view('_logs-loop', [
				'logs' => $response->data,
				'meta' => $response->meta,
				'hasNextPage' => $response->links->next,
			])
		]);
	}

	private function errorReponse($message)
	{
		return wp_send_json([
			'view' => logtivity_view('_logs-loop', [
				'message' => $message,
				'logs' => [],
			])
		]);
	}

	private function getInput($field)
	{
		return ( isset($_GET[$field]) && is_string($_GET[$field]) ? $_GET[$field] : null);
	}
}

new Logtivity_Log_Index_Controller;