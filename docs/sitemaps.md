# Sitemaps

## Enable routing

Create/update the `config/routes/leapt_core.yaml` file, add the following:

```yaml
leapt_core_sitemap:
    resource: '@LeaptCoreBundle/Resources/config/routing_sitemap.yml'
```

## Create the service

Then create a file that extends `Leapt\CoreBundle\Sitemap\AbstractSitemap`:

```php
// src/Sitemap/Sitemap.php

namespace App\Sitemap;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Leapt\CoreBundle\Sitemap\AbstractSitemap;
use Symfony\Component\Routing\Router;

final class Sitemap extends AbstractSitemap
{
    public function __construct(
        private EntityManagerInterface $em,
        private string $locale,
    ) {
    }

    public function build(Router $router)
    {
        // Homepage
        $this->addUrl($router->generate('app_default_index', ['_locale' => $this->locale], true));

        // Pages
        $pages = $this->em->getRepository(Page::class)->findAllPublished($this->locale);
        foreach ($pages as $page) {
            $pageSlug = $page->getTranslations()->get($this->locale)->getSlug();
            $loc = $router->generate('app_page_view', ['slug' => $pageSlug, '_locale' => $this->locale], true);
            $this->addUrl($loc, null, self::CHANGEFREQ_MONTHLY);
        }
    }
}
```

## Register the service

Finally all you need is to populate your `services.yaml` file with one or more Sitemap services like this:

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

If you defined several with aliases then the main sitemap will list all of them, and according the example you would get:

- sitemap.xml
- sitemap_fr.xml
- sitemap_en.xml
