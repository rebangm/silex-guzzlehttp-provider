<?php

namespace SilexGuzzle\Tests;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;

use SilexGuzzle\GuzzleServiceProvider;

class GuzzleServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('GuzzleHttp\\Client')) {
            $this->markTestSkipped('Guzzle was not installed.');
        }
    }
    
    public function testRegister()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider());
            
        $app->get('/', function() use($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        
        $this->assertInstanceOf('GuzzleHttp\Client', $app['guzzle']);
    }

    /**
     * test to connecto httpBin
     */
    public function testConnectToApi()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider());

        $app->get('/', function() use($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $myRequest = $app['guzzle']->get('http://httpbin.org/');
        $this->assertSame($myRequest->getStatusCode(), 200);
    }


    /**
     * @Exception GuzzleHttp\Exception\ConnectException
     */
    public function testTimeoutApi()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider());

        $app->get('/', function() use($app) {
            $app['guzzle'];
        });
        $request = Request::create('/');
        $app->handle($request);
        $app['guzzle']->get('http://httpbin.org/delay/5');
    }


    public function testSetTimeout()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider(), array(
                'guzzle.timeout' => 3.14,
            ));

        $app->get('/', function() use($app) {
                $app['guzzle'];
            });
        $request = Request::create('/');
        $app->handle($request);
        $config = $app['guzzle']->getConfig();
        $this->assertArrayHasKey('timeout',$config);
        $this->assertSame($config['timeout'], 3.14);
        $this->assertEquals($config['timeout'], 3.14);
    }

    public function testSetBaseUri()
    {
        $app = new Application();
        $app->register(new GuzzleServiceProvider(), array(
                'guzzle.base_uri' => 'http://httpbin.org/',
            ));

        $app->get('/', function() use($app) {
                $app['guzzle'];
            });
        $request = Request::create('/');
        $app->handle($request);
        $config = $app['guzzle']->getConfig();
        $this->assertArrayHasKey('base_uri',$config);
        $this->assertEquals($config['base_uri'], 'http://httpbin.org/');
        $myRequest = $app['guzzle']->get('/ip');
        $this->assertObjectHasAttribute('origin', json_decode($myRequest->getBody()->getContents()));

    }
}