<?php

namespace WEMOOF\BackendBundle\Service;

interface UrlShortenerInterface
{
    function shortenRoute($name, array $params = null);
}
