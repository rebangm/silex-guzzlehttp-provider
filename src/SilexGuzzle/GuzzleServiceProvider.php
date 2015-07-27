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

            $this->getConfiguration($app);
            return new Client($this->config);
        });
    }

    protected function getConfiguration($app){

        if(isset($app['guzzle.base_uri'])){
            $this->config['base_uri'] = $app['guzzle.base_uri'];
        }
        if(isset($app['guzzle.timeout'])){
            $this->config['timeout'] = $app['guzzle.timeout'];
        }
        if(isset($app['guzzle.allow_redirect'])){
            $this->config['allow_redirect'] = $app['guzzle.allow_redirect'];
        }

    }
}
