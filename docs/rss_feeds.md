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
    public function __construct(
        private ArticleRepository $articleRepository,
        private RouterInterface $router,
    ) {
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

- <http://yourhost/feed/article> for RSS format
- <http://yourhost/feed/article.atom> for Atom format

And using Twig:

```twig
<link rel="alternate" type="application/rss+xml" href="{{ path('leapt_core_feed', { 'feedName': 'article', '_format': 'rss' }) }}">
<link rel="alternate" type="application/atom+xml" href="{{ path('leapt_core_feed', { 'feedName': 'article', '_format': 'atom' }) }}">
```
