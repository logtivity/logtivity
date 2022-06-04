<?php

class Logtivity_Wp_User
{
	protected $user;

	public function __construct($user = null, $field = 'ID')
	{
		if (is_null($user)) 
		{
			$this->user = wp_get_current_user();
		}
		elseif ($user instanceof WP_User) 
		{
			$this->user = $user;
		} 
		else 
		{
			$this->user = get_user_by($field, $user);
		}
	}

	public function findByUserMeta($field, $value)
	{
		$users = get_users([
			'meta_key' => $field, 
			'meta_value' => $value
		]);

		if (count($users)) 
		{
			$this->user = $users[0];
		}
		else
		{
			$this->user = false;
		}

		return $this;
	}

	public function id()
	{
		if ($this->user->ID == 0) {
			return;
		}

		return $this->user->ID;
	}

	public function userLogin()
	{
		if ($this->user->ID == 0) {
			return;
		}

		return $this->user->user_login;
	}

	public function email()
	{
		return $this->user->user_email;
	}

	public function name()
	{
		return $this->firstName() . ' ' . $this->lastName();
	}

	public function firstName()
	{
		return $this->meta('first_name');
	}

	public function lastName()
	{
		return $this->meta('last_name');
	}

	public function displayName()
	{
		return $this->user->display_name;
	}

	public function niceName()
	{
		return $this->user->user_nicename;
	}

	public function profileLink()
	{
		return add_query_arg( 'user_id', $this->id(), self_admin_url( 'user-edit.php' ) );
	}

	/**
	 * Get user meta
	 * 
	 * @param  string  $meta_key
	 * @param  boolean $returnString
	 * @return string/null
	 */
	public function meta($meta_key, $returnString = true)
	{
		$meta = get_user_meta($this->user->ID, $meta_key, $returnString);

		if ($meta != '') {
			return $meta;
		}

		return null;
	}

	/**
	 * Does the fetched user exist
	 * 
	 * @return boolean
	 */
	public function exists()
	{
		return $this->user;
	}

	/**
	 * Is the user logged in
	 * 
	 * @return boolean
	 */
	public function isLoggedIn()
	{
		if ($this->user->ID == 0) {
			return false;
		}

		return true;
	}

	/**
	 * Handles one user role. Useful for determining in more detail difference between employer and 
	 * employer_premium for instance.
	 * 
	 * @param  string  $role 
	 * @return boolean
	 */
	public function hasRole($role)
	{
		if ( in_array($role, $this->getRoles()) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the users roles
	 * 
	 * @return array
	 */
	public function getRoles()
	{
		return $this->user->roles;
	}

	/**	
	 * Get the users first role
	 * 
	 * @return string|false
	 */
	public function getRole()
	{
		foreach ($this->getRoles() as $role) {
			return $role;
		}
	}

}
