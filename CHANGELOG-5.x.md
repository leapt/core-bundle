5.4.0
-----

* QR code generation: Add compatibility with endroid/qr-code v6

5.3.2
-----

* Drop passing tag to Node constructor to fix Twig 3.12 deprecation

5.3.1
-----

* Drop usage of spaceless filter in Twig templates to fix Twig 3.12 deprecation

5.3.0
-----

* RecaptchaV3 - Don't catch exception
* Test against PHP 8.4 & Symfony 7.1 + 7.2
* Deprecate the PasswordStrength constraint

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
