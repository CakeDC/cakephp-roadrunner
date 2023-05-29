# Roadrunner for CakePHP

[RoadRunner](https://roadrunner.dev/) is a high-performance PHP application server, load-balancer, and process 
manager written in Golang. Using Roadrunner you can replace php-fpm a long with nginx or apache.

## Requirements

* CakePHP ^4.4
* PHP ^8.1
* Roadrunner ^2023.1.4

## Table of Contents
 
- [Install](#install)
- [Sessions](#sessions)
- [Static Assets](#static-assets)

## Install

Install via composer:

```console
composer require cakedc/cakephp-roadrunner
```

Unlike most CakePHP plugins you won't be needing to load the plugin in your `src/Application.php`.

### Installing Roadrunner

Roadrunner ships as a single go binary. Download the Roadrunner binary from the 
[release page](https://github.com/roadrunner-server/roadrunner/releases) and copy the file to your 
filesystem, for example under `/usr/local/bin/rr` or `/usr/bin/rr`.

If your project uses Docker you can easily add the binary to your Dockerfile:

```dockerfile
FROM spiralscout/roadrunner:2.12 as roadrunner
COPY --from=roadrunner /usr/bin/rr /usr/bin/rr
```

Be sure to check the Roadrunner documentation for up-to-date [docker images](https://roadrunner.dev/docs/docker-images).

### Configuring Roadrunner

In a typical PHP application your webserver forwards `*.php` requests to php-fpm, which in turn calls the CakePHP
front controller `webroot/index.php`. With Roadrunner, the worker file gets called by active workers to handle
incoming requests to your application. 

Create a Roadrunner worker file, or use the [example worker](worker/cakephp-worker.php) provided:

```console
cp vendor/cakedc/cakephp-roadrunner/worker/cakephp-worker.php .
```

Next we need to instruct Roadrunner to use our worker a long with a few other configs. Create a 
[Roadrunner config](https://roadrunner.dev/docs/intro-config) file, or use the [example config](worker/rr.yaml) 
provided:

```console
cp vendor/cakedc/cakephp-roadrunner/worker/rr.yaml .
```

Start the server:

```console
/usr/local/bin/rr serve -d -c rr.yaml
```

You should now be able to browse to http://localhost:8080

## Sessions

If you need sessions ensure you add the following to your session config in your CakePHP `config/app.php`

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

## Static Assets

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

Roadrunner also supports [serving static assets](https://roadrunner.dev/docs/http-static) natively. Check the 
[worker/rr.yaml](worker/rr.yaml) file that ships with this project for an example.
