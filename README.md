Leapt Core Bundle
=================

[![Package version](https://img.shields.io/packagist/v/leapt/core-bundle.svg?style=flat-square)](https://packagist.org/packages/leapt/core-bundle)
[![Build Status](https://img.shields.io/github/workflow/status/leapt/core-bundle/Continuous%20Integration/4.x?style=flat-square)](https://github.com/leapt/core-bundle/actions?query=workflow%3A%22Continuous+Integration%22)
[![PHP Version](https://img.shields.io/packagist/php-v/leapt/core-bundle.svg?branch=4.x&style=flat-square)](https://travis-ci.org/leapt/core-bundle?branch=4.x)
[![License](https://img.shields.io/badge/license-MIT-red.svg?style=flat-square)](LICENSE)
[![Code coverage](https://img.shields.io/codecov/c/github/leapt/core-bundle?style=flat-square)](https://codecov.io/gh/leapt/core-bundle/branch/4.x)

The current version (4.x) of the bundle works with Symfony 5.4 & Symfony 6.0+.

For older versions of Symfony:

* Use version 3.x for Symfony between 4.4 and 5.4
* Use version 2.x for Symfony between 3.3 and 4.4
* Use version < 2.x for Symfony < 3.3

You can check the [changelog](CHANGELOG-4.x.md) for version 4 and the [upgrade document](UPGRADE-4.x.md) when upgrading
from 3.x bundle version.

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
composer phpstan # Static analysis
vendor/bin/simple-phpunit # Run tests
```

Docs are built using mkdocs. To launch the docs server locally, run `make docs-start` & open http://127.0.0.1:8000/.

History
-------

This bundle is a maintained fork of the SnowcapCore Bundle: https://github.com/snowcap/SnowcapCoreBundle
