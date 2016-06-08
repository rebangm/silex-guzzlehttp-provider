<?php
/*
 * This file is part of the Silex Guzzle Provider.
 *
 * (c) Jean-Philippe Dépigny <jp.depigny@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once "../vendor/autoload.php";

$app = new Silex\Application();
$app['debug'] = true;
$app['guzzle.timeout'] = 1.0;
$app->register(new SilexGuzzle\GuzzleServiceProvider(), array(
    'guzzle.timeout' => 3.14,
    'guzzle.request_options' =>
        ['auth' => ['admin', 'admin']]
));


$app->get('/ip', function () use ($app) {

    $request = $app['guzzle']->get('http://httpbin.org/ip');
    return 'HttpBin API : "Status Code" "200" "' . $request->getStatusCode() . '"  ' . $request->getBody()->getContents();
});

$app->get('/', function () use ($app) {

    return <<<EOD
/ip  => Get Ip <br/>
/get => send get?q=foo <br/>
/auth => authenticate with admin:admin <br/>
/testTimeout => Get with 5 sec delay <br/>
/post => post request <br/>
EOD;
});

$app->get('/get', function () use ($app) {

    $request = $app['guzzle']->get('http://httpbin.org/get?q=foo');
    return 'HttpBin API : "Status Code" "200" "' . $request->getStatusCode() . '"  ' . $request->getBody()->getContents();
});

$app->get('/auth', function () use ($app) {

    $request = $app['guzzle']->get('http://httpbin.org/basic-auth/admin/admin');
    return 'HttpBin API : "Status Code" "200" "' . $request->getStatusCode() . '"  ' . $request->getBody()->getContents();
});


$app->get('/testTimeout', function () use ($app) {
    try {
        $request = $app['guzzle']->get('http://httpbin.org/delay/5');
        return 'HttpBin API : "Timeout" "200" "' . $request->getStatusCode() . '"';
    } catch (\exception $e) {
        return 'HttpBin API : "Timeout" at ' . $e->getMessage();
    }
});

$app->get('/post', function () use ($app) {
    try {
        $request = $app['guzzle']->request('POST', 'http://httpbin.org/post', ['form_params' => ['field_name' => 'value']]);

        return 'HttpBin API : Post "200" "' . $request->getStatusCode() . '" '. 'send Post data with form_params';
    }catch(\Exception $e){
        return $e;
    }

});


$app->run();