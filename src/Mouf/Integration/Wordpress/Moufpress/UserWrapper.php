<?php

namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Security\UserService\UserInterface;

/**
 * An adapter wrapping the WP_User and providing the UserInterface interface.
 */
class UserWrapper implements UserInterface
{
    private $wpUser;

    public function __construct(\WP_User $wpUser)
    {
        $this->wpUser = $wpUser;
    }

    /**
     * Returns the ID for the current user.
     *
     * @return string
     */
    public function getId()
    {
        return $this->wpUser->ID;
    }

    /**
     * Returns the login for the current user.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->wpUser->user_login;
    }
}
