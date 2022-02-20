<?php

class Logtivity_Dismiss_Notice_Controller
{
	protected $notices = [
		'logtivity-site-url-has-changed-notice'
	];

	public function __construct()
	{
		add_action("wp_ajax_nopriv_logtivity_dismiss_notice", [$this, 'dismiss']);
		add_action("wp_ajax_logtivity_dismiss_notice", [$this, 'dismiss']);
	}

	public function dismiss()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if (!in_array($_POST['type'], $this->notices)) {
			return;
		}

		if (isset($_POST['dismiss_until']) && $_POST['dismiss_until']) {
			set_transient(
				'dismissed-' . $_POST['type'],
				true, 
				(isset($_POST['dismiss_until']) ? intval($_POST['dismiss_until']) : 0)
			);
		} else {
		    update_option('dismissed-' . $_POST['type'], true);
		}

		wp_send_json(['message' => 'success']);
	}
}

new Logtivity_Dismiss_Notice_Controller;