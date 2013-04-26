<?php

namespace WEMOOF\BackendBundle\Repository;

use \PhpOption\Option;

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
     * @return \PhpOption\Option
     */
    function getRegisterableEvents();
}