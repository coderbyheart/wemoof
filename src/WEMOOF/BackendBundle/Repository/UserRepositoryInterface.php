<?php

namespace WEMOOF\BackendBundle\Repository;

use WEMOOF\BackendBundle\Entity\Event;
use \PhpOption\Option;

interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getUser($id);
}