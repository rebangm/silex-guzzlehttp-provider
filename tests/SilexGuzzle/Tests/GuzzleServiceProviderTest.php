<?php

/*
 * This file is part of the Silex Guzzle Provider.
 *
 * (c) Jean-Philippe Dépigny <jp.depigny@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace SilexGuzzle\Tests;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;

use SilexGuzzle\GuzzleServiceProvider;

/**
 * @author Jean-Philippe Dépigny <jp.depigny@gmail.com>
 */
class GuzzleServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * setUp to launch test
     */
    public function setUp()
    {
        if (!class_exists('GuzzleHttp\\Client')) {
            $this->markTestSkipped('Guzzle was not installed.');
        }
    }

    /**
     * Test to register guzzle
     */
    public function testRegister()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider());

        $app->get('/', function () use ($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);

        $this->assertInstanceOf('GuzzleHttp\Client', $app['guzzle']);
    }

    /**
     * test to connect to httpBin
     */
    public function testConnectToApi()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider());

        $app->get('/', function () use ($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $myRequest = $app['guzzle']->get('http://httpbin.org/');
        $this->assertSame($myRequest->getStatusCode(), 200);
    }


    /**
     * test connectException
     * @Exception GuzzleHttp\Exception\ConnectException
     */
    public function testTimeoutApi()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider());

        $app->get('/', function () use ($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $app['guzzle']->get('http://httpbin.org/delay/5');
    }

    /**
     * test params guzzle.timeout
     */
    public function testSetTimeout()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider(), array(
            'guzzle.timeout' => 3.14,
        ));

        $app->get('/', function () use ($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $config = $app['guzzle']->getConfig();
        $this->assertArrayHasKey('timeout', $config);
        $this->assertSame($config['timeout'], 3.14);
        $this->assertEquals($config['timeout'], 3.14);
    }

    /**
     * test params guzzle.base_uri
     */
    public function testSetBaseUri()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider(), array(
            'guzzle.base_uri' => 'http://httpbin.org/',
        ));

        $app->get('/', function () use ($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $config = $app['guzzle']->getConfig();
        $this->assertArrayHasKey('base_uri', $config);
        $this->assertEquals($config['base_uri'], 'http://httpbin.org/');
        $myRequest = $app['guzzle']->get('/ip');
        $this->assertObjectHasAttribute('origin', json_decode($myRequest->getBody()->getContents()));

    }

    /**
     * test params guzzle.request_options
     */
    public function testSetRequestOptionsForAuth()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider(), array(
            'guzzle.base_uri' => 'http://httpbin.org/',
            'guzzle.request_options' => ['auth' => ['admin', 'password']]
        ));

        $app->get('/', function () use ($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $myRequest = $app['guzzle']->get('/basic-auth/admin/password');
        $response = json_decode($myRequest->getBody()->getContents());

        $this->assertObjectHasAttribute('authenticated', $response);
        $this->assertTrue($response->authenticated);
        $this->assertObjectHasAttribute('user', $response);
        $this->assertSame('admin', $response->user);
    }

    /**
     * test params guzzle.debug
     */
    public function testSetRequestOptionsForDebug()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider(), array(
            'guzzle.base_uri' => 'http://httpbin.org/',
            'guzzle.debug' => true
        ));

        $app->get('/', function () use ($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $config = $app['guzzle']->getConfig();
        $this->assertArrayHasKey('debug', $config);
        $this->assertTrue($config['debug']);
    }
}