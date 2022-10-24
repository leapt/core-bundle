4.6.0 (unreleased)
------------------

* Fix docs about datalist filters
* Fix Coding standards & Static analysis workflows
* Run CI against PHP 8.2
* Twig time_ago filter now supports DateTimeImmutable objects
* Add boolean filter type for datalists
* Add tests for choice & search filter type
* Add a multiple option for ChoiceFilterType
* Add tests for feeds
* Add tests for sitemaps

4.5.2
-----

* Fix services loading when EA is not installed

4.5.1
-----

* FileConfigurator: Fix calling getAssetUrl() on null $fileUrl

4.5.0
-----

* Add FileField & ImageField for EasyAdmin bundle

4.4.1
-----

* Remove code that should have been long time ago

4.4.0
-----

* Improve & fix exported files in .gitattributes
* Add `get_qr_code_from_string()` Twig function

4.3.0
-----

* Add PHP routing config
* Rewrite services config with PHP

4.2.1
-----

* Move doctrine/persistence to require-dev & allow v3

4.2.0
-----

* Add Bootstrap 3, 4 & 5 datalist layouts

4.1.0
-----

* Allow file uploads using Flysystem (via league/flysystem-bundle)
* Add `allow_download` option to FileType
* Add Bootstrap 5 layout for Paginator
* SoundType & VideoType: allow configuring player width & height
* SoundType & VideoType: provide constants for providers
* Improve default form layout for file/image uploads, sound & video types
* Add Bootstrap 3, 4 & 5 form layouts

4.0.4
-----

* Fix issue with strict types & mb_strpos()

4.0.3
-----

* Fix file uploads

4.0.2
-----

* Fix expression not initialized in datasource

4.0.1
-----

* Fix RecaptchaType parameter types

4.0.0
-----

* Drop support for PHP < 8.0
* Add support for Symfony 6
* Drop support for Symfony 4 & Symfony < 5.4
* Annotations are now PHP 8 attributes only
* Strict types & added types everywhere
