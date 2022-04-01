# Paginator

The Paginator will help you iterate and paginate items.

It is provided in two versions: `ArrayPaginator` and `DoctrineORMPaginator`,
available under the `Leapt\CoreBundle\Paginator` namespace. The docs will explain how to use
the Doctrine ORM one, but it is easy to apply for the ArrayPaginator as well.

## Usage

```php
use Leapt\CoreBundle\Paginator\DoctrineORMPaginator;

// Get your QueryBuilder from some repository
$queryBuilder = $this->newsRepository->getActiveQueryBuilder();
$currentPage = $request->query->get('page', 1);

$paginator = new DoctrineORMPaginator($queryBuilder->getQuery());
$paginator
    ->setLimitPerPage(10)
    ->setRangeLimit(10)
    ->setPage($currentPage);

// Use it directly or pass it to Twig Template
// Direct iteration is possible as it is Traversable
foreach ($paginator as $news) {
    // ...
}
```

## Twig usage

As you can do it using PHP, you can also iterate on the Paginator to use/render items.

The `paginator_widget` function will render the pagination, if there are more than one page available.

```twig
{% if paginator|length > 0 %}
    {% for news in paginator %}
        {{ include('news/_item.html.twig') }}
    {% endfor %}
    
    {{ paginator_widget(paginator) }}
{% else %}
    <p>There are no news available.</p>
{% endif %}
```

## Twig Pagination

Three pagination templates are provided by the bundle (but you can of course create your own):

- `@LeaptCore/Paginator/paginator_default_layout.html.twig` (default)
- `@LeaptCore/Paginator/paginator_bootstrap3_layout.html.twig`
- `@LeaptCore/Paginator/paginator_bootstrap4_layout.html.twig`
- `@LeaptCore/Paginator/paginator_bootstrap5_layout.html.twig`

You can override the pagination template locally (like you would do for form themes):

```twig
{% paginator_theme paginator '@LeaptCore/Paginator/paginator_bootstrap5_layout.html.twig' %}
```

Or globally, in the configuration:

```yaml
# config/packages/leapt_core.yaml
leapt_core:
    paginator:
        template: '@LeaptCore/Paginator/paginator_bootstrap5_layout.html.twig'
```
