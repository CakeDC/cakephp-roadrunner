Roadrunner for CakePHP
===================

[![Build Status](https://secure.travis-ci.org/cakedc/cakephp-roadrunner.png?branch=master)](http://travis-ci.org/cakedc/cakephp-roadrunner)
[![Coverage Status](https://img.shields.io/codecov/c/gh/cakedc/cakephp-roadrunner.svg?style=flat-square)](https://codecov.io/gh/cakedc/cakephp-roadrunner)
[![Downloads](https://poser.pugx.org/cakedc/cakephp-roadrunner/d/total.png)](https://packagist.org/packages/cakedc/cakephp-roadrunner)
[![Latest Version](https://poser.pugx.org/cakedc/cakephp-roadrunner/v/stable.png)](https://packagist.org/packages/cakedc/cakephp-roadrunner)
[![License](https://poser.pugx.org/cakedc/cakephp-roadrunner/license.svg)](https://packagist.org/packages/cakedc/cakephp-roadrunner)


Requirements
------------

* CakePHP 3.4.0+
* PHP 7.1+

Setup
-----

* `composer require cakedc/cakephp-roadrunner`
* Download roadrunner binary and place the file in your filesystem, for example under `/usr/local/bin/rr`
* Create a RoadRunner worker file, or use the example worker provided

```bash
cp vendor/cakedc/cakephp-roadrunner/worker/* .
```

Note the configuration is stored in .rr.json file, check all possible keys here
https://github.com/spiral/roadrunner/wiki/Configuration

* Start the server, either using your own configuration or the sample configuration provided in the plugin

`/usr/local/bin/rr serve`

* If you need sessions
  * Ensure you add the following to your session config in your CakePHP `config/app.php`

```php
    'Session' => [
        'defaults' => 'php',
        'ini' => [
            'session.use_trans_sid' => false,
            'session.use_cookies' => false,
            'session.use_only_cookies' => true,
            'session.cache_limiter' => '',
            'session.save_handler' => 'user',
        ],
    ],
```

  * Add the session middleware to your `src/Application.php` middleware queue

```php
    ->add(new \Relay\Middleware\SessionHeadersHandler())
```

* Nginx proxy

You'll possibly need to configure a webserver to handle requests, serve static assets etc.
Use this sample config virtualhost for nginx:

```
server {
    listen 80;
    server_name example.com; 
    root /var/virtual/example.com/webroot;

    location / {
        try_files $uri @backend8080;
    }

    location @backend8080 {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Host $server_name;
    }
}

```
  
Documentation
-------------

For documentation, as well as tutorials, see the [Docs](Docs/Home.md) directory of this repository.

Support
-------

For bugs and feature requests, please use the [issues](https://github.com/cakedc/cakephp-roadrunner/issues) section of this repository.

Commercial support is also available, [contact us](https://www.cakedc.com/contact) for more information.

Contributing
------------

This repository follows the [CakeDC Plugin Standard](https://www.cakedc.com/plugin-standard). If you'd like to contribute new features, enhancements or bug fixes to the plugin, please read our [Contribution Guidelines](https://www.cakedc.com/contribution-guidelines) for detailed instructions.

License
-------

Copyright 2019 Cake Development Corporation (CakeDC). All rights reserved.

Licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php) License. Redistributions of the source code included in this repository must retain the copyright notice found in each file.

Todo
----
* Existing issue setting cookies like $this->response = $this->response->withHeader('head', 'one'); conflicts with session cookie generation
