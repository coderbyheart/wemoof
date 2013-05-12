<?php

namespace WEMOOF\BackendBundle\Service;


use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Cache\Cache;

class UrlShortener implements UrlShortenerInterface
{
    /**
     * @var string
     */
    private $googleApiKey;

    /**
     * @var string
     */
    private $schemeAndHost;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $googlCache;

    public function __construct(
        RouterInterface $router,
        Cache $googlCache,
        $schemeAndHost,
        $googleApiKey
    )
    {
        $this->router = $router;
        $this->googlCache = $googlCache;
        $this->schemeAndHost = $schemeAndHost;
        $this->googleApiKey = $googleApiKey;
    }

    public function shortenRoute($route, array $params = null)
    {
        $url = $this->schemeAndHost . $this->router->generate($route, $params);
        $key = md5($url);
        if (!$this->googlCache->contains($key)) {
            $postdata = json_encode(
                array(
                    'longUrl' => $url,
                )
            );

            $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => $postdata
            )
            );

            $context = stream_context_create($opts);
            $result = file_get_contents('https://www.googleapis.com/urlshortener/v1/url?key=' . $this->googleApiKey, false, $context);
            $data = json_decode($result);
            $this->googlCache->save($key, $data->id);
        }
        return $this->googlCache->fetch($key);
    }
}
