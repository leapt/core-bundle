# Sitemaps

## Enable routing

Create/update the `config/routes/leapt_core.yaml` file, add the following:

```yaml
leapt_core_sitemap:
    resource: '@LeaptCoreBundle/config/routing_sitemap.php'
```

## Create the service

Then create a file that extends `Leapt\CoreBundle\Sitemap\AbstractSitemap`:

```php
// src/Sitemap/Sitemap.php

namespace App\Sitemap;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Leapt\CoreBundle\Sitemap\AbstractSitemap;
use Symfony\Component\Routing\RouterInterface;

final class Sitemap extends AbstractSitemap
{
    public function __construct(
        private EntityManagerInterface $em,
        private string $locale,
    ) {
    }

    public function build(RouterInterface $router): void
    {
        // Homepage
        $this->addUrl($router->generate('app_default_index', ['_locale' => $this->locale], RouterInterface::ABSOLUTE_URL));

        // Pages
        $pages = $this->em->getRepository(Page::class)->findAllPublished($this->locale);
        foreach ($pages as $page) {
            $pageSlug = $page->getTranslations()->get($this->locale)->getSlug();
            $loc = $router->generate('app_page_view', ['slug' => $pageSlug, '_locale' => $this->locale], RouterInterface::ABSOLUTE_URL);
            $this->addUrl($loc, null, self::CHANGEFREQ_MONTHLY);
        }
    }
}
```

## Register the service

Any service extending `AbstractSitemap` is automatically registered as a sitemap, provided it is autoconfigured (which is the default for services declared under `App\` in a standard Symfony project). So if a single sitemap covering all locales is enough for your needs, there is nothing more to do: your `App\Sitemap\Sitemap` class will be picked up automatically, using its service id as its alias.

If you need several sitemaps instead (e.g. one per locale, like in the example above, which takes the locale as a constructor argument), you still have to declare them explicitly in your `services.yaml` file, with a tag giving each one an alias:

```yaml
services:
    # Sitemaps
    app.sitemap.fr:
        class: App\Sitemap\Sitemap
        arguments: [ '@doctrine.orm.entity_manager', 'fr' ]
        tags:
            - { name: leapt_core.sitemap, alias: fr }
    app.sitemap.en:
        class: App\Sitemap\Sitemap
        arguments: [ '@doctrine.orm.entity_manager', 'en' ]
        tags:
            - { name: leapt_core.sitemap, alias: en }
```

Now, your main sitemap is available at `http://yourhost/sitemap.xml`.

If you defined several with aliases then the main sitemap will list all of them, and according to the example you would get:

- sitemap.xml
- sitemap_fr.xml
- sitemap_en.xml
