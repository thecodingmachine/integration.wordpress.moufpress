<?php

namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Security\RightsService\RightsServiceInterface;

/**
 * This class is an implementation of Mouf's RightService specially tailored to use Wordpress capabilities in place
 * of Mouf rights.
 */
class MoufpressRightService implements RightsServiceInterface
{
    /**
     * Returns true if the current user has the right passed in parameter.
     * A scope can be optionnally passed.
     * A scope can be anything from a string to an object. If it is an object,
     * it must be serializable (because it will be stored in the session).
     *
     * @param string $right
     * @param mixed  $scope
     */
    public function isAllowed($right, $scope = null)
    {
        if ($scope) {
            throw new MoufpressException("The 'scope' feature is not supported in Moufpress implementation of the right service.");
        }

        return current_user_can($right);
    }

    /**
     * Returns true if the user whose id is $user_id has the $right.
     * A scope can be optionnally passed.
     * A scope can be anything from a string to an object. If it is an object,
     * it must be serializable (because it will be stored in the session).
     *
     * @param string $user_id
     * @param string $right
     * @param mixed  $scope
     */
    public function isUserAllowed($user_id, $right, $scope = null)
    {
        if ($scope) {
            throw new MoufpressException("The 'scope' feature is not supported in Moufpress implementation of the right service.");
        }

        return user_can($user_id, $right);
    }

    /**
     * Rights are cached in session, this function will purge the rights in session.
     * This can be useful if you know the rights previously fetched for
     * the current user will change.
     */
    public function flushRightsCache()
    {
        // Nothing to do in wordpress.
    }

    /**
     * If the user has not the requested right, this function will
     * redirect the user to an error page (or a login page...).
     *
     * @param string $right
     * @param mixed  $scope
     */
    public function redirectNotAuthorized($right, $scope = null)
    {
        if (!$this->isAllowed($right)) {
            $message = apply_filters('wp_router_access_denied_message', __('You are not authorized to access this page', 'moufpress'));
            $title = apply_filters('wp_router_access_denied_title', __('Access Denied', 'moufpress'));
            $args = apply_filters('wp_router_access_denied_args', array('response' => 403));
            wp_die($message, $title, $args);
            exit();
        }
    }
}
