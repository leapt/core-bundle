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
