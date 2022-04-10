<?php

class Logtivity_Logger extends Logtivity_Api
{
	/**
	 * Can this instance log something
	 * 
	 * @var bool
	 */
	public $active = true;

	/**
	 * Logtivity_Wp_User
	 * 
	 * @var object
	 */
	public $user;

	/**
	 * The action for the given log
	 * 
	 * @var string
	 */
	public $action;

	/**
	 * The context for the given log. Could be a post title, or plugin 
	 * name, or anything to help give this log some more context.
	 * 
	 * @var string
	 */
	public $context;
	
	/**	
	 * The post type, if relevant for a given log
	 * 
	 * @var string
	 */
	public $post_type;

	/**	
	 * The post ID, if relevant for a given log
	 * 
	 * @var integer
	 */
	public $post_id;

	/**	
	 * Extra info to pass to the log
	 * 
	 * @var array
	 */
	public $meta = [];

	/**	
	 * Extra user meta to pass to the log
	 * 
	 * @var array
	 */
	public $userMeta = [];

	/**
	 * When storing a log, generally we want to do this asynchronously
	 * and so we won't wait for a response from the API by default.
	 * 
	 * @var boolean
	 */
	public $waitForResponse = false;

	/**	
	 * Set the user and call the parent constructor
	 */
	public function __construct($user_id = null)
	{
		$this->setUser($user_id);

		parent::__construct();
	}

	/**
	 * Way into class. 
	 * 
	 * @param  string $action
	 * @param  string $meta
	 * @param  string $user_id
	 * @return Logtivity_Logger::send()
	 */
	public static function log($action = null, $meta = null, $user_id = null)
	{
		$Logtivity_logger = new Logtivity_Logger($user_id);

		if(is_null($action)) {

			return new $Logtivity_logger;

		}

		$Logtivity_logger->setAction($action);

		if ($meta) {
			$Logtivity_logger->addMeta($meta['key'], $meta['value']);
		}

		return $Logtivity_logger->send();
	}

	/**
	 * Set the user for the current log instance
	 * 
	 * @param integer $user_id
	 */
	public function setUser($user_id)
	{
		$this->user = new Logtivity_Wp_User($user_id);

		return $this;
	}

	/**
	 * Set the action string before sending
	 * 
	 * @param string
	 */
	public function setAction($action)
	{
		$this->action = $action;

		return $this;
	}

	/**
	 * Set the context string before sending.
	 * 
	 * @param string
	 */
	public function setContext($context)
	{
		$this->context = $context;

		return $this;
	}

	/**
	 * Set the post_type string before sending.
	 * 
	 * @param string
	 */
	public function setPostType($post_type)
	{
		$this->post_type = $post_type;

		return $this;
	}

	/**
	 * Set the post_id before sending.
	 * 
	 * @param integer
	 */
	public function setPostId($post_id)
	{
		$this->post_id = $post_id;

		return $this;
	}

	/**
	 * Add to an array any additional information you would like to pass to this log.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function addMeta($key, $value)
	{
		$this->meta[] = [
			'key' => $key,
			'value' => $value,
		];

		return $this;
	}

	/**	
	 * Add the meta if the first condition is true
	 * 
	 * @param boolean $condition
	 * @param string  $key    
	 * @param mixed  $value
	 */
	public function addMetaIf($condition = false, $key, $value)
	{
		if ($condition) {
			$this->addMeta($key, $value);
		}

		return $this;
	}

	/**
	 * Add to an array of user meta you would like to pass to this log.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function addUserMeta($key, $value)
	{
		$this->userMeta[$key] = $value;

		return $this;
	}

	/**	
	 * Should we wait and record the response from logtivity.
	 * 
	 * @return $this
	 */
	public function waitForResponse()
	{
		$this->waitForResponse = true;

		return $this;
	}

	/**
	 * Stop this instance of Logtivity_Logger from logging
	 * 
	 * @return $this
	 */
	public function stop()
	{
		$this->active = false;

		return $this;
	}

	/**
	 * Send the logged data to Logtivity
	 * 
	 * @return void
	 */
	public function send()
	{
		$this->maybeAddProfileLink();

		do_action('wp_logtivity_instance', $this);

		if (!$this->active) {
			return;
		}

		return $this->makeRequest('/logs/store', $this->getData());
	}

	/**	
	 * Build the data array for storing the log
	 *
	 * @return array
	 */
	protected function getData()
	{
		return [
			'action' => $this->action,
			'context' => $this->context,
			'post_type' => $this->post_type,
			'post_id' => $this->post_id,
			'meta' => $this->getMeta(),
			'user_id' => $this->getUserID(),
			'username' => $this->maybeGetUsersUsername(),
			'user_meta' => $this->getUserMeta(),
			'ip_address' => $this->maybeGetUsersIp(),
		];
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

		} else {
		
			return $_SERVER['REMOTE_ADDR'];

		}
	}

	/**
	 * Build the user meta array
	 *
	 * @return array
	 */
	public function getUserMeta()
	{
		return (array) apply_filters('wp_logtivity_get_user_meta', $this->userMeta);
	}

	/**
	 * Build the meta array
	 *
	 * @return array
	 */
	public function getMeta()
	{
		return (array) apply_filters('wp_logtivity_get_meta', $this->meta);
	}

	/**
	 * Maybe get the users profile link
	 * 
	 * @return string|false
	 */
	protected function maybeAddProfileLink()
	{
		if (!$this->options->shouldStoreProfileLink()) {
			return;
		}

		if (!$this->user->isLoggedIn()) {
			return;
		}

		$profileLink = $this->user->profileLink();

		if ($profileLink == '') {
			return null;
		}

		return $this->addUserMeta('Profile Link', $profileLink);
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
