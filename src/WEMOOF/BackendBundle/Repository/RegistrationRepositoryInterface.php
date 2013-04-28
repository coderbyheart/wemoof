<?php

namespace WEMOOF\BackendBundle\Repository;

use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Entity\Registration;

interface RegistrationRepositoryInterface
{
    /**
     * @param User $user
     * @return Registration[]
     */
    function getRegistrations(User $user);

    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getRegistration($id);
}
