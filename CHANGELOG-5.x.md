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
