<?php
/*
 * This file is part of the Silex Guzzle Provider.
 *
 * (c) Jean-Philippe Dépigny <jp.depigny@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SilexGuzzle;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use GuzzleHttp\Client;

/**
 * Class GuzzleServiceProvider
 * @author Jean-Philippe Dépigny <jp.depigny@gmail.com>
 */
class GuzzleServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    private $configuration = array();

    /**
     * {@inheritdoc}
     */

    public function boot(Container $app)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['guzzle'] = function($app) {

            $this->setConfiguration($app);
            return new Client($this->configuration);
        };
    }

    /** method to catch configuration params throw by $app['guzzle.*]
     * @param $app
     */
    protected function setConfiguration($app)
    {

        if (isset($app['guzzle.base_uri'])) {
            $this->configuration['base_uri'] = $app['guzzle.base_uri'];
        }
        if (isset($app['guzzle.timeout'])) {
            $this->configuration['timeout'] = $app['guzzle.timeout'];
        }
        if (isset($app['guzzle.debug'])) {
            $this->configuration['debug'] = $app['guzzle.debug'];
        }
        if (isset($app['guzzle.request_options']) && is_array($app['guzzle.request_options'])) {
            foreach ($app['guzzle.request_options'] as $valueName => $value) {
                $this->configuration[$valueName] = $value;
            }
        }
    }
}
