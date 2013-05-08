<?php

namespace WEMOOF\BackendBundle\Repository;

use WEMOOF\BackendBundle\Entity\Event;
use \PhpOption\Option;
use WEMOOF\BackendBundle\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getUser($id);

    /**
     * @return User[]
     */
    function getUsersWithoutPublicProfile();
}