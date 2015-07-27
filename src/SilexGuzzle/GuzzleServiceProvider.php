<?php


namespace SilexGuzzle;

use Silex\Application;
use Silex\ServiceProviderInterface;
use GuzzleHttp\Client;

class GuzzleServiceProvider implements ServiceProviderInterface
{
    private $configuration = array();

    public function boot(Application $app)
    {

    }

    public function register(Application $app)
    {
        $app['guzzle'] = $app->share(function () use ($app) {

            $this->setConfiguration($app);
            return new Client($this->configuration);
        });
    }

    protected function setConfiguration($app){

        if(isset($app['guzzle.base_uri'])){
            $this->configuration['base_uri'] = $app['guzzle.base_uri'];
        }
        if(isset($app['guzzle.timeout'])){
            $this->configuration['timeout'] = $app['guzzle.timeout'];
        }
        if(isset($app['guzzle.allow_redirect'])){
            $this->configuration['allow_redirect'] = $app['guzzle.allow_redirect'];
        }

    }
}
