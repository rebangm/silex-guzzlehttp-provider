<?php

require_once "../vendor/autoload.php";

$app = new Silex\Application();
$app['debug'] = true;
$app['guzzle.timeout'] = 1.0;
$app->register(new SilexGuzzle\GuzzleServiceProvider());


$app->get('/', function() use($app) {

    $request = $app['guzzle']->get('http://httpbin.org/ip');
    return 'HttpBin API : "Status Code" "200" "' . $request->getStatusCode() . '"  '. $request->getBody()->getContents();
});

$app->get('/testTimeout', function() use($app) {
    try {
        $request = $app['guzzle']->get('http://httpbin.org/delay/5');
        return 'HttpBin API : "Timeout" "200" "' . $request->getStatusCode() . '"';
    }catch(\exception $e){
        return 'HttpBin API : "Timeout" at '.$e->getMessage();
    }
});


$app->run();