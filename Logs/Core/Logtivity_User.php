<?php

class Logtivity_User extends Logtivity_Abstract_Logger
{
	public function registerHooks()
	{
		add_action('wp_login', [$this, 'userLoggedIn'], 10, 2);
		add_action('clear_auth_cookie', [$this, 'userLoggedOut']);
		add_action( 'user_register', [$this, 'userCreated'], 10, 1 );
		add_action( 'delete_user', [$this, 'userDeleted'] );
		add_action( 'profile_update', [$this, 'profileUpdated'], 10, 2 );
	}

	public function userLoggedIn( $user_login, $user ) 
	{
		$logtivityUser = new Logtivity_WP_User($user->ID);

		return (new Logtivity_Logger($user))
	    	->setAction('User Logged In')
	    	->setContext($logtivityUser->getRole())
	    	->send();
	}

	public function userLoggedOut()
	{
		$user = new Logtivity_WP_User();

		return (new Logtivity_Logger())
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
			->setAction('Profile Updated')
			->setContext($user->getRole())
			->addMeta('Username', $user->userLogin())
			->send();
	}
}

$Logtivity_User = new Logtivity_User;