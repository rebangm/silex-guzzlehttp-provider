Silex-Guzzle
================

[![Latest Stable Version](https://poser.pugx.org/rebangm/silex-guzzlehttp-provider/v/stable)](https://packagist.org/packages/rebangm/silex-guzzlehttp-provider)
[![Build Status](https://api.travis-ci.org/rebangm/silex-guzzlehttp-provider.png?branch=master)](https://travis-ci.org/rebangm/silex-guzzlehttp-provider)
[![codecov.io](http://codecov.io/github/rebangm/silex-guzzlehttp-provider/coverage.svg?branch=master)](http://codecov.io/github/rebangm/silex-guzzlehttp-provider?branch=master)

Installation
------------

Create a composer.json in your projects root-directory::

    {
        "require": {
            "rebangm/silex-guzzlehttp-provider": "*"
        }
    }

and run::

    curl -s http://getcomposer.org/installer | php
    php composer.phar install


Getting started
----------------

Supposing that the skeleton of your application is ready, you simply need to register this service provider by specifying the parameters and options needed to access Guzzle:

This will register one instance of Guzzle\Client accessible from anywhere in your application by using $app['guzzle']. all parameters are optional and they accept the same values accepted by the constructor of Guzzle\Client (see the documentation of Guzzle).

```php
    $app->register(new SilexGuzzle\GuzzleServiceProvider(),array(
        'guzzle.base_uri' => "http://httpbin.com/",
        'guzzle.timeout' => 3.14,
        'guzzle.request_options' =>
            ['auth' => ['admin', 'admin']]
    ));
```


You can find more details on how to use this provider in the examples directory or the test suite.

Reporting bugs and contributing code

Contributions are highly appreciated either in the form of pull requests for new features, bug fixes or just bug reports. We only ask you to adhere to a basic [basic set of rules](CONTRIBUTING.md) before submitting your changes or filing bugs on the issue tracker to make it easier for everyone to stay consistent while working on the project.

### Project links ###

- [Source code](http://github.com/rebangm/silex-guzzlehttp-provider)
- [Issue tracker](http://github.com/rebangm/silex-guzzlehttp-provider/issues)


### Author ###

- [Jean-Philippe Dépigny](mailto:jp.depigny@gmail.com)
  ([github](http://github.com/rebangm))
  ([twitter](https://twitter.com/rhadamanthiss))

License
-------

'silex-guzzlehttp-provider' is licensed under the MIT license. [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/rebangm/silex-guzzlehttp-provider/master/LICENSE.md)
