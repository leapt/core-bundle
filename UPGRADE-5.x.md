Upgrade guide for 5.0
---------------------

* Requires PHP 8.2+
* Requires Symfony 6.4/7.0+
* Removed YAML routing files, import PHP routing files instead
* Following the services rewritten to PHP, all services are now defined using class FQCN instead of named services

The paths of the routing files have changed, you have to update them manually:

```yaml
# config/routes/leapt_core.yaml
leapt_core_feed:
    resource: '@LeaptCoreBundle/config/routing_feed.php'
    prefix: /feed

leapt_core_sitemap:
    resource: '@LeaptCoreBundle/config/routing_sitemap.php'
```
