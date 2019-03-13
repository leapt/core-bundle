2.4.2
-----

* Upgraded minimum PHP requirement to 7.1

2.4.1
-----

* Fixed deprecations with Twig >= 2.7.x by using namespaced classes
* Fixed tests
* Removed useless dependency for DatalistExtension

2.4.0
-----

* Datalist: Allow SearchFilterType to search by exploding terms, eg. searching for "my" "test" instead of "my test"

2.3.2
-----

* Make Controllers independent from Controller/AbstractController classes
* Fixed requirement: Twig bundle is now required
* FeedItem: fixed deprecation, using Type assert instead of DateTime assert
* Fixed Translator::transChoice() deprecation

2.3.1
-----

* Make CollectionTypeExtension::getExtendedTypes() static

2.3.0
-----

* Dropped compatibility with Symfony < 3.3
* Fixed most deprecations with Symfony 4.2
* Fixed dependencies in composer.json
