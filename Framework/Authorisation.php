<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        Authorisation.php
 * Location:
 * Project:         XXX-SaaS-Vanilla-MVC-YYYY-SN
 * Date Created:    20/08/2024
 *
 * Author:          Adrian Gould <Adrian.Gould@nmtafe.wa.edu.au>
 *
 */

namespace Framework;

class Authorisation
{
    /**
     * Check if current logged-in user owns a resource
     *
     * @param int $resourceId
     * @return bool
     */
    public static function isOwner(int $resourceId): bool
    {
        $sessionUser = Session::get('user');

        if ($sessionUser !== null && isset($sessionUser['id'])) {
            $sessionUserId = (int)$sessionUser['id'];
            return $sessionUserId === $resourceId;
        }

        return false;
    }

    public static function isAdmin():bool
    {
        $sessionUser = Session::get('user');
        return (int)$sessionUser['id'] === 10 || (int)$sessionUser['id'] === 20;
    }
}