5.2.1
-----

* RecaptchaV3 - Don't catch exception
* Test against PHP 8.4 & Symfony 7.1 + 7.2

5.2.0
-----

* Add error codes and score to RecaptchaV3 constraint

5.1.2
-----

* Fix Slug constraint by not allowing dashes everywhere

5.1.1
-----

* Mark paginator_theme & datalist_theme nodes as `#[YieldReady]`

5.1.0
-----

* Allow doctrine/orm v3

5.0.0
-----

* Drop support for PHP < 8.2
* Add support for Symfony 7
* Drop support for Symfony 5.4 & Symfony < 6.4
* Upgrade dev dependencies
* Add support for endroid/qr-code v5
* Removed YAML routing files, import PHP routing files instead
* The path to the routing files have changed, see [UPGRADE-5.x.md](UPGRADE-5.x.md)
* Following the services rewritten to PHP, all services are now defined using class FQCN instead of named services
