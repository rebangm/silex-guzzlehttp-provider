<?php


namespace SilexGuzzle;

use Silex\Application;
use Silex\ServiceProviderInterface;
use GuzzleHttp\Client;

class GuzzleServiceProvider implements ServiceProviderInterface
{
    private $config = array();

    public function boot(Application $app)
    {

    }

    public function register(Application $app)
    {
        $app['guzzle'] = $app->share(function () use ($app) {

            $config = array();
            return new Client(['connect_timeout' => 1,'timeout' => 2]);
        });
    }

    protected function getConfiguration($app){
        $app['guzzle.base_uri'];
        $app['guzzle.timeout'];
        $app['guzzle.connect_timeout'];
        $this->config;
    }
}
