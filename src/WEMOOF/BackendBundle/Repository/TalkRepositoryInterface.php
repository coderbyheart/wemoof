<?php

namespace WEMOOF\BackendBundle\Repository;

use WEMOOF\BackendBundle\Entity\Event;
use \PhpOption\Option;
use WEMOOF\BackendBundle\Entity\User;

interface TalkRepositoryInterface
{
    /**
     * @param Event $event
     * @return \PhpOption\Option
     */
    function getTalksForEvent(Event $event);

    /**
     * @param User $user
     * @return \PhpOption\Option
     */
    function getTalksForUser(User $user);

    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getTalk($id);
}