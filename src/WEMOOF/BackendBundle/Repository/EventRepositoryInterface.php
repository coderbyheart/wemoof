<?php

namespace WEMOOF\BackendBundle\Repository;

use \PhpOption\Option;
use WEMOOF\BackendBundle\Entity\Event;

interface EventRepositoryInterface
{
    /**
     * @return \PhpOption\Option
     */
    function getNextEvent();

    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getEvent($id);

    /**
     * @return Event[]
     */
    function getRegisterableEvents();

    /**
     * @return Event[]
     */
    function getPastEvents();
}