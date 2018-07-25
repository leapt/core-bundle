---
layout: default
permalink: /rss_feeds.html
---

# RSS Feeds

The Feed Helper allows you to easily create RSS Feeds in minutes.

## Enable routing

Create/update the `config/routes/leapt_core.yaml` file, add the following:

```yaml
leapt_core_feed:
    resource: '@LeaptCoreBundle/Resources/config/routing_feed.yml'
    prefix: /feed
```

## Create the service

Then create a file that implements `Leapt\CoreBundle\Feed\FeedInterface`:

```php
// src/Feed/ArticleFeed.php

namespace App\Feed;

use App\Repository\ArticleRepository;
use Leapt\CoreBundle\Feed\FeedInterface;
use Leapt\CoreBundle\Feed\FeedItem;
use Symfony\Component\Routing\RouterInterface;

final class ArticleFeed implements FeedInterface
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ArticleRepository $articleRepository, RouterInterface $router)
    {
        $this->articleRepository = $articleRepository;
        $this->router = $router;
    }

    public function getId(): string
    {
        return $this->router->generate('app_base_index', [], RouterInterface::ABSOLUTE_URL);
    }

    public function getLink(): string
    {
        return $this->router->generate('app_base_index', [], RouterInterface::ABSOLUTE_URL);
    }

    public function getTitle(): string
    {
        return 'ACME website';
    }

    public function getDescription(): string
    {
        return 'ACME Description';
    }

    public function getUpdatedAt(): \DateTime
    {
        $items = $this->getItems();

        return isset($items[0]) ? $items[0]->getPublicationDate() : new \DateTime();
    }

    public function getItems(): iterable
    {
        return $this->articleRepository->findLatest(30);
    }

    public function buildItem($item): FeedItem
    {
        $uri = $this->router->generate('app_article_view', [
            'slug' => $item->getSlug(),
        ], RouterInterface::ABSOLUTE_URL);

        $feedItem = new FeedItem();
        $feedItem->id = $uri;
        $feedItem->title = $item->getName();
        $feedItem->description = $item->getContent();
        $feedItem->createdAt = $item->getPublicationDate();
        $feedItem->updatedAt = $item->getLastUpdate();
        $feedItem->link = $uri;
        $feedItem->author = ['name' => $item->getAuthor(), 'email' => 'acme@website.com'];

        return $feedItem;
    }
}

```

## Register the service

Finally all you need is to populate your `services.yaml` file with one or more Feed services like this:

```yaml
services:
    # Feeds
    App\Feed\ArticleFeed:
        tags:
            - { name: leapt_core.feed, alias: article }
```

Now, your feed will be available at:

- <http://yourhost/feed/article?format=rss>
- <http://yourhost/feed/article?format=atom>

And using Twig:

{% assign rss_link = "{{ path('leapt_core_feed', { 'feedName': 'news', 'format': 'rss' }) }}" %}
{% assign atom_link = "{{ path('leapt_core_feed', { 'feedName': 'news', 'format': 'atom' }) }}" %}

```twig
<link rel="alternate" type="application/rss+xml" href="{{ rss_link }}">
<link rel="alternate" type="application/atom+xml" href="{{ atom_link }}">
```

----------

&larr; [Data lists](/data_lists.html)

[Sitemaps](/sitemaps.html) &rarr;
