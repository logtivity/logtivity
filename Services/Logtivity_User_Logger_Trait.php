<?php

trait Logtivity_User_Logger_Trait
{
	/**
	 * Logtivity_Wp_User
	 * 
	 * @var object
	 */
	public $user;
	
	/**
	 * Set the user for the current log instance
	 * 
	 * @param integer $user_id
	 */
	public function setUser($user_id = null)
	{
		$this->user = new Logtivity_Wp_User($user_id);

		return $this;
	}

	/**
	 * Protected function to get the User ID if the user is logged in
	 * 
	 * @return mixed string|integer
	 */
	protected function getUserID()
	{
		if (!$this->options->shouldStoreUserId()) {
			return;
		}

		if (!$this->user->isLoggedIn()) {
			return;
		}

		return $this->user->id();
	}

	/**
	 * Maybe get the users IP address
	 * 
	 * @return string|false
	 */
	protected function maybeGetUsersIp()
	{
		if (!$this->options->shouldStoreIp()) {
			return;
		}

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	/**
	 * Maybe get the users username
	 * 
	 * @return string|false
	 */
	protected function maybeGetUsersUsername()
	{
		if (!$this->options->shouldStoreUsername()) {
			return null;
		}

		if (!$this->user->isLoggedIn()) {
			return;
		}

		return $this->user->userLogin();
	}
}