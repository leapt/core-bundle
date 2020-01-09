Leapt Core Bundle
=================

[![Package version](https://img.shields.io/packagist/v/leapt/core-bundle.svg?style=flat-square)](https://packagist.org/packages/leapt/core-bundle)
[![Build Status](https://img.shields.io/travis/leapt/core-bundle.svg?branch=master&style=flat-square)](https://travis-ci.org/leapt/core-bundle?branch=master)
[![PHP Version](https://img.shields.io/packagist/php-v/leapt/core-bundle.svg?branch=master&style=flat-square)](https://travis-ci.org/leapt/core-bundle?branch=master)
[![License](https://img.shields.io/badge/license-MIT-red.svg?style=flat-square)](LICENSE)
[![Code coverage](https://img.shields.io/coveralls/github/leapt/core-bundle.svg?style=flat-square)](LICENSE)

The current version (3.x) of the bundle works with Symfony 4.4 & Symfony 5.

For older versions of Symfony:

* Use version 2.x for Symfony between 3.3 and 4.4
* Use version < 2.x for Symfony < 3.3

You can check the [changelog](CHANGELOG-3.x.md) for version 3 and the [upgrade document](UPGRADE-3.x.md) when upgrading
from 2.x bundle version.

It aims to help with some repetitive tasks, including (but not limited to):

* Dealing with file and image uploads
* RSS feed generation
* SEO-related tasks (sitemaps, etc)
* Building searchable & filterable data lists

Installation & usage
--------------------

You can check docs there: https://core-bundle.leapt.io/

Contributing
------------

Feel free to contribute, like sending [pull requests](https://github.com/leapt/core-bundle/pulls) to add features/tests
or [creating issues](https://github.com/leapt/core-bundle/issues) :)

Note there are a few helpers to maintain code quality, that you can run using these commands:

```bash
composer cs:dry # Code style check
vendor/bin/simple-phpunit # Run tests
```

History
-------

This bundle is a maintained fork of the SnowcapCore Bundle: https://github.com/snowcap/SnowcapCoreBundle
