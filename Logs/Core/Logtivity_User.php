<?php

class Logtivity_User extends Logtivity_Abstract_Logger
{
	protected static $loggedUserlogin = false;

	public function registerHooks()
	{
		add_action('wp_login', [$this, 'wpLogin'], 10, 2);
		add_action('set_logged_in_cookie', [$this, 'preSetLoggedInCookie'], 10, 6);
		add_action('wp_logout', [$this, 'userLoggedOut'], 10, 1);
		add_action( 'user_register', [$this, 'userCreated'], 10, 1 );
		add_action( 'delete_user', [$this, 'userDeleted'] );
		add_action( 'profile_update', [$this, 'profileUpdated'], 10, 2 );
	}

	public function preSetLoggedInCookie($logged_in_cookie, $expire, $expiration, $user_id, $scheme, $token)
	{
		if (self::$loggedUserlogin) {
			return;
		}
		
		return $this->userLoggedIn($user_id);
	}

	public function wpLogin($user_login, $user)
	{
		if (self::$loggedUserlogin) {
			return;
		}

		return $this->userLoggedIn($user->ID);
	}

	public function userLoggedIn( $user_id ) 
	{
		self::$loggedUserlogin = true;

		$logtivityUser = new Logtivity_WP_User($user_id);

		return (new Logtivity_Logger($user_id))
			->setAction('User Logged In')
			->setContext($logtivityUser->getRole())
			->send();
	}

	public function userLoggedOut($user_id)
	{
		if ($user_id == 0) {
			return;
		}
		
		$user = new Logtivity_WP_User($user_id);

		return (new Logtivity_Logger($user_id))
			->setAction('User Logged Out')
			->setContext($user->getRole())
			->send();
	}

	public function userCreated($user_id)
	{
		$log =  Logtivity_Logger::log();

		if (!is_user_logged_in()) {
			$log->setUser($user_id);
		}

		$user = new Logtivity_WP_User($user_id);

		$log->setAction('User Created')
			->setContext($user->getRole())
			->addMeta('Username', $user->userLogin())
			->send();
	}

	public function userDeleted($user_id)
	{
		$user = new Logtivity_WP_User($user_id);

		return Logtivity_Logger::log()
			->setAction('User Deleted')
			->setContext($user->getRole())
			->addMeta('Username', $user->userLogin())
			->send();
	}

	public function profileUpdated($user_id, $old_user_data)
	{
		$user = new Logtivity_WP_User($user_id);

		return Logtivity_Logger::log()
			->setAction('User Updated')
			->setContext($user->getRole())
			->addMeta('Username', $user->userLogin())
			->send();
	}
}

$Logtivity_User = new Logtivity_User;