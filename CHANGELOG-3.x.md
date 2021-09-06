3.3.2
-----

* RecaptchaV3Validator: make secretKey nullable (thanks to @Ikimoo)
* Fix errors raised by phpstan

3.3.1
-----

* Render RecaptchaV3 errors

3.3.0
-----

* Add RecaptchaV3 type & validator
* Allow setting Recaptcha API URL

3.2.1
-----

* Fix Symfony 5.3 deprecation about calling RequestStack->getMasterRequest()

3.2.0
-----

* Add Gravatar Twig filter
* Translate validator errors to French

3.1.2
-----

* Fix slug constraint

3.1.1
-----

* Validator constraints are available as PHP 8 attributes

3.1.0
-----

* Drop support for PHP < 7.4
* File annotation is available as PHP 8 attribute

3.0.2
-----

* Add support for PHP 8

3.0.1
-----

* Initialize Datalist search when grabbing Paginator (allows getting total count)

3.0.0
-----

* Fixed compatibility with Symfony 5
* Dropped compatibility with Symfony < 4.4
* Dropped support for PHP < 7.2
* Added php-cs-fixer & phpstan
