6.5.0
-----

* Deprecated the Google Analytics integration (`analytics_tracking_code` & `analytics_tracking_commerce` Twig
  functions, `leapt_core.google_analytics` configuration), as Universal Analytics (`ga.js`) has been shut down by
  Google; it will be removed in 7.0
* Deprecated the Google Tag Manager integration (`tags_manager_code` Twig function, `leapt_core.google_tags_manager`
  configuration); it will be removed in 7.0, add the official Google Tag Manager snippet to your templates instead
* Deprecated the `GoogleExtension` class
* Deprecated the Facebook SDK integration (`facebook_sdk_code` Twig function, `FacebookExtension` class,
  `leapt_core.facebook` configuration), as it relies on an outdated SDK version; it will be removed in 7.0
* Deprecated the Bootstrap 3 templates (`@LeaptCore/Datalist/datalist_bootstrap3_grid_layout.html.twig`,
  `@LeaptCore/Datalist/datalist_bootstrap3_tiled_layout.html.twig`, `@LeaptCore/Form/bootstrap_3_layout.html.twig` &
  `@LeaptCore/Paginator/paginator_bootstrap3_layout.html.twig`); they will be removed in 7.0, use the Bootstrap 5
  ones instead

6.4.0
-----

* Added support for `google/recaptcha` 2.x
* Fixed `RecaptchaV3Validator` throwing a `TypeError` when instantiated without a `private_key`, even with reCAPTCHA
  disabled; both reCAPTCHA validators now throw an explicit `LogicException` when reCAPTCHA is enabled without a
  `private_key`

6.3.0
-----

* `DoctrineORMPaginator` now uses `Doctrine\ORM\Tools\Pagination\OffsetPaginator` internally instead of the deprecated
  `Doctrine\ORM\Tools\Pagination\Paginator` (will be removed in `doctrine/orm` 4.0) when available, and gained a
  `$fetchJoinCollection` constructor argument; it transparently falls back to the deprecated class on older
  `doctrine/orm` versions, so the required version is unchanged
* Added a `setFetchJoinCollection()` method to `DoctrineORMDatasource`, to control the same option on the
  `DoctrineORMPaginator` it creates for pagination

6.2.0
-----

* Added a `time_ago` option to `DateTimeFieldType` to display the date using the `time_ago` Twig filter
* Fixed the `truncate` option being ignored by `TextFieldType` (and `HeadingFieldType`) in the tiled Datalist themes
* Added `StringUtil::ucfirst()` / `StringUtil::lcfirst()` (multi-byte safe) and the corresponding `ucfirst` / `lcfirst`
  Twig filters
* Added a `choice_translation_domain` option to `LabelFieldType`, mirroring Symfony's `ChoiceType` option of the same
  name, to disable or override the translation domain used for mapped labels

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
