<?php
namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Security\UserService\UserServiceInterface;
use Mouf\Security\UserService\UserInterface;

/**
 * This class is an implementation of Mouf's UserService specially tailored to use Wordpress login system instead. 
 */
class MoufpressUserService implements UserServiceInterface {
	/**
	 * Logs the user using the provided login and password.
	 * Returns true on success, false if the user or password is incorrect.
	 *
	 * @param string $user
	 * @param string $password
	 * @return boolean.
	 */
	public function login($user, $password) {
		$creds = array();
		$creds['user_login'] = $user;
		$creds['user_password'] = $password;
		$creds['remember'] = true;
		$user = wp_signon( $creds, false );
		if ( is_wp_error($user) ) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Logs the user using the provided login.
	 * The password is not needed if you use this function.
	 * Of course, you should use this functions sparingly.
	 * For instance, it can be useful if you want an administrator to "become" another
	 * user without requiring the administrator to provide the password.
	 *
	 * @param string $login
	*/
	public function loginWithoutPassword($login) {
		$user = get_user_by('login', $username );
		
		if ( !is_wp_error( $user ) || $user == null ) {
			throw new MoufpressException("Unable to find user whose login is ".$login);
		}
		wp_clear_auth_cookie();
		wp_set_current_user ( $user->ID );
		wp_set_auth_cookie  ( $user->ID );	
	}
	
	/**
	 * Logs a user using a token. The token should be discarded as soon as it
	 * was used.
	 *
	 * @param string $token
	*/
	public function loginViaToken($token) {
		global $wpdb, $wp_hasher;
		
		$key = preg_replace('/[^a-z0-9]/i', '', $token);
		
		if ( empty( $key ) || !is_string( $key ) ) {
			throw new MoufpressException('Invalid token: '.$token);
		}
		
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT user_login FROM $wpdb->users WHERE user_activation_key = %s", $token ) );
		if ( ! $row ) {
			throw new MoufpressException('Invalid token: '.$token);
		}
		$this->loginWithoutPassword($row->user_login);
	}
	
	/**
	 * Returns "true" if the user is logged, "false" otherwise.
	 *
	 * @return boolean
	*/
	public function isLogged() {
		return is_user_logged_in();
	}
	
	/**
	 * Redirects the user to the login page if he is not logged.
	 *
	 * @return boolean
	*/
	public function redirectNotLogged() {
		if (!$this->isLogged()) {
			wp_redirect(wp_login_url( $_SERVER['REQUEST_URI'] ));
		}
	}
	
	/**
	 * Logs the user off.
	 *
	*/
	public function logoff() {
		wp_logout();
	}
	
	/**
	 * Returns the current user ID.
	 *
	 * @return string
	*/
	public function getUserId() {
		return get_current_user_id();
	}
	
	/**
	 * Returns the current user login.
	 *
	 * @return string
	*/
	public function getUserLogin() {
		$user = wp_get_current_user();
		if ($user == null) {
			return null;
		}
		return $user->user_login;
	}
	
	/**
	 * 
	 * @var UserInterface
	 */
	private $loggedUser;
	
	/**
	 * Returns the user that is logged (or null if no user is logged).
	 *
	 * return UserInterface
	*/
	public function getLoggedUser() {
		// Mini cache mechanism to avoid serving a new object each time the function is called.
		if ($this->loggedUser) {
			if ($this->getUserId() == $this->loggedUser->getId()) {
				return $this->loggedUser;
			}
		}
		$this->loggedUser = new UserWrapper(wp_get_current_user());
		return $this->loggedUser;
	}
}
