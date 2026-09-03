6.2.0
-----

* Added a `time_ago` option to `DateTimeFieldType` to display the date using the `time_ago` Twig filter

6.1.0
-----

* Added a Messenger helper, Twig extension & worker heartbeat listener to check whether a `messenger:consume` worker
  is currently running for a given transport (requires `symfony/messenger`)

6.0.1
-----

* Fix typo in French translation
* Test against Symfony 8.2

6.0.0
-----

* Dropped support for PHP < 8.4
* Dropped support for Symfony 6.4 & Symfony < 7.4
* Upgraded dev dependencies
* Removed the PasswordStrength constraint, use the one from Symfony instead
* Removed PasswordStrengthChecker
* Dropped support for doctrine/orm v2
* Required Twig >= 3.23
* Removed support for endroid/qr-code < 6
