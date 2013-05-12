<?php

namespace WEMOOF\BackendBundle\Repository;

use WEMOOF\BackendBundle\Entity\Event;
use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Entity\Registration;

interface RegistrationRepositoryInterface
{
    /**
     * @param User $user
     * @return Registration[]
     */
    function getRegistrationsForUser(User $user);

    /**
     * @param Event $event
     * @return Registration[]
     */
    function getRegistrationsForEvent(Event $event);


    /**
     * @return Registration[]
     */
    function getUnconfirmedRegistrations();

    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getRegistration($id);

    /**
     * @return Registration[]
     */
    function getMissingNameRegistrations();
}
