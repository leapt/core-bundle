[![Build Status](https://secure.travis-ci.org/snowcap/SnowcapCoreBundle.png?branch=master)](http://travis-ci.org/snowcap/SnowcapCoreBundle)

Snowcap Core Bundle
==================================

The Snowcap Core Bundle is a bundle used at Snowcap to help us with some repetitive tasks, including (but not limited to):

* Dealing with file and image uploads
* RSS feed generation
* SEO-related tasks (sitemaps, etc)

## Prerequisites

This version of the bundle requires Symfony 2.1+. If you are using Symfony
2.0.x, please use the 2.0.x releases of the bundle.

## Installation

### Download SnowcapCoreBundle using composer

Add SnowcapCoreBundle in your composer.json:

```js
{
    "require": {
        "snowcap/core-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update snowcap/core-bundle
```

Composer will install the bundle to your project's `vendor/snowcap` directory.

### Enable the Bundle


Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Snowcap\CoreBundle\SnowcapCoreBundle(),
    );
}
```

### Running the tests

Before running the tests, you will need to install the bundle dependencies. Do it using composer :

``` bash
$ php composer.phar --dev install
```

Then you can simply launch phpunit

``` bash
$ phpunit
```